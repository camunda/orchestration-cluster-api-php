# ProcessInstanceModificationMoveInstruction

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**sourceElementInstruction** | [**\Camunda\Orchestration\Api\Model\SourceElementInstruction**](SourceElementInstruction.md) |  |
**targetElementId** | **string** | The target element id. |
**ancestorScopeInstruction** | [**\Camunda\Orchestration\Api\Model\AncestorScopeInstruction**](AncestorScopeInstruction.md) |  | [optional]
**variableInstructions** | [**\Camunda\Orchestration\Api\Model\ModifyProcessInstanceVariableInstruction[]**](ModifyProcessInstanceVariableInstruction.md) | Instructions describing which variables to create or update. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
