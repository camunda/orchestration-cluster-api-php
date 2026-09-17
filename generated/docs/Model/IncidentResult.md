# IncidentResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**processDefinitionId** | **string** | The process definition ID associated to this incident. |
**errorType** | [**\Camunda\Orchestration\Api\Model\IncidentErrorTypeEnum**](IncidentErrorTypeEnum.md) | The type of the incident error. |
**errorMessage** | **string** | Error message which describes the error in more detail. |
**elementId** | **string** | The element ID associated to this incident. |
**creationTime** | **\DateTime** | The creation time of the incident. |
**state** | [**\Camunda\Orchestration\Api\Model\IncidentStateEnum**](IncidentStateEnum.md) | The incident state. |
**tenantId** | **string** | The tenant ID of the incident. |
**incidentKey** | **string** | The assigned key, which acts as a unique identifier for this incident. |
**processDefinitionKey** | **string** | The process definition key associated to this incident. |
**processInstanceKey** | **string** | The process instance key associated to this incident. |
**rootProcessInstanceKey** | **string** | The key of the root process instance. The root process instance is the top-level ancestor in the process instance hierarchy. This field is only present for data belonging to process instance hierarchies created in version 8.9 or later. |
**elementInstanceKey** | **string** | The element instance key associated to this incident. |
**jobKey** | **string** | The job key, if exists, associated with this incident. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
