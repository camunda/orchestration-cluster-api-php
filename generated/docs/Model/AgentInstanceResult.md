# AgentInstanceResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**agentInstanceKey** | **string** | The unique key for this agent instance. |
**agentDefinitionKey** | **string** | The key of the agent definition this agent instance is an instance of. |
**status** | [**\Camunda\Orchestration\Api\Model\AgentInstanceStatusEnum**](AgentInstanceStatusEnum.md) |  |
**definition** | [**\Camunda\Orchestration\Api\Model\AgentInstanceDefinitionResult**](AgentInstanceDefinitionResult.md) | The definition of the agent, including model, provider, and system prompt. Set at creation, but can change later via a CONFIGURATION history item. |
**metrics** | [**\Camunda\Orchestration\Api\Model\AgentInstanceMetrics**](AgentInstanceMetrics.md) | Aggregated metrics across all loopIterations of this agent instance. Includes history items later discarded: metrics are counted when an item is accepted, not when it&#39;s committed. |
**limits** | [**\Camunda\Orchestration\Api\Model\AgentInstanceLimits**](AgentInstanceLimits.md) | The configured limits for this agent instance, set once at creation. |
**tools** | [**\Camunda\Orchestration\Api\Model\AgentTool[]**](AgentTool.md) | The tools available to the agent. |
**elementId** | **string** | The BPMN element ID of the ad-hoc sub-process or AI agent task that owns this agent instance. |
**processInstanceKey** | **string** | The key of the process instance that owns this agent instance. |
**rootProcessInstanceKey** | **string** | The key of the root process instance. The root process instance is the top-level ancestor in the process instance hierarchy. |
**processDefinitionKey** | **string** | The key of the process definition associated with this agent instance. |
**processDefinitionId** | **string** | The BPMN process ID of the process definition associated with this agent instance. |
**processDefinitionVersion** | **int** | The version of the process definition associated with this agent instance. |
**processDefinitionVersionTag** | **string** | The version tag of the process definition associated with this agent instance. |
**tenantId** | **string** | The tenant ID of this agent instance. |
**creationDate** | **\DateTime** | The date when this agent instance was created. |
**lastUpdatedDate** | **\DateTime** | The date when this agent instance was last updated. |
**completionDate** | **\DateTime** | The date when this agent instance completed. Null while the agent is still running. |
**elementInstanceKeys** | **string[]** | The keys of all element instances associated with this agent instance. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
