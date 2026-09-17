# UpdateGlobalTaskListenerRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | **string** | The name of the job type, used as a reference to specify which job workers request the respective listener job. |
**retries** | **int** | Number of retries for the listener job. | [optional]
**afterNonGlobal** | **bool** | Whether the listener should run after model-level listeners. | [optional]
**priority** | **int** | The priority of the listener. Higher priority listeners are executed before lower priority ones. | [optional]
**eventTypes** | [**\Camunda\Orchestration\Api\Model\GlobalTaskListenerEventTypeEnum[]**](GlobalTaskListenerEventTypeEnum.md) | List of user task event types that trigger the listener. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
