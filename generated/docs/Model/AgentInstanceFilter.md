# AgentInstanceFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**agentInstanceKey** | [**\Camunda\Orchestration\Api\Model\AgentInstanceKeyFilterProperty**](AgentInstanceKeyFilterProperty.md) | The unique key of the agent instance. | [optional]
**agentDefinitionKey** | [**\Camunda\Orchestration\Api\Model\AgentDefinitionKeyFilterProperty**](AgentDefinitionKeyFilterProperty.md) | The key of the agent definition this agent instance is an instance of. | [optional]
**status** | [**\Camunda\Orchestration\Api\Model\AgentInstanceStatusFilterProperty**](AgentInstanceStatusFilterProperty.md) | The current status of the agent instance. | [optional]
**elementId** | [**\Camunda\Orchestration\Api\Model\ElementIdFilterProperty**](ElementIdFilterProperty.md) | The BPMN element ID of the agent task. | [optional]
**processInstanceKey** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceKeyFilterProperty**](ProcessInstanceKeyFilterProperty.md) | The key of the process instance that owns this agent instance. | [optional]
**rootProcessInstanceKey** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceKeyFilterProperty**](ProcessInstanceKeyFilterProperty.md) | The key of the root process instance. Filters agent instances belonging to a specific call hierarchy. The root process instance is the top-level ancestor in the process instance hierarchy. | [optional]
**processDefinitionKey** | [**\Camunda\Orchestration\Api\Model\ProcessDefinitionKeyFilterProperty**](ProcessDefinitionKeyFilterProperty.md) | The key of the process definition associated with this agent instance. | [optional]
**tenantId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The tenant ID of the agent instance. | [optional]
**creationDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The creation date of the agent instance. | [optional]
**lastUpdatedDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The date the agent instance was last updated. | [optional]
**completionDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The completion date of the agent instance. | [optional]
**elementInstanceKeys** | [**\CamundaOrchestrationApiModelElementInstanceKeyFilterProperty[]**](ElementInstanceKeyFilterProperty.md) | The keys of element instances associated with this agent instance. If multiple keys are provided, the filter matches agent instances associated with all of the provided keys at the same time. | [optional]
**processDefinitionId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The BPMN process ID of the process definition associated with this agent instance. | [optional]
**processDefinitionVersion** | [**\Camunda\Orchestration\Api\Model\IntegerFilterProperty**](IntegerFilterProperty.md) | The version of the process definition associated with this agent instance. | [optional]
**processDefinitionVersionTag** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The version tag of the process definition associated with this agent instance. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
