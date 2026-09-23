# AgentInstanceHistoryItemResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**historyItemKey** | **string** | The unique key for this history item. Stable and sortable by creation order. |
**historyItemId** | **string** | The client-supplied identifier this item was created with. Empty for items that don&#39;t carry one. Not unique: a job can be re-activated under a superseded lease any number of times before it completes, so one historyItemId can have zero or more DISCARDED records and at most one COMMITTED record, since only historyItemKey is guaranteed unique. Filter by commitStatus rather than assuming one record per historyItemId. |
**agentInstanceKey** | **string** | The key of the agent instance this item belongs to. |
**elementInstanceKey** | **string** | The key of the AI Agent Task or ad-hoc sub-process element instance under which this item was produced. |
**jobKey** | **string** | The key of the job activation during which this item was produced. |
**jobLeaseToken** | **string** | The lease token of the activation that produced this item. |
**loopIteration** | **int** | The loop iteration this item belongs to. |
**role** | [**\Camunda\Orchestration\Api\Model\AgentInstanceHistoryRoleEnum**](AgentInstanceHistoryRoleEnum.md) | The role of this history item in the conversation. |
**content** | [**\Camunda\Orchestration\Api\Model\AgentInstanceMessageContent[]**](AgentInstanceMessageContent.md) | The content blocks of this history item. |
**toolCalls** | [**\Camunda\Orchestration\Api\Model\AgentInstanceToolCall[]**](AgentInstanceToolCall.md) | Tool calls for this item. Empty for USER items and ASSISTANT items with no tool dispatches. ASSISTANT items: dispatched tool calls. TOOL_RESULT items: single-entry array referencing the originating tool call. |
**metrics** | [**\Camunda\Orchestration\Api\Model\AgentInstanceHistoryItemMetrics**](AgentInstanceHistoryItemMetrics.md) | Per-call token and latency metrics. Null when metrics were not provided at creation time. |
**commitStatus** | [**\Camunda\Orchestration\Api\Model\AgentInstanceHistoryCommitStatusEnum**](AgentInstanceHistoryCommitStatusEnum.md) | The commit status of this history item. |
**producedAt** | **\DateTime** | The agent-side timestamp of when this message was produced. |
**tools** | [**\Camunda\Orchestration\Api\Model\AgentTool[]**](AgentTool.md) | The complete list of tools available to the agent as of this entry. CONFIGURATION items only; empty for other roles. |
**model** | **string** | The LLM model identifier as of this entry. CONFIGURATION items only; null for other roles. |
**provider** | **string** | The LLM provider as of this entry. CONFIGURATION items only; null for other roles. |
**limits** | [**\Camunda\Orchestration\Api\Model\AgentInstanceLimits**](AgentInstanceLimits.md) | The operational limits as of this entry. CONFIGURATION items only; -1 on any field means \&quot;no limit configured\&quot; for other roles. |
**systemPrompt** | [**\Camunda\Orchestration\Api\Model\AgentInstanceMessageContent[]**](AgentInstanceMessageContent.md) | The system prompt, as content blocks, as of this entry. CONFIGURATION items only; empty for other roles. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
