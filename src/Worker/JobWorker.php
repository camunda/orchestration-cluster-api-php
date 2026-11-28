<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Worker;

use Camunda\Orchestration\Api\Api\JobApi;
use Camunda\Orchestration\Api\ApiException;
use Camunda\Orchestration\Api\Model\ActivatedJobResult;
use Camunda\Orchestration\Api\Model\JobActivationRequest;
use Camunda\Orchestration\Api\Model\ProblemDetail;
use Camunda\Orchestration\Api\Model\TenantFilterEnum;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Exception\RetryExhaustedException;

/**
 * A long-polling job worker.
 *
 * The worker repeatedly activates jobs of a configured type and dispatches each to a
 * handler. Handlers may act on the job explicitly through a {@see JobActionClient}, or
 * rely on auto-completion (see {@see JobWorkerOptions::$autoComplete}).
 *
 * ```php
 * $worker = $client->createJobWorker(new JobWorkerOptions(type: 'process-payment'));
 * $worker->run(function (ActivatedJobResult $job, JobActionClient $action): array {
 *     // ... do work ...
 *     return ['status' => 'paid'];
 * });
 * ```
 *
 * When {@see JobWorkerOptions::$forked} is enabled and ext-pcntl is available, each job
 * is processed in a forked child process, bounded to `maxJobs` concurrent children.
 */
final class JobWorker
{
    private bool $running = false;

    public function __construct(
        private readonly CamundaClient $client,
        private readonly JobWorkerOptions $options,
    ) {
    }

    /**
     * Run the worker loop until {@see stop()} is called or a signal interrupts it.
     *
     * @param callable(ActivatedJobResult, JobActionClient): (array<string,mixed>|null|void)|JobHandler $handler
     */
    public function run(callable|JobHandler $handler): void
    {
        $this->running = true;
        while ($this->running) {
            $processed = $this->pollOnce($handler);
            if ($processed === 0 && $this->running && $this->options->pollIntervalMs > 0) {
                usleep($this->options->pollIntervalMs * 1000);
            }
        }
    }

    /**
     * Signal the worker loop to stop after the current iteration.
     */
    public function stop(): void
    {
        $this->running = false;
    }

    /**
     * Activate a single batch of jobs and dispatch them to the handler.
     *
     * @param callable(ActivatedJobResult, JobActionClient): (array<string,mixed>|null|void)|JobHandler $handler
     * @return int The number of jobs processed in this poll.
     *
     * @phpstan-impure
     */
    public function pollOnce(callable|JobHandler $handler): int
    {
        $jobs = $this->activate();
        if ($jobs === []) {
            return 0;
        }

        $forked = $this->options->forked && \function_exists('pcntl_fork');
        $children = 0;

        foreach ($jobs as $job) {
            if ($forked) {
                $pid = pcntl_fork();
                if ($pid === -1) {
                    $this->dispatch($handler, $job);
                } elseif ($pid === 0) {
                    $this->runChild($handler, $job);
                } else {
                    ++$children;
                    if ($children >= $this->options->maxJobs) {
                        $this->reap($children);
                        $children = 0;
                    }
                }
            } else {
                $this->dispatch($handler, $job);
            }
        }

        if ($forked && $children > 0) {
            $this->reap($children);
        }

        return \count($jobs);
    }

    /**
     * @return list<ActivatedJobResult>
     *
     * @throws ApiException When activation fails (e.g. broker backpressure `503`).
     *         The generated sync API returns error statuses as a `ProblemDetail`
     *         value; the worker converts that into an exception so callers can
     *         back off instead of mistaking the failure for an empty batch
     *         (issue #85).
     */
    private function activate(): array
    {
        $request = new JobActivationRequest();
        $request->setType($this->options->type);
        $request->setMaxJobsToActivate($this->options->maxJobs);
        $request->setTimeout($this->options->timeoutMs);
        $request->setRequestTimeout($this->options->requestTimeoutMs);
        $request->setWorker($this->options->workerName ?? 'camunda-orchestration-php');
        if ($this->options->fetchVariables !== []) {
            $request->setFetchVariable($this->options->fetchVariables);
        }
        // An explicit worker tenant list wins; otherwise activate for the client's
        // default tenant(s) — `<default>` when none is configured (issue #138).
        $tenantIds = $this->options->tenantIds !== []
            ? $this->options->tenantIds
            : $this->client->configuration()->defaultJobTenantIds();
        $request->setTenantIds($tenantIds);
        // Tenant-scoped activation requires the explicit PROVIDED filter — the spec
        // default only applies when the field is sent, and unset optional fields are
        // omitted from the request body (issue #53).
        $request->setTenantFilter(TenantFilterEnum::PROVIDED);

        [$result, $httpStatus, $headers] = $this->jobApi()->activateJobsWithHttpInfo($request);
        if ($result instanceof ProblemDetail || $httpStatus < 200 || $httpStatus > 299) {
            // The HTTP status is authoritative: a problem body may omit `status`.
            $title = $result instanceof ProblemDetail ? $result->offsetGet('title') : null;
            $detail = $result instanceof ProblemDetail ? $result->offsetGet('detail') : null;
            $exception = new ApiException(
                sprintf(
                    '[%d] Job activation failed (%s: %s)',
                    $httpStatus,
                    is_string($title) ? $title : 'unknown',
                    is_string($detail) ? $detail : 'no detail',
                ),
                $httpStatus,
                $headers,
            );
            $exception->setResponseObject($result);
            throw $exception;
        }

        return array_values($result->getJobs());
    }

