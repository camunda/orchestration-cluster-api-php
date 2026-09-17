# PartitionBackupState

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**checkpointId** | **int** | The id of the checkpoint this backup is based on. |
**checkpointType** | [**\Camunda\Orchestration\Api\Model\BackupType**](BackupType.md) | The type of the backup. |
**partitionId** | **int** | The id of the partition. Omitted when nested inside a backup range&#39;s &#x60;start&#x60;/&#x60;end&#x60;, where the partition is already identified by the enclosing range. |
**checkpointPosition** | **int** | The log position of the checkpoint this backup is based on. |
**firstLogPosition** | **int** | The first log position included in this backup. |
**checkpointTimestamp** | **\DateTime** | The timestamp at which the checkpoint was created. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
