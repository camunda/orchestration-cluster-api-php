# WaitStateDetails

This model is a `oneOf` wrapper: a value is exactly one of the member types listed below.
It is never instantiated directly — use one of the concrete types.

## oneOf

- [**\Camunda\Orchestration\Api\Model\JobWaitStateDetails**](JobWaitStateDetails.md)
- [**\Camunda\Orchestration\Api\Model\MessageWaitStateDetails**](MessageWaitStateDetails.md)
- [**\Camunda\Orchestration\Api\Model\UserTaskWaitStateDetails**](UserTaskWaitStateDetails.md)
- [**\Camunda\Orchestration\Api\Model\TimerWaitStateDetails**](TimerWaitStateDetails.md)
- [**\Camunda\Orchestration\Api\Model\SignalWaitStateDetails**](SignalWaitStateDetails.md)
- [**\Camunda\Orchestration\Api\Model\ConditionWaitStateDetails**](ConditionWaitStateDetails.md)

The concrete type is selected by the `waitStateType` discriminator property.

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
