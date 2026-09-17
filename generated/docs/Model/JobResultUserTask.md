# JobResultUserTask

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**denied** | **bool** | Indicates whether the worker denies the work, i.e. explicitly doesn&#39;t approve it. For example, a user task listener can deny the completion of a task by setting this flag to true. In this example, the completion of a task is represented by a job that the worker can complete as denied. As a result, the completion request is rejected and the task remains active. Defaults to false. | [optional]
**deniedReason** | **string** | The reason provided by the user task listener for denying the work. | [optional]
**corrections** | [**\Camunda\Orchestration\Api\Model\JobResultCorrections**](JobResultCorrections.md) |  | [optional]
**type** | **string** | Used to distinguish between different types of job results. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
