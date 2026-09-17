<?php

/**
 * Compilable usage examples for element-instance operations.
 */

declare(strict_types=1);

namespace Camunda\Orchestration\Examples;

use Camunda\Orchestration\CamundaClient;

// region SearchElementInstanceWaitStates
/**
 * Search element instance wait states.
 */
function search_element_instance_wait_states(CamundaClient $client, ?\Camunda\Orchestration\Api\Model\ElementInstanceWaitStateQuery $elementInstanceWaitStateQuery = null): void
{
    $client->searchElementInstanceWaitStates($elementInstanceWaitStateQuery);
}
// endregion SearchElementInstanceWaitStates

// region SearchElementInstances
/**
 * Search element instances.
 */
function search_element_instances(CamundaClient $client, ?\Camunda\Orchestration\Api\Model\ElementInstanceSearchQuery $elementInstanceSearchQuery = null): void
{
    $client->searchElementInstances($elementInstanceSearchQuery);
}
// endregion SearchElementInstances

// region GetElementInstance
/**
 * Get element instance.
 */
function get_element_instance(CamundaClient $client, string $elementInstanceKey): void
{
    $client->getElementInstance($elementInstanceKey);
}
// endregion GetElementInstance

// region SearchElementInstanceIncidents
/**
 * Search for incidents of a specific element instance.
 */
function search_element_instance_incidents(CamundaClient $client, string $elementInstanceKey, \Camunda\Orchestration\Api\Model\IncidentSearchQuery $incidentSearchQuery): void
{
    $client->searchElementInstanceIncidents($elementInstanceKey, $incidentSearchQuery);
}
// endregion SearchElementInstanceIncidents

// region CreateElementInstanceVariables
/**
 * Update element instance variables.
 */
function create_element_instance_variables(CamundaClient $client, string $elementInstanceKey, \Camunda\Orchestration\Api\Model\SetVariableRequest $setVariableRequest): void
{
    $client->createElementInstanceVariables($elementInstanceKey, $setVariableRequest);
}
// endregion CreateElementInstanceVariables
