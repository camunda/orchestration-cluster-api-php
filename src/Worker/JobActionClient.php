<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Worker;

use Camunda\Orchestration\Api\Api\JobApi;
use Camunda\Orchestration\Api\ApiException;
use Camunda\Orchestration\Api\Model\ActivatedJobResult;
use Camunda\Orchestration\Api\Model\JobCompletionRequest;
use Camunda\Orchestration\Api\Model\JobErrorRequest;
use Camunda\Orchestration\Api\Model\JobFailRequest;
use Camunda\Orchestration\Exception\AuthenticationException;

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
     * The flag is set only while the request is on the wire. Anything thrown while the
     * generated client builds the request — an `InvalidArgumentException` for variables
     * that cannot be JSON-encoded, or any other `Throwable` escaping serialization
     * (e.g. a lazy value that throws mid-encode) — is a local data problem raised
     * before anything was sent, not an interrupted request. It therefore never leaves
     * the flag set: the worker turns it into a normal job failure instead of leaving
     * the job leased.
     *
     * An `ApiException` is different: the generated client raises it for the HTTP
     * exchange itself (a non-2xx response, or a request/connect failure it wraps), so
     * the request did go out and its outcome is unknown. The flag stays set and the
     * worker keeps propagating it rather than substituting its own failure.
     *
     * An `AuthenticationException` is interrupted the same way: the auth middleware
     * raises it while the request is on the wire when an OAuth token refresh fails, so
     * the action never completed. Clearing the flag would let the worker substitute a
     * `fail(retries - 1)` that consumes a retry or creates an incident, hiding the
     * authentication failure — so it is preserved alongside `ApiException`.
     *
     * @param \Closure(JobApi, string): mixed $request
     */
    private function send(\Closure $request): void
    {
        $this->actionInFlight = true;
        try {
            $request($this->jobApi, $this->jobKey());
        } catch (ApiException|AuthenticationException $interrupted) {
            throw $interrupted;
        } catch (\Throwable $throwable) {
            $this->actionInFlight = false;

            throw $throwable;
        }
        $this->actionInFlight = false;
        $this->handled = true;
    }

    private function jobKey(): string
    {
        return (string) $this->job->getJobKey();
    }
}
