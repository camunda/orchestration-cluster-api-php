# DeleteResourceResponse

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**resourceKey** | [**\Camunda\Orchestration\Api\Model\ResourceKey**](ResourceKey.md) | The system-assigned key for this resource, requested to be deleted. |
**batchOperation** | [**\Camunda\Orchestration\Api\Model\BatchOperationCreatedResult**](BatchOperationCreatedResult.md) | The batch operation created for asynchronously deleting the historic data.  Populated when &#x60;deleteHistory&#x60; is &#x60;true&#x60; and either the resource is a decision requirements definition, or the resource is a process definition that is already fully deleted from the runtime state (its history is purged directly by a batch operation).  For a process definition that still exists in the runtime state, deletion first drains the definition and its history is removed asynchronously as part of that lifecycle, so no batch operation is returned and this field is &#x60;null&#x60;. It is also &#x60;null&#x60; for forms and generic resources. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