    /**
     * Dispatch a single job to the handler, turning a throwing handler into a job
     * failure rather than letting it escape the worker.
     *
     * @param callable(ActivatedJobResult, JobActionClient): (array<string,mixed>|null|void)|JobHandler $handler
     */
    private function dispatch(callable|JobHandler $handler, ActivatedJobResult $job, ?CamundaClient $client = null): void
    {
        $client ??= $this->client;
        /** @var JobApi $jobApi */
        $jobApi = $client->api(JobApi::class);
        $action = new JobActionClient($jobApi, $job, $client);

        try {
            $result = $handler instanceof JobHandler
                ? $handler->handle($job, $action)
                : $handler($job, $action);
        } catch (\Throwable $throwable) {
            if ($action->hasInterruptedAction() && $this->absorbJobCommandFailure($throwable, $job)) {
                return;
            }
            if ($action->hasInterruptedAction()) {
                // The handler asked for a terminal action and the request for it
                // failed. Substituting our own failure would discard the retries,
                // back-off and message it asked for and hide the transport error, so
                // this keeps propagating as it always has.
                throw $throwable;
            }

            // Issue #109: an uncaught handler exception used to propagate out of the
            // dispatch loop, leaving the job leased until `timeoutMs`, dropping the
            // rest of the batch and stopping `run()`. Mirror the JS SDK instead: fail
            // the job unless the handler already took a terminal action, then carry on
            // with the batch and keep polling. The message carries the exception class
            // and message only — never the stack trace, which can hold unrelated data.
            if (!$action->wasHandled()) {
                try {
                    $action->fail(
                        retries: max(0, $job->getRetries() - 1),
                        errorMessage: self::failureMessage($throwable),
                    );
                } catch (\Throwable $failure) {
                    if (!$this->absorbJobCommandFailure($failure, $job)) {
                        throw $failure;
                    }
                }
            }

            return;
        }

        if (!$action->wasHandled() && $this->options->autoComplete) {
            try {
                $action->complete(\is_array($result) ? $result : []);
            } catch (\Throwable $failure) {
                if (!$this->absorbJobCommandFailure($failure, $job)) {
                    throw $failure;
                }
            }
        }
    }

    /**
     * Handle a failed job command (complete, fail, throw error) whose failure must not stop
     * the worker (issue #193), and report whether it was absorbed.
     *
     * - `404`: the job is gone (already completed, failed or timed out). Logged as a warning
     *   without a stack trace, then still propagated as a failed action (not absorbed).
     * - Unknown outcome (`500`, `502`, `504`, or no response after the request may have been
     *   sent): the command may have been applied, so no follow-up `failJob` is sent. Logged
     *   as a warning; if the command was not applied, the job's lease timeout re-activates it.
     *
     * Any other failure (e.g. retries exhausted on `429`/`503`: the command was not applied)
     * is not handled here and keeps propagating.
     */
    private function absorbJobCommandFailure(\Throwable $failure, ActivatedJobResult $job): bool
    {
        if (!$failure instanceof ApiException || $failure instanceof RetryExhaustedException) {
            return false;
        }
        $status = $failure->getCode();
        $key = (string) $job->getJobKey();
        if ($status === 404) {
            // Still reported as a failed action: it propagates, as before.
            $this->warn(sprintf('Job %s: job not found (already completed, failed or timed out)', $key));

            return false;
        }
        if (\in_array($status, [0, 500, 502, 504], true)) {
            $this->warn(sprintf(
                'Job %s: job command outcome unknown (%s); the job may have completed, otherwise it will time out and be re-activated',
                $key,
                $status === 0 ? 'no response' : 'HTTP ' . $status,
            ));

            return true;
        }

        return false;
    }

