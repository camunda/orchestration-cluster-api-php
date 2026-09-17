# BatchOperationItemResponse

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**operationType** | [**\Camunda\Orchestration\Api\Model\BatchOperationTypeEnum**](BatchOperationTypeEnum.md) |  |
**batchOperationKey** | **string** | The key (or operate legacy ID) of the batch operation. |
**itemKey** | **string** | Key of the item, e.g. a process instance key. |
**processInstanceKey** | **string** | The process instance key of the processed item. Null for batch-op types whose targets are not process instances (e.g. DELETE_DECISION_INSTANCE, DELETE_DECISION_DEFINITION, DELETE_PROCESS_DEFINITION). |
**rootProcessInstanceKey** | **string** | The key of the root process instance. The root process instance is the top-level ancestor in the process instance hierarchy. This field is only present for data belonging to process instance hierarchies created in version 8.9 or later. |
**state** | **string** | State of the item. |
**processedDate** | **\DateTime** | The date this item was processed. This is &#x60;null&#x60; if the item has not yet been processed. |
**errorMessage** | **string** | The error message from the engine in case of a failed operation. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
