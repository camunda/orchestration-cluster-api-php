# AdvancedProcessInstanceStateFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**eq** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceStateEnum**](ProcessInstanceStateEnum.md) | Checks for equality with the provided value. | [optional]
**neq** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceStateEnum**](ProcessInstanceStateEnum.md) | Checks for inequality with the provided value. | [optional]
**exists** | **bool** | Checks if the current property exists. | [optional]
**in** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceStateEnum[]**](ProcessInstanceStateEnum.md) | Checks if the property matches any of the provided values. | [optional]
**like** | **string** | Checks if the property matches the provided like value.  Supported wildcard characters are:  * &#x60;*&#x60;: matches zero, one, or multiple characters. * &#x60;?&#x60;: matches one, single character.  Wildcard characters can be escaped with backslash, for instance: &#x60;\\*&#x60;. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
