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
        $this->jobApi->completeJob($this->jobKey(), $request);
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
        $this->jobApi->failJob($this->jobKey(), $request);
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
        $this->jobApi->throwJobError($this->jobKey(), $request);
        $this->handled = true;
    }

    public function wasHandled(): bool
    {
        return $this->handled;
    }

    private function jobKey(): string
    {
        return (string) $this->job->getJobKey();
    }
}
