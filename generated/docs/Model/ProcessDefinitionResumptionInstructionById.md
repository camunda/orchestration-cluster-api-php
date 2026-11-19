# ProcessDefinitionResumptionInstructionById

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**processDefinitionId** | **string** | The BPMN process ID of the process definition. |
**processDefinitionVersion** | **int** | The version of the process definition. Must be 1 or greater; &#x60;-1&#x60; is not accepted. If omitted, all versions are affected. | [optional]
**tenantId** | **string** | The tenant of the process definition. Required when multi-tenancy is enabled; if multi-tenancy is disabled, it can be omitted and the default tenant is used. | [optional]
**includeProcessInstances** | **bool** | Whether to also resume the process instances of the affected process definition versions. The instances are changed by a batch operation. | [optional] [default to false]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
