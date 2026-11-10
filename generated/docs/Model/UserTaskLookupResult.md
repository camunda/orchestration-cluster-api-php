# UserTaskLookupResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**userTaskKey** | **string** | The key of the user task. |
**state** | [**\Camunda\Orchestration\Api\Model\UserTaskLookupStateEnum**](UserTaskLookupStateEnum.md) |  |
**elementId** | **string** | The element ID of the user task. |
**elementInstanceKey** | **string** | The key of the element instance. |
**processInstanceKey** | **string** | The key of the process instance. |
**rootProcessInstanceKey** | **string** | The key of the root process instance. The root process instance is the top-level ancestor in the process instance hierarchy. This field is only present for data belonging to process instance hierarchies created in version 8.9 or later. |
**processDefinitionId** | **string** | The ID of the process definition. |
**processDefinitionKey** | **string** | The key of the process definition. |
**processDefinitionVersion** | **int** | The version of the process definition. |
**tenantId** | **string** | The unique identifier of the tenant. |
**assignee** | **string** | The assignee of the user task. While the task is in the &#x60;CREATING&#x60; state, the assignee from the process model is not set yet. |
**candidateGroups** | **string[]** | The candidate groups for this user task. |
**candidateUsers** | **string[]** | The candidate users for this user task. |
**dueDate** | **\DateTime** | The due date of the user task. |
**followUpDate** | **\DateTime** | The follow-up date of the user task. |
**formKey** | **string** | The key of the form. |
**externalFormReference** | **string** | The external form reference. |
**customHeaders** | **array<string,string>** | Custom headers for the user task. |
**priority** | **int** | The priority of the user task. The higher the value the higher the priority. |
**tags** | **string[]** | List of tags. Tags need to start with a letter; then alphanumerics, &#x60;_&#x60;, &#x60;-&#x60;, &#x60;:&#x60;, or &#x60;.&#x60;; length ≤ 100. |
**businessId** | **string** | The business ID of the owning process instance, inherited when the user task was created. This is &#x60;null&#x60; for user tasks whose owning process instance has no business ID. |
**creationDate** | **\DateTime** | The creation date of the user task. |
**variables** | **array<string,mixed>** | The variables visible to the user task, as a JSON object of name and value. If a name exists in several scopes, the value of the innermost scope is returned. Values are never truncated. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
