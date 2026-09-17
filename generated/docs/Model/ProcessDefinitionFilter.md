# ProcessDefinitionFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | Name of this process definition. | [optional]
**isLatestVersion** | **bool** | Whether to only return the latest version of each process definition. When using this filter, pagination functionality is limited, you can only paginate forward using &#x60;after&#x60; and &#x60;limit&#x60;. The response contains no &#x60;startCursor&#x60; in the &#x60;page&#x60;, and requests ignore the &#x60;from&#x60; and &#x60;before&#x60; in the &#x60;page&#x60;. When using this filter, sorting is limited to &#x60;processDefinitionId&#x60; and &#x60;tenantId&#x60; fields only. | [optional]
**resourceName** | **string** | Resource name of this process definition. | [optional]
**version** | **int** | Version of this process definition. | [optional]
**versionTag** | **string** | Version tag of this process definition. | [optional]
**processDefinitionId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | Process definition ID of this process definition. | [optional]
**tenantId** | **string** | Tenant ID of this process definition. | [optional]
**processDefinitionKey** | **string** | The key for this process definition. | [optional]
**hasStartForm** | **bool** | Indicates whether the start event of the process has an associated Form Key. | [optional]
**state** | **string** | Filter by the process definition&#39;s state. When not set, process definitions in any state are returned. Set to &#x60;ACTIVE&#x60; to exclude draining and deleted definitions (recommended for most use cases). Set to &#x60;DRAINING&#x60; to return only definitions that are being deleted but still have active process instances draining. Set to &#x60;DELETED&#x60; to return only definitions that have been deleted but are still retained in secondary storage. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
