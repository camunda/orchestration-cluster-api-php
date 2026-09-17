# ProcessInstanceMigrationInstruction

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**targetProcessDefinitionKey** | **string** | The key of process definition to migrate the process instance to. |
**mappingInstructions** | [**\Camunda\Orchestration\Api\Model\MigrateProcessInstanceMappingInstruction[]**](MigrateProcessInstanceMappingInstruction.md) | Element mappings from the source process instance to the target process instance. |
**operationReference** | **int** | A reference key chosen by the user that will be part of all records resulting from this operation. Must be &gt; 0 if provided. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
