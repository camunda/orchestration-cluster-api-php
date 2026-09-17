# AgentDefinitionResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**agentDefinitionKey** | **string** | The unique key for this agent definition. Unique across process definition versions. |
**agentType** | [**\Camunda\Orchestration\Api\Model\AgentDefinitionTypeEnum**](AgentDefinitionTypeEnum.md) |  |
**name** | **string** | The human-readable name of the process element that owns the agent definition. Falls back to elementId when the element has no BPMN name configured. |
**elementId** | **string** | The BPMN element ID of the process element that owns the agent definition. |
**processDefinitionId** | **string** | The BPMN process ID of the process definition that owns the agent definition. |
**processDefinitionKey** | **string** | The key of the process definition that owns the agent definition. |
**processDefinitionVersion** | **int** | The version of the process definition that owns the agent definition. |
**processDefinitionVersionTag** | **string** | The version tag of the process definition that owns the agent definition. |
**tenantId** | **string** | The tenant ID of this agent definition. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
