# RestorePartitionStatus

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**partitionId** | **int** | The ID of the partition. |
**state** | **string** | The restore state of the partition. |
**backupIds** | **int[]** | The IDs of the backups this partition is restored from. |
**completedAt** | **\DateTime** | The time the partition was restored, as an ISO 8601 timestamp; null unless the partition state is &#x60;RESTORED&#x60;. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
