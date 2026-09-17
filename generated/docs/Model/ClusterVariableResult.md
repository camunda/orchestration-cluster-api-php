# ClusterVariableResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** | The name of the cluster variable. Unique within its scope (global or tenant-specific). |
**scope** | [**\Camunda\Orchestration\Api\Model\ClusterVariableScopeEnum**](ClusterVariableScopeEnum.md) |  |
**tenantId** | **string** | Only provided if the cluster variable scope is TENANT. Null for global scope variables. |
**metadata** | [**array<string,\Camunda\Orchestration\Api\Model\CreateClusterVariableRequestMetadataValue>**](CreateClusterVariableRequestMetadataValue.md) | A generic key-value metadata bag attached to the cluster variable. Values are strings or numbers. |
**kind** | [**\Camunda\Orchestration\Api\Model\ClusterVariableKindEnum**](ClusterVariableKindEnum.md) |  |
**value** | **string** | Full value of this cluster variable. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
