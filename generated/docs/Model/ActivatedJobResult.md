# ActivatedJobResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | **string** | The type of the job (should match what was requested). |
**processDefinitionId** | **string** | The bpmn process ID of the job&#39;s process definition. |
**processDefinitionVersion** | **int** | The version of the job&#39;s process definition. |
**elementId** | **string** | The associated task element ID. |
**customHeaders** | **array<string,mixed>** | A set of custom headers defined during modelling; returned as a serialized JSON document. |
**worker** | **string** | The name of the worker which activated this job. |
**retries** | **int** | The amount of retries left to this job (should always be positive). |
**deadline** | **int** | When the job can be activated again, sent as a UNIX epoch timestamp. |
**variables** | **array<string,mixed>** | All variables visible to the task scope, computed at activation time. |
**tenantId** | **string** | The ID of the tenant that owns the job. |
**physicalTenantId** | **string** | The ID of the physical tenant that the job-activation request was routed to; the default physical tenant when the request did not specify one. |
**jobKey** | **string** | The key, a unique identifier for the job. |
**processInstanceKey** | **string** | The job&#39;s process instance key. |
**processDefinitionKey** | **string** | The key of the job&#39;s process definition. |
**elementInstanceKey** | **string** | The element instance key of the task. |
**kind** | [**\Camunda\Orchestration\Api\Model\JobKindEnum**](JobKindEnum.md) |  |
**listenerEventType** | [**\Camunda\Orchestration\Api\Model\JobListenerEventTypeEnum**](JobListenerEventTypeEnum.md) |  |
**userTask** | [**\Camunda\Orchestration\Api\Model\UserTaskProperties**](UserTaskProperties.md) | User task properties, if the job is a user task. This is &#x60;null&#x60; if the job is not a user task. |
**tags** | **string[]** | List of tags. Tags need to start with a letter; then alphanumerics, &#x60;_&#x60;, &#x60;-&#x60;, &#x60;:&#x60;, or &#x60;.&#x60;; length ≤ 100. |
**rootProcessInstanceKey** | **string** | The key of the root process instance. The root process instance is the top-level ancestor in the process instance hierarchy. This field is only present for data belonging to process instance hierarchies created in version 8.9 or later. |
**businessId** | **string** | The business ID of the owning process instance, inherited when the job was created. This is &#x60;null&#x60; for jobs created before version 8.10 and for jobs whose owning process instance has no business ID. |
**priority** | **int** | The priority of the job. Higher values indicate higher priority. Jobs created before 8.10 have no stored priority; the API returns 0 for such jobs. |
**jobLeaseToken** | **string** | The lease token identifying this activation. This is &#x60;null&#x60; when the job was activated without a lease. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
