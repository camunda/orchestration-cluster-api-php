# ClusterRuntimeBackupTenantInfo

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**physicalTenantId** | **string** | The id of the physical tenant. |
**state** | [**\Camunda\Orchestration\Api\Model\StateCode**](StateCode.md) | The state of the backup on this physical tenant, aggregated over its partitions. |
**failureReason** | **string** | Reason for failure if the state is &#39;FAILED&#39;. |
**details** | [**\Camunda\Orchestration\Api\Model\PartitionBackupInfo[]**](PartitionBackupInfo.md) | Detailed status of the backup per partition of this physical tenant. Contains every partition of the tenant when the backup id was looked up directly, including for a tenant that holds no such backup. Empty for a tenant that holds nothing for a listed id: a listing asks each tenant for the backups it has, so there is nothing to report per partition for one it does not. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
