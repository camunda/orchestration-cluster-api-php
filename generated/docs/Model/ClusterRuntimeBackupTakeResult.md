# ClusterRuntimeBackupTakeResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**physicalTenantId** | **string** | The id of the physical tenant. |
**backupId** | **int** | The id to monitor or delete this physical tenant&#39;s backup by: the id it is running under when &#x60;TRIGGERED&#x60; — the requested one, or the one the tenant generated when ids are generated — and the requested id to check when &#x60;UNKNOWN&#x60;. Null when the tenant is known to be running no backup, and also when an &#x60;UNKNOWN&#x60; tenant generates its own ids, because the id it may be running under was never reported; list that tenant&#39;s backups to find it. |
**outcome** | [**\Camunda\Orchestration\Api\Model\ClusterRuntimeBackupTakeOutcome**](ClusterRuntimeBackupTakeOutcome.md) | What this physical tenant did with the trigger. |
**reason** | **string** | Why this physical tenant reported no triggered backup. Null when it was triggered. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
