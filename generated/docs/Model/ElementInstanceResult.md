# ElementInstanceResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**processDefinitionId** | **string** | The process definition ID associated to this element instance. |
**startDate** | **\DateTime** | Date when element instance started. |
**endDate** | **\DateTime** | Date when element instance finished. |
**elementId** | **string** | The element ID for this element instance. |
**elementName** | **string** | The element name for this element instance. |
**type** | **string** | Type of element as defined set of values. |
**state** | [**\Camunda\Orchestration\Api\Model\ElementInstanceStateEnum**](ElementInstanceStateEnum.md) | State of element instance as defined set of values. |
**hasIncident** | **bool** | Shows whether this element instance has an incident. If true also an incidentKey is provided. |
**tenantId** | **string** | The tenant ID of the incident. |
**elementInstanceKey** | **string** | The assigned key, which acts as a unique identifier for this element instance. |
**processInstanceKey** | **string** | The process instance key associated to this element instance. |
**rootProcessInstanceKey** | **string** | The key of the root process instance. The root process instance is the top-level ancestor in the process instance hierarchy. This field is only present for data belonging to process instance hierarchies created in version 8.9 or later. |
**processDefinitionKey** | **string** | The process definition key associated to this element instance. |
**incidentKey** | **string** | Incident key associated with this element instance. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
