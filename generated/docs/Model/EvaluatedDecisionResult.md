# EvaluatedDecisionResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**decisionDefinitionId** | **string** | The ID of the decision which was evaluated. |
**decisionDefinitionName** | **string** | The name of the decision which was evaluated. |
**decisionDefinitionVersion** | **int** | The version of the decision which was evaluated. |
**decisionDefinitionType** | **string** | The type of the decision which was evaluated. |
**output** | **string** | JSON document that will instantiate the result of the decision which was evaluated. |
**tenantId** | **string** | The tenant ID of the evaluated decision. |
**matchedRules** | [**\Camunda\Orchestration\Api\Model\MatchedDecisionRuleItem[]**](MatchedDecisionRuleItem.md) | The decision rules that matched within this decision evaluation. |
**evaluatedInputs** | [**\Camunda\Orchestration\Api\Model\EvaluatedDecisionInputItem[]**](EvaluatedDecisionInputItem.md) | The decision inputs that were evaluated within this decision evaluation. |
**decisionDefinitionKey** | **string** | The unique key identifying the decision which was evaluate. |
**decisionEvaluationInstanceKey** | **string** | The unique key identifying this decision evaluation instance. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
