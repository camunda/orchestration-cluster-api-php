# AgentInstanceToolCall

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**toolCallId** | **string** | The LLM-assigned tool call ID. Correlates ASSISTANT items to their matching TOOL_RESULT items. |
**toolName** | **string** | The LLM-visible tool name. |
**elementId** | **string** | The BPMN element ID handling this tool. |
**arguments** | **array<string,mixed>** | The tool call arguments as provided by the LLM. May be null or populated on any item, including TOOL_RESULT. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
