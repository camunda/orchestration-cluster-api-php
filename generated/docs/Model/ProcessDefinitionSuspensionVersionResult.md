# ProcessDefinitionSuspensionVersionResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**processDefinitionKey** | **string** | The key of the process definition version. |
**processDefinitionVersion** | **int** | The version of the process definition. |
**outcome** | **string** | &#x60;SUSPENDED&#x60; if the version was active and is now suspended. The suspension applies to all partitions shortly after the response. &#x60;ALREADY_SUSPENDED&#x60; if the version was already suspended, so its state is unchanged. With &#x60;includeProcessInstances&#x60;, its process instances are still suspended by the batch operation. &#x60;DRAINING&#x60; if it is being deleted and is left unchanged. This only occurs when the request selects by ID without a version; selecting a single draining version is rejected with 409. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
