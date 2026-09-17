# ClusterBalanceResponse

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**state** | **string** | The cluster&#39;s aggregate balance state as of the time of the request. |
**partitions** | [**\Camunda\Orchestration\Api\Model\ClusterRebalancePartition[]**](ClusterRebalancePartition.md) | The balance state of each partition as of the time of the request. |
**runningRebalance** | [**\Camunda\Orchestration\Api\Model\ClusterRunningRebalance**](ClusterRunningRebalance.md) | Normally the rebalance currently running, or absent if no rebalance is running. For a dry-run response, this is instead the unexecuted plan of that dry run. |
**lastCompletedRebalance** | [**\Camunda\Orchestration\Api\Model\ClusterCompletedRebalance**](ClusterCompletedRebalance.md) | The last completed non-dry-run rebalance this coordinator finished. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
