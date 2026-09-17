<?php

/**
 * Compilable usage examples for process-definition operations.
 */

declare(strict_types=1);

namespace Camunda\Orchestration\Examples;

use Camunda\Orchestration\CamundaClient;

// region SearchProcessDefinitions
/**
 * Search process definitions.
 */
function search_process_definitions(CamundaClient $client, ?\Camunda\Orchestration\Api\Model\ProcessDefinitionSearchQuery $processDefinitionSearchQuery = null): void
{
    $client->searchProcessDefinitions($processDefinitionSearchQuery);
}
// endregion SearchProcessDefinitions

// region GetProcessDefinitionMessageSubscriptionStatistics
/**
 * Get message subscription statistics.
 */
function get_process_definition_message_subscription_statistics(CamundaClient $client, ?\Camunda\Orchestration\Api\Model\ProcessDefinitionMessageSubscriptionStatisticsQuery $processDefinitionMessageSubscriptionStatisticsQuery = null): void
{
    $client->getProcessDefinitionMessageSubscriptionStatistics($processDefinitionMessageSubscriptionStatisticsQuery);
}
// endregion GetProcessDefinitionMessageSubscriptionStatistics

// region GetProcessDefinitionInstanceStatistics
/**
 * Get process instance statistics.
 */
function get_process_definition_instance_statistics(CamundaClient $client, ?\Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceStatisticsQuery $processDefinitionInstanceStatisticsQuery = null): void
{
    $client->getProcessDefinitionInstanceStatistics($processDefinitionInstanceStatisticsQuery);
}
// endregion GetProcessDefinitionInstanceStatistics

// region GetProcessDefinition
/**
 * Get process definition.
 */
function get_process_definition(CamundaClient $client, string $processDefinitionKey): void
{
    $client->getProcessDefinition($processDefinitionKey);
}
// endregion GetProcessDefinition

// region GetStartProcessForm
/**
 * Get process start form.
 */
function get_start_process_form(CamundaClient $client, string $processDefinitionKey): void
{
    $client->getStartProcessForm($processDefinitionKey);
}
// endregion GetStartProcessForm

// region GetProcessDefinitionStatistics
/**
 * Get process definition statistics.
 */
function get_process_definition_statistics(CamundaClient $client, string $processDefinitionKey, ?\Camunda\Orchestration\Api\Model\ProcessDefinitionElementStatisticsQuery $processDefinitionElementStatisticsQuery = null): void
{
    $client->getProcessDefinitionStatistics($processDefinitionKey, $processDefinitionElementStatisticsQuery);
}
// endregion GetProcessDefinitionStatistics

// region SearchProcessDefinitionVariableNames
/**
 * Search process definition variable names.
 */
function search_process_definition_variable_names(CamundaClient $client, string $processDefinitionKey, ?\Camunda\Orchestration\Api\Model\ProcessDefinitionVariableNameSearchQuery $processDefinitionVariableNameSearchQuery = null): void
{
    $client->searchProcessDefinitionVariableNames($processDefinitionKey, $processDefinitionVariableNameSearchQuery);
}
// endregion SearchProcessDefinitionVariableNames

// region GetProcessDefinitionXML
/**
 * Get process definition XML.
 */
function get_process_definition_xml(CamundaClient $client, string $processDefinitionKey): void
{
    $client->getProcessDefinitionXML($processDefinitionKey);
}
// endregion GetProcessDefinitionXML

// region GetProcessDefinitionInstanceVersionStatistics
/**
 * Get process instance statistics by version.
 */
function get_process_definition_instance_version_statistics(CamundaClient $client, \Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceVersionStatisticsQuery $processDefinitionInstanceVersionStatisticsQuery): void
{
    $client->getProcessDefinitionInstanceVersionStatistics($processDefinitionInstanceVersionStatisticsQuery);
}
// endregion GetProcessDefinitionInstanceVersionStatistics
