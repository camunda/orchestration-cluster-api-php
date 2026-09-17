# PartitionBackupInfo

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**partitionId** | **int** | The id of the partition. | [readonly]
**state** | [**\Camunda\Orchestration\Api\Model\StateCode**](StateCode.md) | The state of the backup on this partition. | [readonly]
**failureReason** | **string** | Failure reason if the state is &#39;FAILED&#39;. |
**createdAt** | **\DateTime** | The timestamp at which the backup was started on this partition. | [readonly]
**lastUpdatedAt** | **\DateTime** | The timestamp at which the backup was last updated on this partition, e.g. changed state from &#39;IN_PROGRESS&#39; to &#39;COMPLETED&#39;. | [readonly]
**snapshotId** | **string** | The id of the snapshot which is included in this backup. | [readonly]
**firstLogPosition** | **int** | The first log position included in this backup. | [readonly]
**checkpointPosition** | **int** | The position of the checkpoint for this backup. | [readonly]
**brokerId** | **int** | The id of the broker from which the backup was taken for this partition. | [readonly]
**brokerVersion** | **string** | The version of the broker from which the backup was taken for this partition. | [readonly]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
