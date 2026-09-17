# AgentInstanceHistoryItem

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**historyItemId** | **string** | Caller-assigned identifier used to detect and dedupe retries of the same item. For example, when a retried job activation resubmits history items it already sent in an earlier attempt, those items are not rejected; they are flagged via isDuplicate in the response instead. Must be non-blank. |
**loopIteration** | **int** | The loop iteration this item belongs to. |
**role** | [**\Camunda\Orchestration\Api\Model\AgentInstanceHistoryRoleEnum**](AgentInstanceHistoryRoleEnum.md) | The role of this history item in the conversation. |
**content** | [**\Camunda\Orchestration\Api\Model\AgentInstanceMessageContent[]**](AgentInstanceMessageContent.md) | The content blocks of this history item. |
**toolCalls** | [**\Camunda\Orchestration\Api\Model\AgentInstanceToolCall[]**](AgentInstanceToolCall.md) | Tool calls associated with this history item. For ASSISTANT items: tool calls dispatched by this LLM response. For TOOL_RESULT items: single-entry array referencing the originating tool call. Omit for USER items. | [optional]
**metrics** | [**\Camunda\Orchestration\Api\Model\AgentInstanceHistoryItemMetricsRequest**](AgentInstanceHistoryItemMetricsRequest.md) | Per-call token and latency metrics. Present on ASSISTANT items only. | [optional]
**producedAt** | **\DateTime** | The agent-side timestamp of when this message was produced. |
**tools** | [**\Camunda\Orchestration\Api\Model\AgentTool[]**](AgentTool.md) | The complete list of tools available to the agent as of this entry. CONFIGURATION items only; omit for other roles. Omit to leave the tool list unchanged; send an empty array to clear it. | [optional]
**model** | **string** | The LLM model identifier as of this entry. CONFIGURATION items only; omit for other roles. | [optional]
**provider** | **string** | The LLM provider as of this entry. CONFIGURATION items only; omit for other roles. | [optional]
**limits** | [**\Camunda\Orchestration\Api\Model\AgentInstanceLimits**](AgentInstanceLimits.md) | The operational limits as of this entry. CONFIGURATION items only; omit for other roles. | [optional]
**systemPrompt** | [**\Camunda\Orchestration\Api\Model\AgentInstanceMessageContent[]**](AgentInstanceMessageContent.md) | The system prompt, as content blocks, as of this entry. CONFIGURATION items only; omit for other roles. Omit to leave the system prompt unchanged; when present, must be non-empty. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
