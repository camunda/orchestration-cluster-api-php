# EvaluateDecisionResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**decisionDefinitionId** | **string** | The ID of the decision which was evaluated. |
**decisionDefinitionKey** | **string** | The unique key identifying the decision which was evaluated. |
**decisionDefinitionName** | **string** | The name of the decision which was evaluated. |
**decisionDefinitionVersion** | **int** | The version of the decision which was evaluated. |
**decisionEvaluationKey** | **string** | The unique key identifying this decision evaluation. |
**decisionInstanceKey** | **string** | Deprecated, please refer to &#x60;decisionEvaluationKey&#x60;. |
**decisionRequirementsId** | **string** | The ID of the decision requirements graph that the decision which was evaluated is part of. |
**decisionRequirementsKey** | **string** | The unique key identifying the decision requirements graph that the decision which was evaluated is part of. |
**evaluatedDecisions** | [**\Camunda\Orchestration\Api\Model\EvaluatedDecisionResult[]**](EvaluatedDecisionResult.md) | Decisions that were evaluated within the requested decision evaluation. |
**failedDecisionDefinitionId** | **string** | The ID of the decision which failed during evaluation. |
**failureMessage** | **string** | Message describing why the decision which was evaluated failed. |
**output** | **string** | JSON document that will instantiate the result of the decision which was evaluated. |
**tenantId** | **string** | The tenant ID of the evaluated decision. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
