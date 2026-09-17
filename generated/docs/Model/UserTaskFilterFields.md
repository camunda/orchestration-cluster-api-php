# UserTaskFilterFields

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**state** | [**\Camunda\Orchestration\Api\Model\UserTaskStateFilterProperty**](UserTaskStateFilterProperty.md) | The user task state. | [optional]
**assignee** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The assignee of the user task. | [optional]
**businessId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The business ID of the owning process instance the user task belongs to. This only works for user tasks created with 8.10 and onwards. Tasks from prior versions don&#39;t contain this data and cannot be found. | [optional]
**priority** | [**\Camunda\Orchestration\Api\Model\IntegerFilterProperty**](IntegerFilterProperty.md) | The priority of the user task. | [optional]
**elementId** | **string** | The element ID of the user task. | [optional]
**name** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The task name. This only works for data created with 8.8 and onwards. Instances from prior versions don&#39;t contain this data and cannot be found. | [optional]
**candidateGroup** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The candidate group for this user task. | [optional]
**candidateUser** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The candidate user for this user task. | [optional]
**tenantId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | Tenant ID of this user task. | [optional]
**processDefinitionId** | [**\Camunda\Orchestration\Api\Model\ProcessDefinitionIdFilterProperty**](ProcessDefinitionIdFilterProperty.md) | The ID of the process definition. | [optional]
**creationDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The user task creation date. | [optional]
**completionDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The user task completion date. | [optional]
**followUpDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The user task follow-up date. | [optional]
**dueDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The user task due date. | [optional]
**processInstanceVariables** | [**\Camunda\Orchestration\Api\Model\VariableValueFilterProperty[]**](VariableValueFilterProperty.md) | The variables of the process instance. | [optional]
**localVariables** | [**\Camunda\Orchestration\Api\Model\VariableValueFilterProperty[]**](VariableValueFilterProperty.md) | The local variables of the user task. | [optional]
**userTaskKey** | **string** | The key for this user task. | [optional]
**processDefinitionKey** | [**\Camunda\Orchestration\Api\Model\ProcessDefinitionKeyFilterProperty**](ProcessDefinitionKeyFilterProperty.md) | The key of the process definition. | [optional]
**processInstanceKey** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceKeyFilterProperty**](ProcessInstanceKeyFilterProperty.md) | The key of the process instance. | [optional]
**elementInstanceKey** | **string** | The key of the element instance. | [optional]
**tags** | **string[]** | List of tags. Tags need to start with a letter; then alphanumerics, &#x60;_&#x60;, &#x60;-&#x60;, &#x60;:&#x60;, or &#x60;.&#x60;; length ≤ 100. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
