# RestoreBrokerStatus

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**brokerId** | **string** | The ID of the broker, including its zone if it belongs to one. |
**partitionsRestored** | **int** | The number of the broker&#39;s partitions that have been restored so far. |
**partitionsToRestore** | **int** | The total number of the broker&#39;s partitions to restore. |
**partitions** | [**\Camunda\Orchestration\Api\Model\RestorePartitionStatus[]**](RestorePartitionStatus.md) | The per-partition restore status for this broker. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
