<?php

/**
 * Compilable usage examples for incident operations.
 */

declare(strict_types=1);

namespace Camunda\Orchestration\Examples;

use Camunda\Orchestration\Api\Api\IncidentApi;
use Camunda\Orchestration\Api\Model\IncidentSearchQuery;
use Camunda\Orchestration\Api\Model\IncidentSearchQueryResult;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Semantic\IncidentKey;

// region ResolveIncident
function resolve_incident(CamundaClient $client, IncidentKey $key): void
{
    $api = $client->api(IncidentApi::class);
    $api->resolveIncident((string) $key);
}
// endregion ResolveIncident

// region SearchIncidents
function search_incidents(CamundaClient $client): void
{
    $api = $client->api(IncidentApi::class);

    $result = $api->searchIncidents(new IncidentSearchQuery());

    if ($result instanceof IncidentSearchQueryResult) {
        foreach ($result->getItems() as $incident) {
            echo (string) $incident->getIncidentKey(), ': ', $incident->getErrorType()->value, "\n";
        }
    }
}
// endregion SearchIncidents
