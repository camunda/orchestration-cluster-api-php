# MessageSubscriptionResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**businessId** | **string** | The business id inherited from the subscribing process instance when this message subscription was opened. It is &#x60;null&#x60; when the process instance has no business id, and for message start event subscriptions, which are not tied to a process instance. |
**messageSubscriptionKey** | **string** | The message subscription key associated with this message subscription. |
**processDefinitionId** | **string** | The process definition ID associated with this message subscription. |
**processDefinitionKey** | **string** | The process definition key associated with this message subscription. |
**processInstanceKey** | **string** | The process instance key associated with this message subscription. Only populated for intermediate event entities. |
**rootProcessInstanceKey** | **string** | The key of the root process instance. The root process instance is the top-level ancestor in the process instance hierarchy. This field is only present for data belonging to process instance hierarchies created in version 8.9 or later. |
**elementId** | **string** | The element ID associated with this message subscription. |
**elementInstanceKey** | **string** | The element instance key associated with this message subscription. Only populated for intermediate event entities. |
**messageSubscriptionState** | [**\Camunda\Orchestration\Api\Model\MessageSubscriptionStateEnum**](MessageSubscriptionStateEnum.md) |  |
**lastUpdatedDate** | **\DateTime** | The last updated date of the message subscription. |
**messageName** | **string** | The name of the message associated with the message subscription. |
**correlationKey** | **string** | The correlation key of the message subscription. |
**messageSubscriptionType** | [**\Camunda\Orchestration\Api\Model\MessageSubscriptionTypeEnum**](MessageSubscriptionTypeEnum.md) |  |
**toolProperties** | **array<string,string>** | The subset of &#x60;zeebe:properties&#x60; extension properties whose keys start with the &#x60;io.camunda.tool:&#x60; prefix, extracted from the BPMN element associated with this subscription. Empty object when no matching properties are defined. |
**processDefinitionName** | **string** | The name of the process definition associated with this message subscription. |
**processDefinitionVersion** | **int** | The version of the process definition associated with this message subscription. |
**toolName** | **string** | Tool name extracted from the &#x60;io.camunda.tool:name&#x60; zeebe:property. Null when the property is absent. |
**inboundConnectorType** | **string** | Inbound connector type extracted from the &#x60;inbound.type&#x60; zeebe:property. Null when the property is absent. |
**tenantId** | **string** | The unique identifier of the tenant. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
