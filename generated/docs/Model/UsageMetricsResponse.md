# UsageMetricsResponse

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**processInstances** | **int** | The amount of created root process instances. |
**decisionInstances** | **int** | The amount of executed decision instances. |
**assignees** | **int** | The amount of unique active task users. |
**activeTenants** | **int** | The amount of active tenants. |
**tenants** | [**array<string,\CamundaOrchestrationApiModelUsageMetricsResponseItem>**](UsageMetricsResponseItem.md) | The usage metrics by tenants. Only available if request &#x60;withTenants&#x60; query parameter was &#x60;true&#x60;. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
