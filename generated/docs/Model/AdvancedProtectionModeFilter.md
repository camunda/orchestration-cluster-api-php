# AdvancedProtectionModeFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**eq** | [**\Camunda\Orchestration\Api\Model\ProtectionModeEnum**](ProtectionModeEnum.md) | Checks if the variable has the provided protection mode. | [optional]
**neq** | [**\Camunda\Orchestration\Api\Model\ProtectionModeEnum**](ProtectionModeEnum.md) | Checks if the variable does not have the provided protection mode. | [optional]
**exists** | **bool** | Checks if the variable has any protection mode. Use &#x60;false&#x60; to match the variables that are not protected. | [optional]
**in** | [**\Camunda\Orchestration\Api\Model\ProtectionModeEnum[]**](ProtectionModeEnum.md) | Checks if the variable has at least one of the provided protection modes. | [optional]
**notIn** | [**\Camunda\Orchestration\Api\Model\ProtectionModeEnum[]**](ProtectionModeEnum.md) | Checks if the variable has none of the provided protection modes. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
