# CorrelatedMessageSubscriptionFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**businessId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | Filter by the business id stored on the correlated message subscription — for message start event correlations the correlating message&#39;s business id, and for catch, boundary, or intermediate event correlations the subscribing process instance&#39;s business id. Supports advanced string filtering, including &#x60;$like&#x60; with &#x60;*&#x60;/&#x60;?&#x60; wildcards. | [optional]
**correlationKey** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The correlation key of the message. | [optional]
**correlationTime** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The time when the message was correlated. | [optional]
**elementId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The element ID that received the message. | [optional]
**elementInstanceKey** | [**\Camunda\Orchestration\Api\Model\ElementInstanceKeyFilterProperty**](ElementInstanceKeyFilterProperty.md) | The element instance key that received the message. | [optional]
**messageKey** | [**\Camunda\Orchestration\Api\Model\BasicStringFilterProperty**](BasicStringFilterProperty.md) | The message key. | [optional]
**messageName** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The name of the message. | [optional]
**partitionId** | [**\Camunda\Orchestration\Api\Model\IntegerFilterProperty**](IntegerFilterProperty.md) | The partition ID that correlated the message. | [optional]
**processDefinitionId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The process definition ID associated with this correlated message subscription. | [optional]
**processDefinitionKey** | [**\Camunda\Orchestration\Api\Model\ProcessDefinitionKeyFilterProperty**](ProcessDefinitionKeyFilterProperty.md) | The process definition key associated with this correlated message subscription. For intermediate message events, this only works for data created with 8.9 and later. | [optional]
**processInstanceKey** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceKeyFilterProperty**](ProcessInstanceKeyFilterProperty.md) | The process instance key associated with this correlated message subscription. | [optional]
**subscriptionKey** | [**\Camunda\Orchestration\Api\Model\MessageSubscriptionKeyFilterProperty**](MessageSubscriptionKeyFilterProperty.md) | The subscription key that received the message. | [optional]
**tenantId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The tenant ID associated with this correlated message subscription. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
