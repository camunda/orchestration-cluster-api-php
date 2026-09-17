# BackupInfo

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**backupId** | **int** | The id of the backup. | [readonly]
**state** | [**\Camunda\Orchestration\Api\Model\StateCode**](StateCode.md) | The aggregated state of the backup. | [readonly]
**failureReason** | **string** | Reason for failure if the state is &#39;FAILED&#39;. |
**details** | [**\Camunda\Orchestration\Api\Model\PartitionBackupInfo[]**](PartitionBackupInfo.md) | Detailed status of the backup per partition. Always contains every partition of the physical tenant. | [readonly]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
