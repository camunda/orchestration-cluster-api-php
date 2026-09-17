# BatchOperationItemFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**batchOperationKey** | [**\Camunda\Orchestration\Api\Model\BasicStringFilterProperty**](BasicStringFilterProperty.md) | The key (or operate legacy ID) of the batch operation. | [optional]
**itemKey** | [**\Camunda\Orchestration\Api\Model\BasicStringFilterProperty**](BasicStringFilterProperty.md) | The key of the item, e.g. a process instance key. | [optional]
**processInstanceKey** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceKeyFilterProperty**](ProcessInstanceKeyFilterProperty.md) | The process instance key of the processed item. | [optional]
**state** | [**\Camunda\Orchestration\Api\Model\BatchOperationItemStateFilterProperty**](BatchOperationItemStateFilterProperty.md) | The state of the batch operation. | [optional]
**operationType** | [**\Camunda\Orchestration\Api\Model\BatchOperationTypeFilterProperty**](BatchOperationTypeFilterProperty.md) | The type of the batch operation. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
