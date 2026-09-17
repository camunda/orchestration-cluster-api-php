# AgentInstanceHistoryItemMetricsRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**inputTokens** | **int** | Input tokens consumed by this LLM call. Null when not provided. | [optional]
**outputTokens** | **int** | Output tokens produced by this LLM call. Null when not provided. | [optional]
**reasoningTokenCount** | **int** | Reasoning tokens consumed by this LLM call. Null when not provided. | [optional]
**cacheCreationTokenCount** | **int** | Cache-creation tokens consumed by this LLM call. Null when not provided. | [optional]
**cacheReadTokenCount** | **int** | Cache-read tokens consumed by this LLM call. Null when not provided. | [optional]
**durationMs** | **int** | Wall-clock duration of the LLM call in milliseconds. Null when not provided. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
