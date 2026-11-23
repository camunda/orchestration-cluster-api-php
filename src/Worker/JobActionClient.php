<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Worker;

use Camunda\Orchestration\Api\Api\JobApi;
use Camunda\Orchestration\Api\Model\ActivatedJobResult;
use Camunda\Orchestration\Api\Model\JobCompletionRequest;
use Camunda\Orchestration\Api\Model\JobErrorRequest;
use Camunda\Orchestration\Api\Model\JobFailRequest;
use Camunda\Orchestration\CamundaClient;

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
        private readonly ?CamundaClient $client = null,
    ) {
    }

    /**
     * The client the worker uses for this job, for any further API calls the handler makes.
     *
     * In forked mode this is a client with its own HTTP connections, built in the child
     * process. Use it rather than a `CamundaClient` captured from the parent: the parent's
     * connections must not be shared between processes (issue #187).
     *
     * @throws \LogicException When this action client was built without one.
     */
    public function client(): CamundaClient
    {
        if ($this->client === null) {
            throw new \LogicException('This JobActionClient was created without a CamundaClient.');
        }

        return $this->client;
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
        $this->actionInFlight = true;
        $this->jobApi->completeJob($this->jobKey(), $request);
        $this->actionInFlight = false;
        $this->handled = true;
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
        $this->actionInFlight = true;
        $this->jobApi->failJob($this->jobKey(), $request);
        $this->actionInFlight = false;
        $this->handled = true;
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
        $this->actionInFlight = true;
        $this->jobApi->throwJobError($this->jobKey(), $request);
        $this->actionInFlight = false;
        $this->handled = true;
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

    private function jobKey(): string
    {
        return (string) $this->job->getJobKey();
    }
}
