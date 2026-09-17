# VariableFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | Name of the variable. | [optional]
**value** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The value of the variable. Variable values in filters need to be in serialized JSON format. For example, a variable with string value &#x60;myValue&#x60; can be found with the filter value &#x60;\&quot;myValue\&quot;&#x60;. Consider appropriate escaping for special characters in JSON strings when constructing filter values. | [optional]
**tenantId** | **string** | Tenant ID of this variable. | [optional]
**isTruncated** | **bool** | Whether the value is truncated or not. | [optional]
**variableKey** | [**\Camunda\Orchestration\Api\Model\VariableKeyFilterProperty**](VariableKeyFilterProperty.md) | The key for this variable. | [optional]
**scopeKey** | [**\Camunda\Orchestration\Api\Model\ScopeKeyFilterProperty**](ScopeKeyFilterProperty.md) | The key of the scope that defines where this variable is directly defined. This can be a process instance key (for process-level variables) or an element instance key (for local variables scoped to tasks, subprocesses, gateways, events, etc.). Use this filter to find variables directly defined in specific scopes. Note that this does not include variables from parent scopes that would be visible through the scope hierarchy. | [optional]
**processInstanceKey** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceKeyFilterProperty**](ProcessInstanceKeyFilterProperty.md) | The key of the process instance of this variable. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
