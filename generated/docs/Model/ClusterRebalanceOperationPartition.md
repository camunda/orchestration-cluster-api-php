# ClusterRebalanceOperationPartition

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**partitionId** | **int** | The unique ID of this partition, within its physical tenant. |
**physicalTenantId** | **string** | The partition group this partition belongs to. |
**currentLeader** | **string** | The leader last observed by this rebalance, or absent if there was no leader. |
**desiredLeader** | **string** | The leader selected when this rebalance was planned. |
**progress** | **string** | Where this rebalance has reached for the partition. |
**result** | **string** | The terminal outcome, present only when progress is COMPLETED. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
