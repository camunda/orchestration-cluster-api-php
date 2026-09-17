# HistoryBackupInfo

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**backupId** | **int** | The id of the backup. | [readonly]
**state** | [**\Camunda\Orchestration\Api\Model\HistoryBackupStateCode**](HistoryBackupStateCode.md) | The aggregated state of the backup. | [readonly]
**failureReason** | **string** | Reason for failure if the state is &#39;FAILED&#39;. |
**details** | [**\Camunda\Orchestration\Api\Model\HistoryBackupSnapshotInfo[]**](HistoryBackupSnapshotInfo.md) | Detailed status of the backup per snapshot. Always lists every snapshot found for the backup; when the backup was read without snapshot detail, each entry carries only its name. | [readonly]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
