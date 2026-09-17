# AdvancedMetadataValueFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**eq** | [**\Camunda\Orchestration\Api\Model\AdvancedMetadataValueFilterEq**](AdvancedMetadataValueFilterEq.md) |  | [optional]
**neq** | [**\Camunda\Orchestration\Api\Model\AdvancedMetadataValueFilterNeq**](AdvancedMetadataValueFilterNeq.md) |  | [optional]
**exists** | **bool** | Checks if the metadata key exists. | [optional]
**gt** | **float** | Greater than comparison with the provided value. | [optional]
**gte** | **float** | Greater than or equal comparison with the provided value. | [optional]
**lt** | **float** | Lower than comparison with the provided value. | [optional]
**lte** | **float** | Lower than or equal comparison with the provided value. | [optional]
**in** | [**\Camunda\Orchestration\Api\Model\CreateClusterVariableRequestMetadataValue[]**](CreateClusterVariableRequestMetadataValue.md) | Checks if the property matches any of the provided values. | [optional]
**like** | **string** | Checks if the property matches the provided like value.  Supported wildcard characters are:  * &#x60;*&#x60;: matches zero, one, or multiple characters. * &#x60;?&#x60;: matches one, single character.  Wildcard characters can be escaped with backslash, for instance: &#x60;\\*&#x60;. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
