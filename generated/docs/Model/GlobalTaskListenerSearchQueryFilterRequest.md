# GlobalTaskListenerSearchQueryFilterRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | Id of the global listener. | [optional]
**type** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | Job type of the global listener. | [optional]
**retries** | [**\Camunda\Orchestration\Api\Model\IntegerFilterProperty**](IntegerFilterProperty.md) | Number of retries of the global listener. | [optional]
**eventTypes** | [**\CamundaOrchestrationApiModelGlobalTaskListenerEventTypeFilterProperty[]**](GlobalTaskListenerEventTypeFilterProperty.md) | Event types of the global listener. | [optional]
**afterNonGlobal** | **bool** | Whether the listener runs after model-level listeners. | [optional]
**priority** | [**\Camunda\Orchestration\Api\Model\IntegerFilterProperty**](IntegerFilterProperty.md) | Priority of the global listener. | [optional]
**source** | [**\Camunda\Orchestration\Api\Model\GlobalListenerSourceFilterProperty**](GlobalListenerSourceFilterProperty.md) | How the global listener was defined. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
