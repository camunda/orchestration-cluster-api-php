<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Worker;

use Camunda\Orchestration\Api\Model\ActivatedJobResult;

/**
 * Handles a single activated job.
 *
 * A handler may act explicitly on the job via the supplied {@see JobActionClient}
 * (complete / fail / error). If it takes no action and the worker has auto-complete
 * enabled, returning an array of variables completes the job with them; returning
 * null completes it with no variables.
 *
 * Any handler alternatively may be expressed as a `callable(ActivatedJobResult,
 * JobActionClient): (array<string,mixed>|null|void)`.
 */
interface JobHandler
{
    /**
     * @return array<string, mixed>|null
     */
    public function handle(ActivatedJobResult $job, JobActionClient $action): ?array;
}
