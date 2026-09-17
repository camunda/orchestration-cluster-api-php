# DecisionInstanceGetQueryResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**businessId** | **string** | The business ID of the owning process instance, inherited when the decision instance was evaluated. This is &#x60;null&#x60; for decision instances created before version 8.10, for standalone decision evaluations, and for decision instances whose owning process instance has no business ID. |
**decisionDefinitionId** | **string** | The ID of the DMN decision. |
**decisionDefinitionKey** | **string** | The key of the decision. |
**decisionDefinitionName** | **string** | The name of the DMN decision. |
**decisionDefinitionType** | [**\Camunda\Orchestration\Api\Model\DecisionDefinitionTypeEnum**](DecisionDefinitionTypeEnum.md) |  |
**decisionDefinitionVersion** | **int** | The version of the decision. |
**decisionEvaluationInstanceKey** | **string** | System-generated identifier for a decision evaluation instance. It is composed of the parent decision evaluation key and the 1-based index of the evaluated decision within that evaluation, joined by a hyphen (format: &#x60;&lt;decisionEvaluationKey&gt;-&lt;index&gt;&#x60;). |
**decisionEvaluationKey** | **string** | The key of the decision evaluation where this instance was created. |
**elementInstanceKey** | **string** | The key of the element instance this decision instance is linked to. |
**evaluationDate** | **\DateTime** | The evaluation date of the decision instance. |
**evaluationFailure** | **string** | The evaluation failure of the decision instance. |
**processDefinitionKey** | **string** | The key of the process definition. |
**processInstanceKey** | **string** | The key of the process instance. |
**result** | **string** | The result of the decision instance. |
**rootDecisionDefinitionKey** | **string** | The key of the root decision definition. |
**rootProcessInstanceKey** | **string** | The key of the root process instance. The root process instance is the top-level ancestor in the process instance hierarchy. This field is only present for data belonging to process instance hierarchies created in version 8.9 or later. |
**state** | [**\Camunda\Orchestration\Api\Model\DecisionInstanceStateEnum**](DecisionInstanceStateEnum.md) |  |
**tenantId** | **string** | The tenant ID of the decision instance. |
**evaluatedInputs** | [**\Camunda\Orchestration\Api\Model\EvaluatedDecisionInputItem[]**](EvaluatedDecisionInputItem.md) | The evaluated inputs of the decision instance. |
**matchedRules** | [**\Camunda\Orchestration\Api\Model\MatchedDecisionRuleItem[]**](MatchedDecisionRuleItem.md) | The matched rules of the decision instance. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
