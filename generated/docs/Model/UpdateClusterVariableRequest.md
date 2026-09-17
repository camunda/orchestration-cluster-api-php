# UpdateClusterVariableRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**value** | **object** | The new value of the cluster variable. Can be any JSON object or primitive value. Will be serialized as a JSON string in responses. |
**metadata** | [**array<string,\Camunda\Orchestration\Api\Model\CreateClusterVariableRequestMetadataValue>**](CreateClusterVariableRequestMetadataValue.md) | A generic key-value metadata bag attached to the cluster variable. Values must be strings or numbers. Limited to 100 entries and a configurable maximum serialized size (default: 100 entries at max key length of a cluster variable name (256 chars) plus the maximum value length, 8192 characters). | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
