# UserTaskResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** | The name for this user task. |
**state** | [**\Camunda\Orchestration\Api\Model\UserTaskStateEnum**](UserTaskStateEnum.md) |  |
**assignee** | **string** | The assignee of the user task. |
**elementId** | **string** | The element ID of the user task. |
**candidateGroups** | **string[]** | The candidate groups for this user task. |
**candidateUsers** | **string[]** | The candidate users for this user task. |
**processDefinitionId** | **string** | The ID of the process definition. |
**creationDate** | **\DateTime** | The creation date of a user task. |
**completionDate** | **\DateTime** | The completion date of a user task. |
**followUpDate** | **\DateTime** | The follow date of a user task. |
**dueDate** | **\DateTime** | The due date of a user task. |
**tenantId** | **string** | The unique identifier of the tenant. |
**externalFormReference** | **string** | The external form reference. |
**processDefinitionVersion** | **int** | The version of the process definition. |
**customHeaders** | **array<string,string>** | Custom headers for the user task. |
**priority** | **int** | The priority of a user task. The higher the value the higher the priority. | [default to 50]
**userTaskKey** | **string** | The key of the user task. |
**elementInstanceKey** | **string** | The key of the element instance. |
**processName** | **string** | The name of the process definition. This is &#x60;null&#x60; if the process has no name defined. |
**processDefinitionKey** | **string** | The key of the process definition. |
**processInstanceKey** | **string** | The key of the process instance. |
**rootProcessInstanceKey** | **string** | The key of the root process instance. The root process instance is the top-level ancestor in the process instance hierarchy. This field is only present for data belonging to process instance hierarchies created in version 8.9 or later. |
**businessId** | **string** | The business ID of the owning process instance, inherited when the user task was created. This is &#x60;null&#x60; for user tasks created before version 8.10, and for user tasks whose owning process instance has no business ID. |
**formKey** | **string** | The key of the form. |
**tags** | **string[]** | List of tags. Tags need to start with a letter; then alphanumerics, &#x60;_&#x60;, &#x60;-&#x60;, &#x60;:&#x60;, or &#x60;.&#x60;; length ≤ 100. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
