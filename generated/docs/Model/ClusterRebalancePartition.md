# ClusterRebalancePartition

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**partitionId** | **int** | The unique ID of this partition, within its physical tenant. |
**physicalTenantId** | **string** | The partition group this partition belongs to. Partition IDs are unique only within a group, so this is needed to identify the partition. |
**currentLeader** | **string** | The broker ID currently leading this partition, or absent if it has no leader. |
**desiredLeader** | **string** | The broker ID the current configuration wants to lead this partition. |
**state** | **string** | Whether this partition is being actively transferred, unbalanced, or balanced. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
