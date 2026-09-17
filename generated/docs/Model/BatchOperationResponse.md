# BatchOperationResponse

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**batchOperationKey** | **string** | Key or (Operate Legacy ID &#x3D; UUID) of the batch operation. |
**state** | [**\Camunda\Orchestration\Api\Model\BatchOperationStateEnum**](BatchOperationStateEnum.md) |  |
**batchOperationType** | [**\Camunda\Orchestration\Api\Model\BatchOperationTypeEnum**](BatchOperationTypeEnum.md) |  |
**startDate** | **\DateTime** | The start date of the batch operation. This is &#x60;null&#x60; if the batch operation has not yet started. |
**endDate** | **\DateTime** | The end date of the batch operation. This is &#x60;null&#x60; if the batch operation is still running. |
**actorType** | [**\Camunda\Orchestration\Api\Model\AuditLogActorTypeEnum**](AuditLogActorTypeEnum.md) | The type of the actor who performed the operation. This is &#x60;null&#x60; if the batch operation was created before 8.9, or if the actor information is not available. |
**actorId** | **string** | The ID of the actor who performed the operation. Available for batch operations created since 8.9. |
**operationsTotalCount** | **int** | The total number of items contained in this batch operation. |
**operationsFailedCount** | **int** | The number of items which failed during execution of the batch operation. (e.g. because they are rejected by the Zeebe engine). |
**operationsCompletedCount** | **int** | The number of successfully completed tasks. |
**errors** | [**\Camunda\Orchestration\Api\Model\BatchOperationError[]**](BatchOperationError.md) | The errors that occurred per partition during the batch operation. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
