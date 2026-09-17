# ProcessInstanceModificationActivateInstruction

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**elementId** | **string** | The id of the element to activate. |
**variableInstructions** | [**\Camunda\Orchestration\Api\Model\ModifyProcessInstanceVariableInstruction[]**](ModifyProcessInstanceVariableInstruction.md) | Instructions describing which variables to create or update. | [optional]
**ancestorElementInstanceKey** | **string** | The key of the ancestor scope the element instance should be created in. Set to -1 to create the new element instance within an existing element instance of the flow scope. If multiple instances of the target element&#39;s flow scope exist, choose one specifically with this property by providing its key. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
