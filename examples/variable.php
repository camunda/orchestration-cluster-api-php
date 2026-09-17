<?php

/**
 * Compilable usage examples for process-variable operations.
 */

declare(strict_types=1);

namespace Camunda\Orchestration\Examples;

use Camunda\Orchestration\CamundaClient;

// region SearchVariables
/**
 * Search variables.
 */
function search_variables(CamundaClient $client, ?bool $truncateValues = null, ?\Camunda\Orchestration\Api\Model\VariableSearchQuery $variableSearchQuery = null): void
{
    $client->searchVariables($truncateValues, $variableSearchQuery);
}
// endregion SearchVariables

// region GetVariable
/**
 * Get variable.
 */
function get_variable(CamundaClient $client, string $variableKey): void
{
    $client->getVariable($variableKey);
}
// endregion GetVariable
