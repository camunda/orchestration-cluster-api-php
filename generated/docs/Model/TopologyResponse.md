# TopologyResponse

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**brokers** | [**\Camunda\Orchestration\Api\Model\BrokerInfo[]**](BrokerInfo.md) | A list of brokers that are part of this cluster. |
**clusterId** | **string** | The cluster Id. |
**clusterSize** | **int** | The number of brokers in the cluster. |
**partitionsCount** | **int** | The number of partitions are spread across the cluster. |
**replicationFactor** | **int** | The configured replication factor for this cluster. |
**gatewayVersion** | **string** | The version of the Zeebe Gateway. |
**lastCompletedChangeId** | **string** | ID of the last completed change |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
