# CorrelatedMessageSubscriptionResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**businessId** | **string** | The business id associated with this correlated message subscription. For a message start event correlation, it is the business id carried by the correlating message that was stamped on the started process instance to enforce its uniqueness. For a catch, boundary, or intermediate event correlation, it is the business id of the subscribing process instance, captured when the subscription was opened. It is &#x60;null&#x60; when the relevant process instance has no business id. |
**correlationKey** | **string** | The correlation key of the message. |
**correlationTime** | **\DateTime** | The time when the message was correlated. |
**elementId** | **string** | The element ID that received the message. |
**elementInstanceKey** | **string** | The element instance key that received the message. It is &#x60;null&#x60; for start event subscriptions. |
**messageKey** | **string** | The message key. |
**messageName** | **string** | The name of the message. |
**partitionId** | **int** | The partition ID that correlated the message. |
**processDefinitionId** | **string** | The process definition ID associated with this correlated message subscription. |
**processDefinitionKey** | **string** | The process definition key associated with this correlated message subscription. |
**processInstanceKey** | **string** | The process instance key associated with this correlated message subscription. |
**rootProcessInstanceKey** | **string** | The key of the root process instance. The root process instance is the top-level ancestor in the process instance hierarchy. This field is only present for data belonging to process instance hierarchies created in version 8.9 or later. |
**subscriptionKey** | **string** | The subscription key that received the message. |
**tenantId** | **string** | The tenant ID associated with this correlated message subscription. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
