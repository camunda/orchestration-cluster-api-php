# JobSearchResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**customHeaders** | **array<string,string>** | A set of custom headers defined during modelling. |
**deadline** | **\DateTime** | If the job has been activated, when it will next be available to be activated. |
**deniedReason** | **string** | The reason provided by the user task listener for denying the work. |
**elementId** | **string** | The element ID associated with the job. May be missing on job failure. |
**elementInstanceKey** | **string** | The element instance key associated with the job. |
**endTime** | **\DateTime** | End date of the job. This is &#x60;null&#x60; if the job is not in an end state yet. |
**errorCode** | **string** | The error code provided for a failed job. |
**errorMessage** | **string** | The error message that provides additional context for a failed job. |
**hasFailedWithRetriesLeft** | **bool** | Indicates whether the job has failed with retries left. |
**isDenied** | **bool** | Indicates whether the user task listener denies the work. |
**jobKey** | **string** | The key, a unique identifier for the job. |
**kind** | [**\Camunda\Orchestration\Api\Model\JobKindEnum**](JobKindEnum.md) |  |
**listenerEventType** | [**\Camunda\Orchestration\Api\Model\JobListenerEventTypeEnum**](JobListenerEventTypeEnum.md) |  |
**processDefinitionId** | **string** | The process definition ID associated with the job. |
**processDefinitionKey** | **string** | The process definition key associated with the job. |
**processInstanceKey** | **string** | The process instance key associated with the job. |
**rootProcessInstanceKey** | **string** | The key of the root process instance. The root process instance is the top-level ancestor in the process instance hierarchy. This field is only present for data belonging to process instance hierarchies created in version 8.9 or later. |
**businessId** | **string** | The business ID of the owning process instance, inherited when the job was created. This is &#x60;null&#x60; for jobs created before version 8.10 and for jobs whose owning process instance has no business ID. |
**retries** | **int** | The amount of retries left to this job. |
**state** | [**\Camunda\Orchestration\Api\Model\JobStateEnum**](JobStateEnum.md) |  |
**tenantId** | **string** | The unique identifier of the tenant. |
**type** | **string** | The type of the job. |
**worker** | **string** | The name of the worker of this job. |
**creationTime** | **\DateTime** | When the job was created. Field is present for jobs created after 8.9. |
**lastUpdateTime** | **\DateTime** | When the job was last updated. Field is present for jobs created after 8.9. |
**priority** | **int** | The priority of the job. Higher values indicate higher priority. Jobs created before 8.10 have no stored priority; they appear last when sorting by this field and are excluded when filtering by this field. The API returns 0 for such jobs. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
