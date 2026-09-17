# JobWaitStateDetails

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**waitStateType** | **string** | The wait state type discriminator. |
**jobKey** | **string** | The key of the job. |
**jobType** | **string** | The job type (worker subscription identifier). |
**jobKind** | [**\Camunda\Orchestration\Api\Model\JobKindEnum**](JobKindEnum.md) | The kind of job. |
**listenerEventType** | [**\Camunda\Orchestration\Api\Model\JobListenerEventTypeEnum**](JobListenerEventTypeEnum.md) | The listener event type of the job (only set for execution listener and task listener jobs). |
**retries** | **int** | The number of retries remaining for the job. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
