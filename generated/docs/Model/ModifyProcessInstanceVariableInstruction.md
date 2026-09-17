# ModifyProcessInstanceVariableInstruction

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**variables** | **array<string,mixed>** | JSON document that will instantiate the variables at the scope defined by the scopeId. It must be a JSON object, as variables will be mapped in a key-value fashion. |
**scopeId** | **string** | The id of the element in which scope the variables should be created. Leave empty to create the variables in the global scope of the process instance. | [optional] [default to '']

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
