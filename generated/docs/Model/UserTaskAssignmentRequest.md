# UserTaskAssignmentRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**assignee** | **string** | The assignee for the user task. The assignee must not be empty or &#x60;null&#x60;. | [optional]
**allowOverride** | **bool** | By default, the task is reassigned if it was already assigned. Set this to &#x60;false&#x60; to return an error in such cases. The task must then first be unassigned to be assigned again. Use this when you have users picking from group task queues to prevent race conditions. | [optional]
**action** | **string** | A custom action value that will be accessible from user task events resulting from this endpoint invocation. If not provided, it will default to \&quot;assign\&quot;. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
