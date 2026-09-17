# JobFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**deadline** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | When the job can next be activated. | [optional]
**deniedReason** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The reason provided by the user task listener for denying the work. | [optional]
**elementId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The element ID associated with the job. | [optional]
**elementInstanceKey** | [**\Camunda\Orchestration\Api\Model\ElementInstanceKeyFilterProperty**](ElementInstanceKeyFilterProperty.md) | The element instance key associated with the job. | [optional]
**endTime** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | When the job ended. | [optional]
**errorCode** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The error code provided for the failed job. | [optional]
**errorMessage** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The error message that provides additional context for a failed job. | [optional]
**hasFailedWithRetriesLeft** | **bool** | Indicates whether the job has failed with retries left. | [optional]
**isDenied** | **bool** | Indicates whether the user task listener denies the work. | [optional]
**jobKey** | [**\Camunda\Orchestration\Api\Model\JobKeyFilterProperty**](JobKeyFilterProperty.md) | The key, a unique identifier for the job. | [optional]
**kind** | [**\Camunda\Orchestration\Api\Model\JobKindFilterProperty**](JobKindFilterProperty.md) | The kind of the job. | [optional]
**listenerEventType** | [**\Camunda\Orchestration\Api\Model\JobListenerEventTypeFilterProperty**](JobListenerEventTypeFilterProperty.md) | The listener event type of the job. | [optional]
**priority** | [**\Camunda\Orchestration\Api\Model\IntegerFilterProperty**](IntegerFilterProperty.md) | The priority of the job. Jobs created before 8.10 have no stored priority and are excluded from results when this filter is applied. | [optional]
**processDefinitionId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The process definition ID associated with the job. | [optional]
**processDefinitionKey** | [**\Camunda\Orchestration\Api\Model\ProcessDefinitionKeyFilterProperty**](ProcessDefinitionKeyFilterProperty.md) | The process definition key associated with the job. | [optional]
**processInstanceKey** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceKeyFilterProperty**](ProcessInstanceKeyFilterProperty.md) | The process instance key associated with the job. | [optional]
**retries** | [**\Camunda\Orchestration\Api\Model\IntegerFilterProperty**](IntegerFilterProperty.md) | The number of retries left. | [optional]
**state** | [**\Camunda\Orchestration\Api\Model\JobStateFilterProperty**](JobStateFilterProperty.md) | The state of the job. | [optional]
**tenantId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The tenant ID. | [optional]
**type** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The type of the job. | [optional]
**worker** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The name of the worker for this job. | [optional]
**creationTime** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | When the job was created. Field is present for jobs created after 8.9. | [optional]
**lastUpdateTime** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | When the job was last updated. Field is present for jobs created after 8.9. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
