# IncidentFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**processDefinitionId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The process definition ID associated to this incident. | [optional]
**errorType** | [**\Camunda\Orchestration\Api\Model\IncidentErrorTypeFilterProperty**](IncidentErrorTypeFilterProperty.md) | Incident error type with a defined set of values. | [optional]
**errorMessage** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The error message of this incident. | [optional]
**elementId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The element ID associated to this incident. | [optional]
**creationTime** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | Date of incident creation. | [optional]
**state** | [**\Camunda\Orchestration\Api\Model\IncidentStateFilterProperty**](IncidentStateFilterProperty.md) | State of this incident with a defined set of values. | [optional]
**tenantId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The tenant ID of the incident. | [optional]
**incidentKey** | [**\Camunda\Orchestration\Api\Model\BasicStringFilterProperty**](BasicStringFilterProperty.md) | The assigned key, which acts as a unique identifier for this incident. | [optional]
**processDefinitionKey** | [**\Camunda\Orchestration\Api\Model\ProcessDefinitionKeyFilterProperty**](ProcessDefinitionKeyFilterProperty.md) | The process definition key associated to this incident. | [optional]
**processInstanceKey** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceKeyFilterProperty**](ProcessInstanceKeyFilterProperty.md) | The process instance key associated to this incident. | [optional]
**elementInstanceKey** | [**\Camunda\Orchestration\Api\Model\ElementInstanceKeyFilterProperty**](ElementInstanceKeyFilterProperty.md) | The element instance key associated to this incident. | [optional]
**jobKey** | [**\Camunda\Orchestration\Api\Model\JobKeyFilterProperty**](JobKeyFilterProperty.md) | The job key, if exists, associated with this incident. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
