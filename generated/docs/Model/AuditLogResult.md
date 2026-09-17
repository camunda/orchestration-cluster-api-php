# AuditLogResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**auditLogKey** | **string** | The unique key of the audit log entry. |
**entityKey** | **string** | System-generated entity key for an audit log entry. |
**entityType** | [**\Camunda\Orchestration\Api\Model\AuditLogEntityTypeEnum**](AuditLogEntityTypeEnum.md) |  |
**operationType** | [**\Camunda\Orchestration\Api\Model\AuditLogOperationTypeEnum**](AuditLogOperationTypeEnum.md) |  |
**batchOperationKey** | **string** | Key of the batch operation. |
**batchOperationType** | [**\Camunda\Orchestration\Api\Model\BatchOperationTypeEnum**](BatchOperationTypeEnum.md) | The type of batch operation performed, if this is part of a batch. |
**timestamp** | **\DateTime** | The timestamp when the operation occurred. |
**actorId** | **string** | The ID of the actor who performed the operation. |
**actorType** | [**\Camunda\Orchestration\Api\Model\AuditLogActorTypeEnum**](AuditLogActorTypeEnum.md) | The type of the actor who performed the operation. |
**agentElementId** | **string** | The element ID of the agent that performed the operation (e.g. ad-hoc subprocess element ID). |
**tenantId** | **string** | The tenant ID of the audit log. |
**result** | [**\Camunda\Orchestration\Api\Model\AuditLogResultEnum**](AuditLogResultEnum.md) |  |
**category** | [**\Camunda\Orchestration\Api\Model\AuditLogCategoryEnum**](AuditLogCategoryEnum.md) |  |
**processDefinitionId** | **string** | The process definition ID. |
**processDefinitionKey** | **string** | The key of the process definition. |
**processInstanceKey** | **string** | The key of the process instance. |
**rootProcessInstanceKey** | **string** | The key of the root process instance. The root process instance is the top-level ancestor in the process instance hierarchy. This field is only present for data belonging to process instance hierarchies created in version 8.9 or later. |
**elementInstanceKey** | **string** | The key of the element instance. |
**jobKey** | **string** | The key of the job. |
**userTaskKey** | **string** | The key of the user task. |
**decisionRequirementsId** | **string** | The decision requirements ID. |
**decisionRequirementsKey** | **string** | The assigned key of the decision requirements. |
**decisionDefinitionId** | **string** | The decision definition ID. |
**decisionDefinitionKey** | **string** | The key of the decision definition. |
**decisionEvaluationKey** | **string** | The key of the decision evaluation. |
**deploymentKey** | **string** | The key of the deployment. |
**formKey** | **string** | The key of the form. |
**resourceKey** | [**\Camunda\Orchestration\Api\Model\ResourceKey**](ResourceKey.md) | The system-assigned key for this resource. |
**relatedEntityKey** | **string** | The key of the related entity. The content depends on the operation type and entity type. For example, for authorization operations, this will contain the ID of the owner (e.g., user or group) the authorization belongs to. |
**relatedEntityType** | [**\Camunda\Orchestration\Api\Model\AuditLogEntityTypeEnum**](AuditLogEntityTypeEnum.md) | The type of the related entity. The content depends on the operation type and entity type. For example, for authorization operations, this will contain the type of the owner (e.g., USER or GROUP) the authorization belongs to. |
**entityDescription** | **string** | Additional description of the entity affected by the operation. For example, for variable operations, this will contain the variable name. |
**inboundChannelType** | **string** | The type of the inbound channel that triggered the operation (e.g. MCP). |
**inboundChannelToolName** | **string** | The tool name of the inbound channel (e.g. the MCP tool that triggered the operation). |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
