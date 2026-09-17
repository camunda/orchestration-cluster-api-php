# ProcessDefinitionInstanceStatisticsResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**processDefinitionId** | **string** | Id of a process definition, from the model. Only ids of process definitions that are deployed are useful. |
**tenantId** | **string** | The unique identifier of the tenant. |
**latestProcessDefinitionName** | **string** | Name of the latest deployed process definition instance version. |
**hasMultipleVersions** | **bool** | Indicates whether multiple versions of this process definition instance are deployed. |
**activeInstancesWithoutIncidentCount** | **int** | Total number of currently active process instances of this definition that do not have incidents. |
**activeInstancesWithIncidentCount** | **int** | Total number of currently active process instances of this definition that have at least one incident. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