    private function warn(string $message): void
    {
        if ($this->options->logger !== null) {
            $this->options->logger->warning($message);
        } else {
            error_log('[camunda job worker] ' . $message);
        }
    }

    /**
     * The message reported to the broker for a job whose handler threw.
     *
     * Carries the exception class and message only — never the stack trace, which can
     * hold unrelated data. The text is forced to valid UTF-8 first: a handler is free
     * to throw with a message built from binary data, and the fail request is JSON, so
     * an unencodable message would throw out of the catch block above and re-create the
     * very defect this guards against.
     */
    private static function failureMessage(\Throwable $throwable): string
    {
        return mb_convert_encoding($throwable::class . ': ' . $throwable->getMessage(), 'UTF-8', 'UTF-8');
    }

    /**
     * Process one job in a forked child and end the child (issue #187).
     *
     * The child builds its own client, so its requests never travel over the keep-alive
     * connection it inherited from the parent. It then ends without running destructors: the
     * inherited curl handles belong to the parent, and cleaning them up in the child can
     * deadlock (curl's resolver thread pool does not exist after a fork).
     *
     * @param callable(ActivatedJobResult, JobActionClient): (array<string,mixed>|null|void)|JobHandler $handler
     */
    private function runChild(callable|JobHandler $handler, ActivatedJobResult $job): never
    {
        $status = 0;
        try {
            $this->dispatch($handler, $job, $this->client->forChildProcess());
        } catch (\Throwable $throwable) {
            // Nothing crosses the process boundary; report and end the child.
            $status = 1;
            try {
                fwrite(\STDERR, sprintf(
                    "Job %s: %s\n",
                    (string) $job->getJobKey(),
                    self::failureMessage($throwable),
                ));
            } catch (\Throwable) {
                // Reporting failed (e.g. closed STDERR); the child must still end.
            }
        } finally {
            // Always end the child here: it must never return into the parent's loop.
            self::terminateChild($status);
        }
    }

    /**
     * Flush and close every output buffer. Stops at a buffer that cannot be
     * removed (ob_end_flush() fails without lowering the level), flushing it
     * if it allows that, so the loop always terminates.
     *
     * @internal
     */
    public static function drainOutputBuffers(): void
    {
        while (ob_get_level() > 0) {
            $status = ob_get_status();
            $flags = \is_int($status['flags'] ?? null) ? $status['flags'] : 0;
            if (0 === ($flags & \PHP_OUTPUT_HANDLER_REMOVABLE)) {
                // Checking flags first avoids notices that error handlers may
                // turn into exceptions, and a double callback invocation.
                if (0 !== ($flags & \PHP_OUTPUT_HANDLER_FLUSHABLE)) {
                    ob_flush();
                }
                break;
            }
            $level = ob_get_level();
            if (!@ob_end_flush() || ob_get_level() >= $level) {
                break;
            }
        }
    }

    /**
     * @internal
     */
    public static function terminateChild(int $status): never
    {
        try {
            self::drainOutputBuffers();
            $remaining = ob_get_level();
            if ($remaining > 0) {
                // A non-removable buffer stopped the drain; whatever it and the
                // buffers below it hold is lost when the child is killed. Say so.
                fwrite(\STDERR, \sprintf(
                    "[camunda job worker] forked child exiting with %d output buffer(s) that could not be removed; their content is discarded\n",
                    $remaining,
                ));
            }
            fflush(\STDOUT);
            fflush(\STDERR);
        } catch (\Throwable) {
            // An output handler threw; termination must still happen.
            $status = 1;
        } finally {
            if (\function_exists('posix_kill') && \function_exists('posix_getpid')) {
                // SIGKILL skips shutdown functions and destructors, which is the point.
                posix_kill(posix_getpid(), \SIGKILL);
            }
            // Without ext-posix this is the best plain PHP offers.
            exit($status);
        }
    }

    private function reap(int $count): void
    {
        for ($i = 0; $i < $count; ++$i) {
            pcntl_wait($status);
        }
    }

    private function jobApi(): JobApi
    {
        /** @var JobApi */
        return $this->client->api(JobApi::class);
    }
}
