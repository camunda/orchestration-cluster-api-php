<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Worker;

/**
 * Immutable configuration for a {@see JobWorker}.
 */
final class JobWorkerOptions
{
    /**
     * @param string $type The job type to activate (BPMN task definition type).
     * @param int $maxJobs Maximum number of jobs to hold/activate per poll.
     * @param int $timeoutMs Job activation lease timeout in milliseconds.
     * @param int $requestTimeoutMs Long-poll request timeout in milliseconds.
     * @param list<string> $fetchVariables Restrict fetched variables (empty = all).
     * @param bool $autoComplete Auto-complete jobs when the handler returns without acting.
     * @param int $pollIntervalMs Delay between polls when the previous poll returned no jobs.
     * @param bool $forked Fork a child process per job (requires ext-pcntl).
     * @param string|null $workerName Worker name reported to the broker.
     * @param list<string> $tenantIds Tenants to activate jobs for (empty = default).
     */
    public function __construct(
        public readonly string $type,
        public readonly int $maxJobs = 32,
        public readonly int $timeoutMs = 60000,
        public readonly int $requestTimeoutMs = 10000,
        public readonly array $fetchVariables = [],
        public readonly bool $autoComplete = true,
        public readonly int $pollIntervalMs = 250,
        public readonly bool $forked = false,
        public readonly ?string $workerName = null,
        public readonly array $tenantIds = [],
    ) {
    }
}
