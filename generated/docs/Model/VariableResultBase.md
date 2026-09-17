# VariableResultBase

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** | Name of this variable. |
**tenantId** | **string** | Tenant ID of this variable. |
**variableKey** | **string** | The key for this variable. |
**scopeKey** | [**\Camunda\Orchestration\Api\Model\ScopeKey**](ScopeKey.md) | The key of the scope where this variable is directly defined. For process-level variables, this is the process instance key. For local variables, this is the key of the specific element instance (task, subprocess, gateway, event, etc.) where the variable is directly defined. |
**processInstanceKey** | **string** | The key of the process instance of this variable. |
**rootProcessInstanceKey** | **string** | The key of the root process instance. The root process instance is the top-level ancestor in the process instance hierarchy. This field is only present for data belonging to process instance hierarchies created in version 8.9 or later. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
