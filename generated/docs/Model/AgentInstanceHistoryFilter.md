# AgentInstanceHistoryFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**historyItemKey** | [**\Camunda\Orchestration\Api\Model\AgentHistoryItemKeyFilterProperty**](AgentHistoryItemKeyFilterProperty.md) | The unique key of the history item. | [optional]
**role** | [**\Camunda\Orchestration\Api\Model\AgentInstanceHistoryRoleFilterProperty**](AgentInstanceHistoryRoleFilterProperty.md) | The role of the history item. | [optional]
**elementInstanceKey** | [**\Camunda\Orchestration\Api\Model\ElementInstanceKeyFilterProperty**](ElementInstanceKeyFilterProperty.md) | The key of the element instance under which the history item was produced. | [optional]
**jobKey** | [**\Camunda\Orchestration\Api\Model\JobKeyFilterProperty**](JobKeyFilterProperty.md) | The key of the job activation that produced the history item. | [optional]
**loopIteration** | [**\Camunda\Orchestration\Api\Model\IntegerFilterProperty**](IntegerFilterProperty.md) | Filter by loop iteration number. | [optional]
**commitStatus** | [**\Camunda\Orchestration\Api\Model\AgentInstanceHistoryCommitStatusFilterProperty**](AgentInstanceHistoryCommitStatusFilterProperty.md) | The commit status of the history item. Defaults to COMMITTED only. Include PENDING or DISCARDED explicitly to debug in-flight or failed activations. | [optional]
**producedAt** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The timestamp when the history item was produced. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
