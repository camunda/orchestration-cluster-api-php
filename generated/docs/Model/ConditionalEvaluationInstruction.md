# ConditionalEvaluationInstruction

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**tenantId** | **string** | Used to evaluate root-level conditional start events for a tenant with the given ID. This will only evaluate root-level conditional start events of process definitions which belong to the tenant. | [optional]
**processDefinitionKey** | **string** | Used to evaluate root-level conditional start events of the process definition with the given key. | [optional]
**variables** | **array<string,mixed>** | JSON object representing the variables to use for evaluation of the conditions and to pass to the process instances that have been triggered. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
