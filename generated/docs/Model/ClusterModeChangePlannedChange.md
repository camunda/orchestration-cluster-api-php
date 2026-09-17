# ClusterModeChangePlannedChange

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**physicalTenantId** | **string** | The physical tenant the operations apply to; null for operations that are not scoped to a single physical tenant, such as broker lifecycle operations. |
**operations** | [**\Camunda\Orchestration\Api\Model\ClusterModeChangeOperation[]**](ClusterModeChangeOperation.md) | The ordered list of operations that will be applied to the physical tenant. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
