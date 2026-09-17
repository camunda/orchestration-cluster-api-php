# ProcessInstanceModificationInstruction

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**operationReference** | **int** | A reference key chosen by the user that will be part of all records resulting from this operation. Must be &gt; 0 if provided. | [optional]
**activateInstructions** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceModificationActivateInstruction[]**](ProcessInstanceModificationActivateInstruction.md) | Instructions describing which elements to activate in which scopes and which variables to create or update. | [optional]
**moveInstructions** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceModificationMoveInstruction[]**](ProcessInstanceModificationMoveInstruction.md) | Instructions describing which elements to move from one scope to another. | [optional]
**terminateInstructions** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceModificationTerminateInstruction[]**](ProcessInstanceModificationTerminateInstruction.md) | Instructions describing which elements to terminate. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
