# DecisionInstanceFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**decisionEvaluationInstanceKey** | [**\Camunda\Orchestration\Api\Model\DecisionEvaluationInstanceKeyFilterProperty**](DecisionEvaluationInstanceKeyFilterProperty.md) | The key of the decision evaluation instance. | [optional]
**state** | [**\Camunda\Orchestration\Api\Model\DecisionInstanceStateFilterProperty**](DecisionInstanceStateFilterProperty.md) | The state of the decision instance. | [optional]
**evaluationFailure** | **string** | The evaluation failure of the decision instance. | [optional]
**evaluationDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The evaluation date of the decision instance. | [optional]
**decisionDefinitionId** | **string** | The ID of the DMN decision. | [optional]
**decisionDefinitionName** | **string** | The name of the DMN decision. | [optional]
**decisionDefinitionVersion** | **int** | The version of the decision. | [optional]
**decisionDefinitionType** | [**\Camunda\Orchestration\Api\Model\DecisionDefinitionTypeEnum**](DecisionDefinitionTypeEnum.md) |  | [optional]
**tenantId** | **string** | The tenant ID of the decision instance. | [optional]
**decisionEvaluationKey** | **string** | The key of the parent decision evaluation. Note that this is not the identifier of an individual decision instance; the &#x60;decisionEvaluationInstanceKey&#x60; is the identifier for a decision instance. | [optional]
**processDefinitionKey** | **string** | The key of the process definition. | [optional]
**processInstanceKey** | **string** | The key of the process instance. | [optional]
**businessId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The business ID of the owning process instance the decision instance belongs to. This only works for decision instances created with 8.10 and onwards. Decision instances from prior versions and standalone evaluations don&#39;t contain this data and cannot be found. | [optional]
**decisionDefinitionKey** | [**\Camunda\Orchestration\Api\Model\DecisionDefinitionKeyFilterProperty**](DecisionDefinitionKeyFilterProperty.md) | The key of the decision. | [optional]
**elementInstanceKey** | [**\Camunda\Orchestration\Api\Model\ElementInstanceKeyFilterProperty**](ElementInstanceKeyFilterProperty.md) | The key of the element instance this decision instance is linked to. | [optional]
**rootDecisionDefinitionKey** | [**\Camunda\Orchestration\Api\Model\DecisionDefinitionKeyFilterProperty**](DecisionDefinitionKeyFilterProperty.md) | The key of the root decision definition. | [optional]
**decisionRequirementsKey** | [**\Camunda\Orchestration\Api\Model\DecisionRequirementsKeyFilterProperty**](DecisionRequirementsKeyFilterProperty.md) | The key of the decision requirements definition. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
