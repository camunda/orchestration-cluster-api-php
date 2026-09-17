# AgentInstanceCreatedHistoryItem

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**historyItemId** | **string** | The historyItemId of the corresponding item in the request, echoed back so callers can correlate response entries with request items by id. |
**historyItemKey** | **string** | The system-generated key for the history item. When isDuplicate is true, this is the key of the original entry, not a new one. |
**isDuplicate** | **bool** | True if this item had already been recorded and no new AGENT_HISTORY event was created for it; false if a new event was created. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
