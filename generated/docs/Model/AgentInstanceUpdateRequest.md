# AgentInstanceUpdateRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**elementInstanceKey** | **string** | The key of the currently-active element instance for this agent instance. Used for ownership/equality validation against the stored agent instance and, when the supplied key differs from the previous association (re-entry of an ad-hoc sub-process or AI Agent task), appended to elementInstanceKeys with the reverse link updated on the supplied element instance. Only one element instance may hold this write claim at a time: any update from a different element instance is rejected while the current writer&#39;s job is still active. |
**status** | [**\Camunda\Orchestration\Api\Model\AgentInstanceUpdateStatusEnum**](AgentInstanceUpdateStatusEnum.md) | The new status of the agent instance. | [optional]
**jobKey** | **string** | The key of the job activation during which this update is being made. An update must always be attributed to the active job that produced it. |
**jobLease** | **string** | Opaque lease token received from the job activation response. Disambiguates this activation from any other activation of the same job: if the job is later retried, history items submitted under a superseded lease are discarded rather than committed. |
**history** | [**\Camunda\Orchestration\Api\Model\AgentInstanceHistoryItem[]**](AgentInstanceHistoryItem.md) | A batch of history items to append to the agent instance&#39;s conversation history, in request order. Each created item is echoed back in the response&#39;s createdHistory, positionally correlated. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
