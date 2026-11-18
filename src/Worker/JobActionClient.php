<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Worker;

use Camunda\Orchestration\Api\Api\JobApi;
use Camunda\Orchestration\Api\Model\ActivatedJobResult;
use Camunda\Orchestration\Api\Model\JobCompletionRequest;
use Camunda\Orchestration\Api\Model\JobErrorRequest;
use Camunda\Orchestration\Api\Model\JobFailRequest;

/**
 * The action surface handed to a job handler: complete, fail, or raise a BPMN error
 * for the job currently being processed.
 *
 * Exactly one terminal action should be taken per job. Once an action is taken the
 * worker will not auto-complete.
 */
final class JobActionClient
{
    private bool $handled = false;

    /** True while a terminal-action request is in flight; still true if it threw. */
    private bool $actionInFlight = false;

    public function __construct(
        private readonly JobApi $jobApi,
        private readonly ActivatedJobResult $job,
    ) {
    }

    /**
     * Successfully complete the job, optionally setting process variables.
     *
     * @param array<string, mixed> $variables
     */
    public function complete(array $variables = []): void
    {
        $request = new JobCompletionRequest();
        if ($variables !== []) {
            $request->setVariables($variables);
        }
        $this->send(static fn (JobApi $api, string $key) => $api->completeJob($key, $request));
    }

    /**
     * Fail the job, scheduling a retry when $retries > 0.
     *
     * @param array<string, mixed> $variables
     */
    public function fail(int $retries = 0, string $errorMessage = '', int $retryBackOffMs = 0, array $variables = []): void
    {
        $request = new JobFailRequest();
        $request->setRetries($retries);
        if ($errorMessage !== '') {
            $request->setErrorMessage($errorMessage);
        }
        if ($retryBackOffMs > 0) {
            $request->setRetryBackOff($retryBackOffMs);
        }
        if ($variables !== []) {
            $request->setVariables($variables);
        }
        $this->send(static fn (JobApi $api, string $key) => $api->failJob($key, $request));
    }

    /**
     * Raise a BPMN error to be caught by an error boundary event.
     *
     * @param array<string, mixed> $variables
     */
    public function error(string $errorCode, string $errorMessage = '', array $variables = []): void
    {
        $request = new JobErrorRequest();
        $request->setErrorCode($errorCode);
        if ($errorMessage !== '') {
            $request->setErrorMessage($errorMessage);
        }
        if ($variables !== []) {
            $request->setVariables($variables);
        }
        $this->send(static fn (JobApi $api, string $key) => $api->throwJobError($key, $request));
    }

    public function wasHandled(): bool
    {
        return $this->handled;
    }

    /**
     * Whether a terminal action was started and did not come back.
     *
     * The worker uses this to tell "the handler's own code threw" from "the handler
     * asked for a terminal action and the request for it failed". Substituting its own
     * failure for the second case would discard the retries, back-off and message the
     * handler asked for, and swallow the transport error.
     */
    public function hasInterruptedAction(): bool
    {
        return $this->actionInFlight;
    }

    /**
     * Send a terminal-action request, recording whether it was interrupted.
     *
     * An `\\InvalidArgumentException` is raised by the generated client while it builds
     * the request (e.g. variables that cannot be JSON-encoded), before anything is
     * sent. That is a local data problem, not an interrupted request, so it does not
     * mark the action as in flight: the worker then turns it into a normal job failure
     * instead of leaving the job leased.
     *
     * @param \Closure(JobApi, string): mixed $request
     */
    private function send(\Closure $request): void
    {
        $this->actionInFlight = true;
        try {
            $request($this->jobApi, $this->jobKey());
        } catch (\InvalidArgumentException $invalid) {
            $this->actionInFlight = false;

            throw $invalid;
        }
        $this->actionInFlight = false;
        $this->handled = true;
    }

    private function jobKey(): string
    {
        return (string) $this->job->getJobKey();
    }
}
