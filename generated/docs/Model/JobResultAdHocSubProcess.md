# JobResultAdHocSubProcess

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**activateElements** | [**\Camunda\Orchestration\Api\Model\JobResultActivateElement[]**](JobResultActivateElement.md) | Indicates which elements need to be activated in the ad-hoc subprocess. | [optional]
**isCompletionConditionFulfilled** | **bool** | Indicates whether the completion condition of the ad-hoc subprocess is fulfilled. | [optional] [default to false]
**isCancelRemainingInstances** | **bool** | Indicates whether the remaining instances of the ad-hoc subprocess should be canceled. | [optional] [default to false]
**type** | **string** | Used to distinguish between different types of job results. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
