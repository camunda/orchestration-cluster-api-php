# PhysicalTenantTopology

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**physicalTenantId** | **string** | The id of the physical tenant. |
**partitionsCount** | **int** | The number of partitions spread across this physical tenant. |
**replicationFactor** | **int** | The configured replication factor for this physical tenant. |
**lastCompletedChangeId** | **string** | ID of the last completed change of this physical tenant. |
**brokers** | [**\Camunda\Orchestration\Api\Model\PhysicalTenantBrokerTopology[]**](PhysicalTenantBrokerTopology.md) | The brokers holding partitions of this physical tenant. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
