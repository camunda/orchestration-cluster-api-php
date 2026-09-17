# CreateClusterVariableRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** | The name of the cluster variable. Must be unique within its scope (global or tenant-specific). |
**value** | **object** | The value of the cluster variable. Can be any JSON object or primitive value. Will be serialized as a JSON string in responses. |
**metadata** | [**array<string,\Camunda\Orchestration\Api\Model\CreateClusterVariableRequestMetadataValue>**](CreateClusterVariableRequestMetadataValue.md) | A generic key-value metadata bag attached to the cluster variable. Values must be strings or numbers. Limited to 100 entries and a configurable maximum serialized size (default: 100 entries at max key length of a cluster variable name (256 chars) plus the maximum value length, 8192 characters). | [optional]
**kind** | [**\Camunda\Orchestration\Api\Model\ClusterVariableKindEnum**](ClusterVariableKindEnum.md) | The kind of the cluster variable. Defaults to JSON if not specified. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
