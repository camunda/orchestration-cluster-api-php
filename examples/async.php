<?php

/**
 * Compilable usage examples for PHP promise-based API calls.
 */

declare(strict_types=1);

namespace Camunda\Orchestration\Examples;

use Camunda\Orchestration\Api\Model\ProcessDefinitionSearchQueryResult;
use Camunda\Orchestration\Api\Model\TopologyResponse;
use Camunda\Orchestration\CamundaAsyncClient;

// region ParallelAsyncReads
function parallel_async_reads(CamundaAsyncClient $client): void
{
    // Requests are issued before either promise is awaited.
    $topologyPromise = $client->getTopology();
    $definitionsPromise = $client->searchProcessDefinitions();

    $topology = $topologyPromise->wait();
    $definitions = $definitionsPromise->wait();

    if ($topology instanceof TopologyResponse) {
        printf("Connected to %d broker(s).\n", count($topology->getBrokers()));
    }
    if ($definitions instanceof ProcessDefinitionSearchQueryResult) {
        printf("Found %d process definitions.\n", count($definitions->getItems()));
    }
}
// endregion ParallelAsyncReads
