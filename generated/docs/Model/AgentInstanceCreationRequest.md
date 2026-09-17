# AgentInstanceCreationRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**elementInstanceKey** | **string** | The key of the AI Agent Sub-process or AI Agent Task element instance. The engine uses this key to infer processInstanceKey, elementId, processDefinitionKey, and tenantId. |
**jobKey** | **string** | The key of the job activation during which this creation is being made. A creation must always be attributed to the active job that produced it. |
**jobLease** | **string** | Opaque lease token received from the job activation response. Disambiguates this activation from any other activation of the same job: if the job is later retried, history items submitted under a superseded lease are discarded rather than committed. |
**history** | [**\Camunda\Orchestration\Api\Model\AgentInstanceHistoryItem[]**](AgentInstanceHistoryItem.md) | A batch of history items to append to the agent instance&#39;s conversation history, in request order. Each created item is echoed back in the response&#39;s createdHistory, positionally correlated. Must include a CONFIGURATION item establishing model, provider, and systemPrompt (and, if needed, limits). Every item&#39;s role must be CONFIGURATION or USER, and no item may carry non-zero usage-token metrics (inputTokens, outputTokens, reasoningTokenCount, cacheCreationTokenCount, cacheReadTokenCount); durationMs is exempt and may be non-zero. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
