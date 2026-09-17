# ProcessInstanceModificationBatchOperationRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**filter** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceFilter**](ProcessInstanceFilter.md) | The process instance filter. |
**moveInstructions** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceModificationMoveBatchOperationInstruction[]**](ProcessInstanceModificationMoveBatchOperationInstruction.md) | Instructions for moving tokens between elements. |
**operationReference** | **int** | A reference key chosen by the user that will be part of all records resulting from this operation. Must be &gt; 0 if provided. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
