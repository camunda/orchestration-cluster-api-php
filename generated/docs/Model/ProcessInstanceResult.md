# ProcessInstanceResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**processDefinitionId** | **string** | Id of a process definition, from the model. Only ids of process definitions that are deployed are useful. |
**processDefinitionName** | **string** | The process definition name. |
**processDefinitionVersion** | **int** | The process definition version. |
**processDefinitionVersionTag** | **string** | The process definition version tag. |
**startDate** | **\DateTime** | The start time of the process instance. |
**endDate** | **\DateTime** | The completion or termination time of the process instance. |
**state** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceStateEnum**](ProcessInstanceStateEnum.md) |  |
**suspendedDate** | **\DateTime** | The time this process instance most recently entered the &#x60;SUSPENDED&#x60; state. This is &#x60;null&#x60; if the process instance is not currently suspended. |
**hasIncident** | **bool** | Whether this process instance has a related incident or not. |
**tenantId** | **string** | The unique identifier of the tenant. |
**processInstanceKey** | **string** | The key of this process instance. |
**processDefinitionKey** | **string** | The process definition key. |
**parentProcessInstanceKey** | **string** | The parent process instance key. |
**parentElementInstanceKey** | **string** | The parent element instance key. |
**rootProcessInstanceKey** | **string** | The key of the root process instance. The root process instance is the top-level ancestor in the process instance hierarchy. This field is only present for data belonging to process instance hierarchies created in version 8.9 or later. |
**tags** | **string[]** | List of tags. Tags need to start with a letter; then alphanumerics, &#x60;_&#x60;, &#x60;-&#x60;, &#x60;:&#x60;, or &#x60;.&#x60;; length ≤ 100. |
**businessId** | **string** | The business id associated with this process instance. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
