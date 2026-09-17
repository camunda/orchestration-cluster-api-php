# ClusterHistoryBackupTenantInfo

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**physicalTenantId** | **string** | The id of the physical tenant. |
**state** | [**\Camunda\Orchestration\Api\Model\ClusterHistoryBackupTenantState**](ClusterHistoryBackupTenantState.md) | The state of the backup on this physical tenant. |
**failureReason** | **string** | Reason for failure if the state is &#39;FAILED&#39;. |
**details** | [**\Camunda\Orchestration\Api\Model\HistoryBackupSnapshotInfo[]**](HistoryBackupSnapshotInfo.md) | Detailed status of the backup per snapshot on this physical tenant. Empty when the tenant does not hold the backup. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
