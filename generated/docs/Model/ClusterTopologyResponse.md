# ClusterTopologyResponse

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**brokers** | [**\Camunda\Orchestration\Api\Model\ClusterBrokerInfo[]**](ClusterBrokerInfo.md) | The brokers that are part of this cluster, across all physical tenants. |
**clusterId** | **string** | The cluster Id. |
**clusterSize** | **int** | The number of brokers in the cluster. |
**gatewayVersion** | **string** | The version of the Orchestration Cluster Gateway. |
**physicalTenants** | [**\Camunda\Orchestration\Api\Model\PhysicalTenantTopology[]**](PhysicalTenantTopology.md) | The topology of each physical tenant of this cluster. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
