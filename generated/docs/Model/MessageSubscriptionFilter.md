# MessageSubscriptionFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**businessId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | Filter by the business id inherited from the subscribing process instance when the subscription was opened. Supports advanced string filtering, including &#x60;$like&#x60; with &#x60;*&#x60;/&#x60;?&#x60; wildcards. | [optional]
**messageSubscriptionKey** | [**\Camunda\Orchestration\Api\Model\MessageSubscriptionKeyFilterProperty**](MessageSubscriptionKeyFilterProperty.md) | The message subscription key associated with this message subscription. | [optional]
**processDefinitionKey** | [**\Camunda\Orchestration\Api\Model\ProcessDefinitionKeyFilterProperty**](ProcessDefinitionKeyFilterProperty.md) | The process definition key associated with this correlated message subscription. This only works for data created with 8.9 and later. | [optional]
**processDefinitionId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The process definition ID associated with this message subscription. | [optional]
**processInstanceKey** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceKeyFilterProperty**](ProcessInstanceKeyFilterProperty.md) | The process instance key associated with this message subscription. | [optional]
**elementId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The element ID associated with this message subscription. | [optional]
**elementInstanceKey** | [**\Camunda\Orchestration\Api\Model\ElementInstanceKeyFilterProperty**](ElementInstanceKeyFilterProperty.md) | The element instance key associated with this message subscription. | [optional]
**messageSubscriptionState** | [**\Camunda\Orchestration\Api\Model\MessageSubscriptionStateFilterProperty**](MessageSubscriptionStateFilterProperty.md) | The message subscription state. | [optional]
**lastUpdatedDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The last updated date of the message subscription. | [optional]
**messageName** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The name of the message associated with the message subscription. | [optional]
**correlationKey** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The correlation key of the message subscription. | [optional]
**tenantId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The unique external tenant ID. | [optional]
**messageSubscriptionType** | [**\Camunda\Orchestration\Api\Model\MessageSubscriptionTypeFilterProperty**](MessageSubscriptionTypeFilterProperty.md) | The type of message subscription to filter by. When omitted, both &#x60;START_EVENT&#x60; and &#x60;PROCESS_EVENT&#x60; are returned. Only available for data created with Camunda 8.10 or later. | [optional]
**processDefinitionName** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The name of the process definition associated with this message subscription. | [optional]
**processDefinitionVersion** | [**\Camunda\Orchestration\Api\Model\IntegerFilterProperty**](IntegerFilterProperty.md) | The version of the process definition associated with this message subscription. | [optional]
**toolName** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | Filter by tool name extracted from the &#x60;io.camunda.tool:name&#x60; zeebe:property. | [optional]
**inboundConnectorType** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | Filter by inbound connector type extracted from the &#x60;inbound.type&#x60; zeebe:property. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
