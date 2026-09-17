<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Worker;

use Camunda\Orchestration\Api\Api\JobApi;
use Camunda\Orchestration\Api\Model\ActivatedJobResult;
use Camunda\Orchestration\Api\Model\JobActivationRequest;
use Camunda\Orchestration\Api\Model\JobActivationResult;
use Camunda\Orchestration\CamundaClient;

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
                    $this->dispatch($handler, $job);
                    exit(0);
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
        if ($this->options->tenantIds !== []) {
            $request->setTenantIds($this->options->tenantIds);
        }

        $result = $this->jobApi()->activateJobs($request);
        if (!$result instanceof JobActivationResult) {
            return [];
        }

        return array_values($result->getJobs());
    }

    /**
     * @param callable(ActivatedJobResult, JobActionClient): (array<string,mixed>|null|void)|JobHandler $handler
     */
    private function dispatch(callable|JobHandler $handler, ActivatedJobResult $job): void
    {
        $action = new JobActionClient($this->jobApi(), $job);

        $result = $handler instanceof JobHandler
            ? $handler->handle($job, $action)
            : $handler($job, $action);

        if (!$action->wasHandled() && $this->options->autoComplete) {
            $action->complete(\is_array($result) ? $result : []);
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
