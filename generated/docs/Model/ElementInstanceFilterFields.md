# ElementInstanceFilterFields

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**processDefinitionId** | **string** | The process definition ID associated to this element instance. | [optional]
**state** | [**\Camunda\Orchestration\Api\Model\ElementInstanceStateFilterProperty**](ElementInstanceStateFilterProperty.md) | State of element instance as defined set of values. | [optional]
**type** | **string** | Type of element as defined set of values. | [optional]
**elementId** | [**\Camunda\Orchestration\Api\Model\ElementIdFilterProperty**](ElementIdFilterProperty.md) | The element ID for this element instance. | [optional]
**elementName** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The element name. This only works for data created with 8.8 and onwards. Instances from prior versions don&#39;t contain this data and cannot be found. | [optional]
**hasIncident** | **bool** | Shows whether this element instance has an incident related to. | [optional]
**tenantId** | **string** | The unique identifier of the tenant. | [optional]
**elementInstanceKey** | **string** | The assigned key, which acts as a unique identifier for this element instance. | [optional]
**processInstanceKey** | **string** | The process instance key associated to this element instance. | [optional]
**processDefinitionKey** | **string** | The process definition key associated to this element instance. | [optional]
**incidentKey** | **string** | The key of incident if field incident is true. | [optional]
**startDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The start date of this element instance. | [optional]
**endDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The end date of this element instance. | [optional]
**elementInstanceScopeKey** | **string** | The scope key of this element instance. If provided with a process instance key it will return element instances that are immediate children of the process instance. If provided with an element instance key it will return element instances that are immediate children of the element instance. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
