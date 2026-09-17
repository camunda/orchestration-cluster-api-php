# CreateProcessInstanceResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**processDefinitionId** | **string** | The BPMN process id of the process definition which was used to create the process. instance |
**processDefinitionVersion** | **int** | The version of the process definition which was used to create the process instance. |
**tenantId** | **string** | The tenant id of the created process instance. |
**variables** | **array<string,mixed>** | All the variables visible in the root scope. |
**processDefinitionKey** | **string** | The key of the process definition which was used to create the process instance. |
**processInstanceKey** | **string** | The unique identifier of the created process instance; to be used wherever a request needs a process instance key (e.g. CancelProcessInstanceRequest). |
**tags** | **string[]** | List of tags. Tags need to start with a letter; then alphanumerics, &#x60;_&#x60;, &#x60;-&#x60;, &#x60;:&#x60;, or &#x60;.&#x60;; length ≤ 100. |
**businessId** | **string** | Business id as provided on creation. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
