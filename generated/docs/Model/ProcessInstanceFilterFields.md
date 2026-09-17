# ProcessInstanceFilterFields

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**startDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The start date. | [optional]
**endDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The end date. | [optional]
**state** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceStateFilterProperty**](ProcessInstanceStateFilterProperty.md) | The process instance state. | [optional]
**hasIncident** | **bool** | Whether this process instance has a related incident or not. | [optional]
**suspendedDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The time this process instance most recently entered the SUSPENDED state. This is cleared (null) again once the process instance is resumed. | [optional]
**tenantId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The tenant id. | [optional]
**variables** | [**\Camunda\Orchestration\Api\Model\VariableValueFilterProperty[]**](VariableValueFilterProperty.md) | The process instance variables. | [optional]
**processInstanceKey** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceKeyFilterProperty**](ProcessInstanceKeyFilterProperty.md) | The key of this process instance. | [optional]
**parentProcessInstanceKey** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceKeyFilterProperty**](ProcessInstanceKeyFilterProperty.md) | The parent process instance key. | [optional]
**parentElementInstanceKey** | [**\Camunda\Orchestration\Api\Model\ElementInstanceKeyFilterProperty**](ElementInstanceKeyFilterProperty.md) | The parent element instance key. | [optional]
**batchOperationId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The batch operation id. **Deprecated**: Use &#x60;batchOperationKey&#x60; instead. This field will be removed in a future release. If both &#x60;batchOperationId&#x60; and &#x60;batchOperationKey&#x60; are provided, the request will be rejected with a 400 error. | [optional]
**batchOperationKey** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The batch operation key. | [optional]
**errorMessage** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The error message related to the process. | [optional]
**hasRetriesLeft** | **bool** | Whether the process has failed jobs with retries left. | [optional]
**elementInstanceState** | [**\Camunda\Orchestration\Api\Model\ElementInstanceStateFilterProperty**](ElementInstanceStateFilterProperty.md) | The state of the element instances associated with the process instance. | [optional]
**elementId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The element id associated with the process instance. | [optional]
**hasElementInstanceIncident** | **bool** | Whether the element instance has an incident or not. | [optional]
**incidentErrorHashCode** | [**\Camunda\Orchestration\Api\Model\IntegerFilterProperty**](IntegerFilterProperty.md) | The incident error hash code, associated with this process. | [optional]
**tags** | **string[]** | List of tags. Tags need to start with a letter; then alphanumerics, &#x60;_&#x60;, &#x60;-&#x60;, &#x60;:&#x60;, or &#x60;.&#x60;; length ≤ 100. | [optional]
**businessId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The business id associated with the process instance. | [optional]
**processDefinitionId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The process definition id. | [optional]
**processDefinitionName** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The process definition name. | [optional]
**processDefinitionVersion** | [**\Camunda\Orchestration\Api\Model\IntegerFilterProperty**](IntegerFilterProperty.md) | The process definition version. | [optional]
**processDefinitionVersionTag** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The process definition version tag. | [optional]
**processDefinitionKey** | [**\Camunda\Orchestration\Api\Model\ProcessDefinitionKeyFilterProperty**](ProcessDefinitionKeyFilterProperty.md) | The process definition key. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
