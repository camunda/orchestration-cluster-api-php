# ClusterCompletedRebalance

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**rebalanceId** | **int** | The ID of this rebalance. |
**partitions** | [**\Camunda\Orchestration\Api\Model\ClusterRebalanceOperationPartition[]**](ClusterRebalanceOperationPartition.md) | Every partition in the rebalance plan and its progress within this rebalance. |
**startedAt** | **\DateTime** | When this rebalance was created. |
**finishedAt** | **\DateTime** | When this rebalance finished. |
**result** | **string** | How the rebalance ended. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
