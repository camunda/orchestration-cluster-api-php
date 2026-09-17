<?php

/**
 * Compilable usage examples for decision-definition, decision-instance, and DRD operations.
 */

declare(strict_types=1);

namespace Camunda\Orchestration\Examples;

use Camunda\Orchestration\CamundaClient;

// region EvaluateDecision
/**
 * Evaluate decision.
 */
function evaluate_decision(CamundaClient $client, \Camunda\Orchestration\Api\Model\DecisionEvaluationById|\Camunda\Orchestration\Api\Model\DecisionEvaluationByKey $decisionEvaluationInstruction): void
{
    $client->evaluateDecision($decisionEvaluationInstruction);
}
// endregion EvaluateDecision

// region SearchDecisionDefinitions
/**
 * Search decision definitions.
 */
function search_decision_definitions(CamundaClient $client, ?\Camunda\Orchestration\Api\Model\DecisionDefinitionSearchQuery $decisionDefinitionSearchQuery = null): void
{
    $client->searchDecisionDefinitions($decisionDefinitionSearchQuery);
}
// endregion SearchDecisionDefinitions

// region GetDecisionDefinition
/**
 * Get decision definition.
 */
function get_decision_definition(CamundaClient $client, string $decisionDefinitionKey): void
{
    $client->getDecisionDefinition($decisionDefinitionKey);
}
// endregion GetDecisionDefinition

// region GetDecisionDefinitionXML
/**
 * Get decision definition XML.
 */
function get_decision_definition_xml(CamundaClient $client, string $decisionDefinitionKey): void
{
    $client->getDecisionDefinitionXML($decisionDefinitionKey);
}
// endregion GetDecisionDefinitionXML

// region SearchDecisionInstances
/**
 * Search decision instances.
 */
function search_decision_instances(CamundaClient $client, ?\Camunda\Orchestration\Api\Model\DecisionInstanceSearchQuery $decisionInstanceSearchQuery = null): void
{
    $client->searchDecisionInstances($decisionInstanceSearchQuery);
}
// endregion SearchDecisionInstances

// region GetDecisionInstance
/**
 * Get decision instance.
 */
function get_decision_instance(CamundaClient $client, string $decisionEvaluationInstanceKey): void
{
    $client->getDecisionInstance($decisionEvaluationInstanceKey);
}
// endregion GetDecisionInstance

// region DeleteDecisionInstance
/**
 * Delete decision instance.
 */
function delete_decision_instance(CamundaClient $client, string $decisionEvaluationKey, ?\Camunda\Orchestration\Api\Model\DeleteDecisionInstanceRequest $deleteDecisionInstanceRequest = null): void
{
    $client->deleteDecisionInstance($decisionEvaluationKey, $deleteDecisionInstanceRequest);
}
// endregion DeleteDecisionInstance

// region DeleteDecisionInstancesBatchOperation
/**
 * Delete decision instances (batch).
 */
function delete_decision_instances_batch_operation(CamundaClient $client, \Camunda\Orchestration\Api\Model\DecisionInstanceDeletionBatchOperationRequest $decisionInstanceDeletionBatchOperationRequest): void
{
    $client->deleteDecisionInstancesBatchOperation($decisionInstanceDeletionBatchOperationRequest);
}
// endregion DeleteDecisionInstancesBatchOperation

// region SearchDecisionRequirements
/**
 * Search decision requirements.
 */
function search_decision_requirements(CamundaClient $client, ?\Camunda\Orchestration\Api\Model\DecisionRequirementsSearchQuery $decisionRequirementsSearchQuery = null): void
{
    $client->searchDecisionRequirements($decisionRequirementsSearchQuery);
}
// endregion SearchDecisionRequirements

// region GetDecisionRequirements
/**
 * Get decision requirements.
 */
function get_decision_requirements(CamundaClient $client, string $decisionRequirementsKey): void
{
    $client->getDecisionRequirements($decisionRequirementsKey);
}
// endregion GetDecisionRequirements

// region GetDecisionRequirementsXML
/**
 * Get decision requirements XML.
 */
function get_decision_requirements_xml(CamundaClient $client, string $decisionRequirementsKey): void
{
    $client->getDecisionRequirementsXML($decisionRequirementsKey);
}
// endregion GetDecisionRequirementsXML
