# ClusterHistoryBackupInfo

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**backupId** | **int** | The id of the backup. |
**physicalTenants** | [**\Camunda\Orchestration\Api\Model\ClusterHistoryBackupTenantInfo[]**](ClusterHistoryBackupTenantInfo.md) | What each physical tenant reports for this backup id, ordered by physical tenant id. When looking a backup id up directly, every targeted tenant is listed, including the ones reporting &#x60;NOT_FOUND&#x60;. Within a listing, only the tenants that hold the id are listed. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
