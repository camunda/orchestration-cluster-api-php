# Camunda\Orchestration\Api

API for communicating with a Camunda 8 cluster.

## Conventions

### Enum value casing

Server-side, enum-typed request fields accept any casing of a
documented value (for example `ACTIVE`, `active`, and `Active` are
all accepted on input). Responses always use the canonical casing
shown in this specification.

This permissive request behavior is a server-side convenience and
is **not** part of the OpenAPI enum contract — SDKs generated from
this specification by strict generators may reject non-canonical
casing on the client side. For portability across SDKs, always
send the canonical casing shown in this document.


For more information, please visit [https://github.com/camunda/camunda/issues](https://github.com/camunda/camunda/issues).

## Installation & Usage

### Requirements

PHP 8.1 and later.

### Composer

To install the bindings via [Composer](https://getcomposer.org/), add the following to `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/GIT_USER_ID/GIT_REPO_ID.git"
    }
  ],
  "require": {
    "GIT_USER_ID/GIT_REPO_ID": "*@dev"
  }
}
```

Then run `composer install`

### Manual Installation

Download the files and include `autoload.php`:

```php
<?php
require_once('/path/to/Camunda\Orchestration\Api/vendor/autoload.php');
```

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



// Configure HTTP basic authorization: basicAuth
$config = Camunda\Orchestration\Api\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');

// Configure Bearer (JWT) authorization: bearerAuth
$config = Camunda\Orchestration\Api\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new Camunda\Orchestration\Api\Api\AdHocSubProcessApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$adHocSubProcessInstanceKey = 'adHocSubProcessInstanceKey_example'; // string | The key of the ad-hoc sub-process instance that contains the activities.
$adHocSubProcessActivateActivitiesInstruction = new \Camunda\Orchestration\Api\Model\AdHocSubProcessActivateActivitiesInstruction(); // \Camunda\Orchestration\Api\Model\AdHocSubProcessActivateActivitiesInstruction

try {
    $apiInstance->activateAdHocSubProcessActivities($adHocSubProcessInstanceKey, $adHocSubProcessActivateActivitiesInstruction);
} catch (Exception $e) {
    echo 'Exception when calling AdHocSubProcessApi->activateAdHocSubProcessActivities: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *http://localhost:8080/v2*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*AdHocSubProcessApi* | [**activateAdHocSubProcessActivities**](docs/Api/AdHocSubProcessApi.md#activateadhocsubprocessactivities) | **POST** /element-instances/ad-hoc-activities/{adHocSubProcessInstanceKey}/activation | Activate activities within an ad-hoc sub-process
*AgentDefinitionApi* | [**getAgentDefinition**](docs/Api/AgentDefinitionApi.md#getagentdefinition) | **GET** /agent-definitions/{agentDefinitionKey} | Get agent definition
*AgentDefinitionApi* | [**searchAgentDefinitions**](docs/Api/AgentDefinitionApi.md#searchagentdefinitions) | **POST** /agent-definitions/search | Search agent definitions
*AgentInstanceApi* | [**createAgentInstance**](docs/Api/AgentInstanceApi.md#createagentinstance) | **POST** /agent-instances | Create agent instance
*AgentInstanceApi* | [**getAgentInstance**](docs/Api/AgentInstanceApi.md#getagentinstance) | **GET** /agent-instances/{agentInstanceKey} | Get agent instance
*AgentInstanceApi* | [**searchAgentInstanceHistory**](docs/Api/AgentInstanceApi.md#searchagentinstancehistory) | **POST** /agent-instances/{agentInstanceKey}/history/search | Search agent instance history
*AgentInstanceApi* | [**searchAgentInstances**](docs/Api/AgentInstanceApi.md#searchagentinstances) | **POST** /agent-instances/search | Search agent instances
*AgentInstanceApi* | [**updateAgentInstance**](docs/Api/AgentInstanceApi.md#updateagentinstance) | **PATCH** /agent-instances/{agentInstanceKey} | Update agent instance
*AuditLogApi* | [**getAuditLog**](docs/Api/AuditLogApi.md#getauditlog) | **GET** /audit-logs/{auditLogKey} | Get audit log
*AuditLogApi* | [**searchAuditLogs**](docs/Api/AuditLogApi.md#searchauditlogs) | **POST** /audit-logs/search | Search audit logs
*AuthenticationApi* | [**getAuthentication**](docs/Api/AuthenticationApi.md#getauthentication) | **GET** /authentication/me | Get current user
*AuthenticationApi* | [**searchOwnAuthorizations**](docs/Api/AuthenticationApi.md#searchownauthorizations) | **POST** /authentication/me/authorizations/search | Search own authorizations
*AuthorizationApi* | [**createAuthorization**](docs/Api/AuthorizationApi.md#createauthorization) | **POST** /authorizations | Create authorization
*AuthorizationApi* | [**deleteAuthorization**](docs/Api/AuthorizationApi.md#deleteauthorization) | **DELETE** /authorizations/{authorizationKey} | Delete authorization
*AuthorizationApi* | [**getAuthorization**](docs/Api/AuthorizationApi.md#getauthorization) | **GET** /authorizations/{authorizationKey} | Get authorization
*AuthorizationApi* | [**searchAuthorizations**](docs/Api/AuthorizationApi.md#searchauthorizations) | **POST** /authorizations/search | Search authorizations
*AuthorizationApi* | [**updateAuthorization**](docs/Api/AuthorizationApi.md#updateauthorization) | **PUT** /authorizations/{authorizationKey} | Update authorization
*BackupApi* | [**deleteHistoryBackup**](docs/Api/BackupApi.md#deletehistorybackup) | **DELETE** /backups/history/{backupId} | Delete history backup
*BackupApi* | [**deleteHistoryBackupAsClusterAdmin**](docs/Api/BackupApi.md#deletehistorybackupasclusteradmin) | **DELETE** /cluster/v2/backups/history/{backupId} | Delete a history backup across physical tenants
*BackupApi* | [**deleteRuntimeBackup**](docs/Api/BackupApi.md#deleteruntimebackup) | **DELETE** /backups/runtime/{backupId} | Delete runtime backup
*BackupApi* | [**deleteRuntimeBackupAsClusterAdmin**](docs/Api/BackupApi.md#deleteruntimebackupasclusteradmin) | **DELETE** /cluster/v2/backups/runtime/{backupId} | Delete a runtime backup across physical tenants
*BackupApi* | [**deleteRuntimeBackupState**](docs/Api/BackupApi.md#deleteruntimebackupstate) | **DELETE** /backups/runtime/state | Delete runtime backup state
*BackupApi* | [**deleteRuntimeBackupStateAsClusterAdmin**](docs/Api/BackupApi.md#deleteruntimebackupstateasclusteradmin) | **DELETE** /cluster/v2/backups/runtime/state | Delete runtime backup state across physical tenants
*BackupApi* | [**getHistoryBackup**](docs/Api/BackupApi.md#gethistorybackup) | **GET** /backups/history/{backupId} | Get history backup
*BackupApi* | [**getHistoryBackupAsClusterAdmin**](docs/Api/BackupApi.md#gethistorybackupasclusteradmin) | **GET** /cluster/v2/backups/history/{backupId} | Get a history backup across physical tenants
*BackupApi* | [**getRuntimeBackup**](docs/Api/BackupApi.md#getruntimebackup) | **GET** /backups/runtime/{backupId} | Get runtime backup
*BackupApi* | [**getRuntimeBackupAsClusterAdmin**](docs/Api/BackupApi.md#getruntimebackupasclusteradmin) | **GET** /cluster/v2/backups/runtime/{backupId} | Get a runtime backup across physical tenants
*BackupApi* | [**getRuntimeBackupState**](docs/Api/BackupApi.md#getruntimebackupstate) | **GET** /backups/runtime/state | Get runtime backup state
*BackupApi* | [**getRuntimeBackupStateAsClusterAdmin**](docs/Api/BackupApi.md#getruntimebackupstateasclusteradmin) | **GET** /cluster/v2/backups/runtime/state | Get runtime backup state across physical tenants
*BackupApi* | [**listHistoryBackups**](docs/Api/BackupApi.md#listhistorybackups) | **GET** /backups/history | List history backups
*BackupApi* | [**listHistoryBackupsAsClusterAdmin**](docs/Api/BackupApi.md#listhistorybackupsasclusteradmin) | **GET** /cluster/v2/backups/history | List history backups across physical tenants
*BackupApi* | [**listRuntimeBackups**](docs/Api/BackupApi.md#listruntimebackups) | **GET** /backups/runtime | List runtime backups
*BackupApi* | [**listRuntimeBackupsAsClusterAdmin**](docs/Api/BackupApi.md#listruntimebackupsasclusteradmin) | **GET** /cluster/v2/backups/runtime | List runtime backups across physical tenants
*BackupApi* | [**syncRuntimeBackupState**](docs/Api/BackupApi.md#syncruntimebackupstate) | **POST** /backups/runtime/state/sync | Force-write runtime backup state
*BackupApi* | [**syncRuntimeBackupStateAsClusterAdmin**](docs/Api/BackupApi.md#syncruntimebackupstateasclusteradmin) | **POST** /cluster/v2/backups/runtime/state/sync | Force-write runtime backup state across physical tenants
*BackupApi* | [**takeHistoryBackup**](docs/Api/BackupApi.md#takehistorybackup) | **POST** /backups/history | Take a history backup
*BackupApi* | [**takeHistoryBackupAsClusterAdmin**](docs/Api/BackupApi.md#takehistorybackupasclusteradmin) | **POST** /cluster/v2/backups/history | Take a history backup on one or every physical tenant
*BackupApi* | [**takeRuntimeBackup**](docs/Api/BackupApi.md#takeruntimebackup) | **POST** /backups/runtime | Take a runtime backup
*BackupApi* | [**takeRuntimeBackupAsClusterAdmin**](docs/Api/BackupApi.md#takeruntimebackupasclusteradmin) | **POST** /cluster/v2/backups/runtime | Take a runtime backup on one or every physical tenant
*BatchOperationApi* | [**cancelBatchOperation**](docs/Api/BatchOperationApi.md#cancelbatchoperation) | **POST** /batch-operations/{batchOperationKey}/cancellation | Cancel Batch operation
*BatchOperationApi* | [**getBatchOperation**](docs/Api/BatchOperationApi.md#getbatchoperation) | **GET** /batch-operations/{batchOperationKey} | Get batch operation
*BatchOperationApi* | [**resumeBatchOperation**](docs/Api/BatchOperationApi.md#resumebatchoperation) | **POST** /batch-operations/{batchOperationKey}/resumption | Resume Batch operation
*BatchOperationApi* | [**searchBatchOperationItems**](docs/Api/BatchOperationApi.md#searchbatchoperationitems) | **POST** /batch-operation-items/search | Search batch operation items
*BatchOperationApi* | [**searchBatchOperations**](docs/Api/BatchOperationApi.md#searchbatchoperations) | **POST** /batch-operations/search | Search batch operations
*BatchOperationApi* | [**suspendBatchOperation**](docs/Api/BatchOperationApi.md#suspendbatchoperation) | **POST** /batch-operations/{batchOperationKey}/suspension | Suspend Batch operation
*ClockApi* | [**pinClock**](docs/Api/ClockApi.md#pinclock) | **PUT** /clock | Pin internal clock (alpha)
*ClockApi* | [**resetClock**](docs/Api/ClockApi.md#resetclock) | **POST** /clock/reset | Reset internal clock (alpha)
*ClusterApi* | [**cancelClusterRebalance**](docs/Api/ClusterApi.md#cancelclusterrebalance) | **DELETE** /cluster/v2/rebalance | Stop the running rebalance
*ClusterApi* | [**getClusterRebalance**](docs/Api/ClusterApi.md#getclusterrebalance) | **GET** /cluster/v2/rebalance | Report the cluster&#39;s current leadership balance
*ClusterApi* | [**getClusterStatus**](docs/Api/ClusterApi.md#getclusterstatus) | **GET** /cluster/v2/status | Get the status of the whole cluster
*ClusterApi* | [**getClusterTopology**](docs/Api/ClusterApi.md#getclustertopology) | **GET** /cluster/v2/topology | Get the topology of the whole cluster
*ClusterApi* | [**getStatus**](docs/Api/ClusterApi.md#getstatus) | **GET** /status | Get physical tenant status
*ClusterApi* | [**getTopology**](docs/Api/ClusterApi.md#gettopology) | **GET** /topology | Get cluster topology
*ClusterApi* | [**triggerClusterRebalance**](docs/Api/ClusterApi.md#triggerclusterrebalance) | **POST** /cluster/v2/rebalance | Trigger a cluster-wide leadership rebalance
*ClusterVariableApi* | [**createGlobalClusterVariable**](docs/Api/ClusterVariableApi.md#createglobalclustervariable) | **POST** /cluster-variables/global | Create a global-scoped cluster variable
*ClusterVariableApi* | [**createTenantClusterVariable**](docs/Api/ClusterVariableApi.md#createtenantclustervariable) | **POST** /cluster-variables/tenants/{tenantId} | Create a tenant-scoped cluster variable
*ClusterVariableApi* | [**deleteGlobalClusterVariable**](docs/Api/ClusterVariableApi.md#deleteglobalclustervariable) | **DELETE** /cluster-variables/global/{name} | Delete a global-scoped cluster variable
*ClusterVariableApi* | [**deleteTenantClusterVariable**](docs/Api/ClusterVariableApi.md#deletetenantclustervariable) | **DELETE** /cluster-variables/tenants/{tenantId}/{name} | Delete a tenant-scoped cluster variable
*ClusterVariableApi* | [**getGlobalClusterVariable**](docs/Api/ClusterVariableApi.md#getglobalclustervariable) | **GET** /cluster-variables/global/{name} | Get a global-scoped cluster variable
*ClusterVariableApi* | [**getTenantClusterVariable**](docs/Api/ClusterVariableApi.md#gettenantclustervariable) | **GET** /cluster-variables/tenants/{tenantId}/{name} | Get a tenant-scoped cluster variable
*ClusterVariableApi* | [**searchClusterVariables**](docs/Api/ClusterVariableApi.md#searchclustervariables) | **POST** /cluster-variables/search | 
*ClusterVariableApi* | [**updateGlobalClusterVariable**](docs/Api/ClusterVariableApi.md#updateglobalclustervariable) | **PUT** /cluster-variables/global/{name} | Update a global-scoped cluster variable
*ClusterVariableApi* | [**updateTenantClusterVariable**](docs/Api/ClusterVariableApi.md#updatetenantclustervariable) | **PUT** /cluster-variables/tenants/{tenantId}/{name} | Update a tenant-scoped cluster variable
*ConditionalApi* | [**evaluateConditionals**](docs/Api/ConditionalApi.md#evaluateconditionals) | **POST** /conditionals/evaluation | Evaluate root level conditional start events
*DecisionDefinitionApi* | [**evaluateDecision**](docs/Api/DecisionDefinitionApi.md#evaluatedecision) | **POST** /decision-definitions/evaluation | Evaluate decision
*DecisionDefinitionApi* | [**getDecisionDefinition**](docs/Api/DecisionDefinitionApi.md#getdecisiondefinition) | **GET** /decision-definitions/{decisionDefinitionKey} | Get decision definition
*DecisionDefinitionApi* | [**getDecisionDefinitionXML**](docs/Api/DecisionDefinitionApi.md#getdecisiondefinitionxml) | **GET** /decision-definitions/{decisionDefinitionKey}/xml | Get decision definition XML
*DecisionDefinitionApi* | [**searchDecisionDefinitions**](docs/Api/DecisionDefinitionApi.md#searchdecisiondefinitions) | **POST** /decision-definitions/search | Search decision definitions
*DecisionInstanceApi* | [**deleteDecisionInstance**](docs/Api/DecisionInstanceApi.md#deletedecisioninstance) | **POST** /decision-instances/{decisionEvaluationKey}/deletion | Delete decision instance
*DecisionInstanceApi* | [**deleteDecisionInstancesBatchOperation**](docs/Api/DecisionInstanceApi.md#deletedecisioninstancesbatchoperation) | **POST** /decision-instances/deletion | Delete decision instances (batch)
*DecisionInstanceApi* | [**getDecisionInstance**](docs/Api/DecisionInstanceApi.md#getdecisioninstance) | **GET** /decision-instances/{decisionEvaluationInstanceKey} | Get decision instance
*DecisionInstanceApi* | [**searchDecisionInstances**](docs/Api/DecisionInstanceApi.md#searchdecisioninstances) | **POST** /decision-instances/search | Search decision instances
*DecisionRequirementsApi* | [**getDecisionRequirements**](docs/Api/DecisionRequirementsApi.md#getdecisionrequirements) | **GET** /decision-requirements/{decisionRequirementsKey} | Get decision requirements
*DecisionRequirementsApi* | [**getDecisionRequirementsXML**](docs/Api/DecisionRequirementsApi.md#getdecisionrequirementsxml) | **GET** /decision-requirements/{decisionRequirementsKey}/xml | Get decision requirements XML
*DecisionRequirementsApi* | [**searchDecisionRequirements**](docs/Api/DecisionRequirementsApi.md#searchdecisionrequirements) | **POST** /decision-requirements/search | Search decision requirements
*DocumentApi* | [**createDocument**](docs/Api/DocumentApi.md#createdocument) | **POST** /documents | Upload document
*DocumentApi* | [**createDocumentLink**](docs/Api/DocumentApi.md#createdocumentlink) | **POST** /documents/{documentId}/links | Create document link
*DocumentApi* | [**createDocuments**](docs/Api/DocumentApi.md#createdocuments) | **POST** /documents/batch | Upload multiple documents
*DocumentApi* | [**deleteDocument**](docs/Api/DocumentApi.md#deletedocument) | **DELETE** /documents/{documentId} | Delete document
*DocumentApi* | [**getDocument**](docs/Api/DocumentApi.md#getdocument) | **GET** /documents/{documentId} | Download document
*ElementInstanceApi* | [**createElementInstanceVariables**](docs/Api/ElementInstanceApi.md#createelementinstancevariables) | **PUT** /element-instances/{elementInstanceKey}/variables | Update element instance variables
*ElementInstanceApi* | [**getElementInstance**](docs/Api/ElementInstanceApi.md#getelementinstance) | **GET** /element-instances/{elementInstanceKey} | Get element instance
*ElementInstanceApi* | [**searchElementInstanceIncidents**](docs/Api/ElementInstanceApi.md#searchelementinstanceincidents) | **POST** /element-instances/{elementInstanceKey}/incidents/search | Search for incidents of a specific element instance
*ElementInstanceApi* | [**searchElementInstanceWaitStates**](docs/Api/ElementInstanceApi.md#searchelementinstancewaitstates) | **POST** /element-instances/wait-states/search | Search element instance wait states
*ElementInstanceApi* | [**searchElementInstances**](docs/Api/ElementInstanceApi.md#searchelementinstances) | **POST** /element-instances/search | Search element instances
*ExportingApi* | [**getClusterExportingStatus**](docs/Api/ExportingApi.md#getclusterexportingstatus) | **GET** /cluster/v2/exporting | Get exporting status of the whole cluster
*ExportingApi* | [**getExportingStatus**](docs/Api/ExportingApi.md#getexportingstatus) | **GET** /exporting | Get exporting status
*ExportingApi* | [**pauseClusterExporting**](docs/Api/ExportingApi.md#pauseclusterexporting) | **POST** /cluster/v2/exporting/pause | Pause exporting across the whole cluster
*ExportingApi* | [**pauseExporting**](docs/Api/ExportingApi.md#pauseexporting) | **POST** /exporting/pause | Pause exporting
*ExportingApi* | [**resumeClusterExporting**](docs/Api/ExportingApi.md#resumeclusterexporting) | **POST** /cluster/v2/exporting/resume | Resume exporting across the whole cluster
*ExportingApi* | [**resumeExporting**](docs/Api/ExportingApi.md#resumeexporting) | **POST** /exporting/resume | Resume exporting
*ExpressionApi* | [**evaluateExpression**](docs/Api/ExpressionApi.md#evaluateexpression) | **POST** /expression/evaluation | Evaluate an expression
*FormApi* | [**getFormByKey**](docs/Api/FormApi.md#getformbykey) | **GET** /forms/{formKey} | Get form by key
*GlobalListenerApi* | [**createGlobalTaskListener**](docs/Api/GlobalListenerApi.md#createglobaltasklistener) | **POST** /global-task-listeners | Create global user task listener
*GlobalListenerApi* | [**deleteGlobalTaskListener**](docs/Api/GlobalListenerApi.md#deleteglobaltasklistener) | **DELETE** /global-task-listeners/{id} | Delete global user task listener
*GlobalListenerApi* | [**getGlobalTaskListener**](docs/Api/GlobalListenerApi.md#getglobaltasklistener) | **GET** /global-task-listeners/{id} | Get global user task listener
*GlobalListenerApi* | [**searchGlobalTaskListeners**](docs/Api/GlobalListenerApi.md#searchglobaltasklisteners) | **POST** /global-task-listeners/search | Search global user task listeners
*GlobalListenerApi* | [**updateGlobalTaskListener**](docs/Api/GlobalListenerApi.md#updateglobaltasklistener) | **PUT** /global-task-listeners/{id} | Update global user task listener
*GroupApi* | [**assignClientToGroup**](docs/Api/GroupApi.md#assignclienttogroup) | **PUT** /groups/{groupId}/clients/{clientId} | Assign a client to a group
*GroupApi* | [**assignMappingRuleToGroup**](docs/Api/GroupApi.md#assignmappingruletogroup) | **PUT** /groups/{groupId}/mapping-rules/{mappingRuleId} | Assign a mapping rule to a group
*GroupApi* | [**assignUserToGroup**](docs/Api/GroupApi.md#assignusertogroup) | **PUT** /groups/{groupId}/users/{username} | Assign a user to a group
*GroupApi* | [**createGroup**](docs/Api/GroupApi.md#creategroup) | **POST** /groups | Create group
*GroupApi* | [**deleteGroup**](docs/Api/GroupApi.md#deletegroup) | **DELETE** /groups/{groupId} | Delete group
*GroupApi* | [**getGroup**](docs/Api/GroupApi.md#getgroup) | **GET** /groups/{groupId} | Get group
*GroupApi* | [**searchClientsForGroup**](docs/Api/GroupApi.md#searchclientsforgroup) | **POST** /groups/{groupId}/clients/search | Search group clients
*GroupApi* | [**searchGroups**](docs/Api/GroupApi.md#searchgroups) | **POST** /groups/search | Search groups
*GroupApi* | [**searchMappingRulesForGroup**](docs/Api/GroupApi.md#searchmappingrulesforgroup) | **POST** /groups/{groupId}/mapping-rules/search | Search group mapping rules
*GroupApi* | [**searchRolesForGroup**](docs/Api/GroupApi.md#searchrolesforgroup) | **POST** /groups/{groupId}/roles/search | Search group roles
*GroupApi* | [**searchUsersForGroup**](docs/Api/GroupApi.md#searchusersforgroup) | **POST** /groups/{groupId}/users/search | Search group users
*GroupApi* | [**unassignClientFromGroup**](docs/Api/GroupApi.md#unassignclientfromgroup) | **DELETE** /groups/{groupId}/clients/{clientId} | Unassign a client from a group
*GroupApi* | [**unassignMappingRuleFromGroup**](docs/Api/GroupApi.md#unassignmappingrulefromgroup) | **DELETE** /groups/{groupId}/mapping-rules/{mappingRuleId} | Unassign a mapping rule from a group
*GroupApi* | [**unassignUserFromGroup**](docs/Api/GroupApi.md#unassignuserfromgroup) | **DELETE** /groups/{groupId}/users/{username} | Unassign a user from a group
*GroupApi* | [**updateGroup**](docs/Api/GroupApi.md#updategroup) | **PUT** /groups/{groupId} | Update group
*IncidentApi* | [**getIncident**](docs/Api/IncidentApi.md#getincident) | **GET** /incidents/{incidentKey} | Get incident
*IncidentApi* | [**getProcessInstanceStatisticsByDefinition**](docs/Api/IncidentApi.md#getprocessinstancestatisticsbydefinition) | **POST** /incidents/statistics/process-instances-by-definition | Get process instance statistics by definition
*IncidentApi* | [**getProcessInstanceStatisticsByError**](docs/Api/IncidentApi.md#getprocessinstancestatisticsbyerror) | **POST** /incidents/statistics/process-instances-by-error | Get process instance statistics by error
*IncidentApi* | [**resolveIncident**](docs/Api/IncidentApi.md#resolveincident) | **POST** /incidents/{incidentKey}/resolution | Resolve incident
*IncidentApi* | [**searchIncidents**](docs/Api/IncidentApi.md#searchincidents) | **POST** /incidents/search | Search incidents
*JobApi* | [**activateJobs**](docs/Api/JobApi.md#activatejobs) | **POST** /jobs/activation | Activate jobs
*JobApi* | [**completeJob**](docs/Api/JobApi.md#completejob) | **POST** /jobs/{jobKey}/completion | Complete job
*JobApi* | [**failJob**](docs/Api/JobApi.md#failjob) | **POST** /jobs/{jobKey}/failure | Fail job
*JobApi* | [**getGlobalJobStatistics**](docs/Api/JobApi.md#getglobaljobstatistics) | **GET** /jobs/statistics/global | Global job statistics
*JobApi* | [**getJobErrorStatistics**](docs/Api/JobApi.md#getjoberrorstatistics) | **POST** /jobs/statistics/errors | Get error metrics for a job type
*JobApi* | [**getJobTimeSeriesStatistics**](docs/Api/JobApi.md#getjobtimeseriesstatistics) | **POST** /jobs/statistics/time-series | Get time-series metrics for a job type
*JobApi* | [**getJobTypeStatistics**](docs/Api/JobApi.md#getjobtypestatistics) | **POST** /jobs/statistics/by-types | Get job statistics by type
*JobApi* | [**getJobWorkerStatistics**](docs/Api/JobApi.md#getjobworkerstatistics) | **POST** /jobs/statistics/by-workers | Get job statistics by worker
*JobApi* | [**searchJobs**](docs/Api/JobApi.md#searchjobs) | **POST** /jobs/search | Search jobs
*JobApi* | [**throwJobError**](docs/Api/JobApi.md#throwjoberror) | **POST** /jobs/{jobKey}/error | Throw error for job
*JobApi* | [**updateJob**](docs/Api/JobApi.md#updatejob) | **PATCH** /jobs/{jobKey} | Update job
*JobApi* | [**updateJobsBatchOperation**](docs/Api/JobApi.md#updatejobsbatchoperation) | **POST** /jobs/batch-update | Update jobs (batch)
*LicenseApi* | [**getLicense**](docs/Api/LicenseApi.md#getlicense) | **GET** /license | Get license status
*MappingRuleApi* | [**createMappingRule**](docs/Api/MappingRuleApi.md#createmappingrule) | **POST** /mapping-rules | Create mapping rule
*MappingRuleApi* | [**deleteMappingRule**](docs/Api/MappingRuleApi.md#deletemappingrule) | **DELETE** /mapping-rules/{mappingRuleId} | Delete a mapping rule
*MappingRuleApi* | [**getMappingRule**](docs/Api/MappingRuleApi.md#getmappingrule) | **GET** /mapping-rules/{mappingRuleId} | Get a mapping rule
*MappingRuleApi* | [**searchMappingRule**](docs/Api/MappingRuleApi.md#searchmappingrule) | **POST** /mapping-rules/search | Search mapping rules
*MappingRuleApi* | [**updateMappingRule**](docs/Api/MappingRuleApi.md#updatemappingrule) | **PUT** /mapping-rules/{mappingRuleId} | Update mapping rule
*MessageApi* | [**correlateMessage**](docs/Api/MessageApi.md#correlatemessage) | **POST** /messages/correlation | Correlate message
*MessageApi* | [**publishMessage**](docs/Api/MessageApi.md#publishmessage) | **POST** /messages/publication | Publish message
*MessageSubscriptionApi* | [**searchCorrelatedMessageSubscriptions**](docs/Api/MessageSubscriptionApi.md#searchcorrelatedmessagesubscriptions) | **POST** /correlated-message-subscriptions/search | Search correlated message subscriptions
*MessageSubscriptionApi* | [**searchMessageSubscriptions**](docs/Api/MessageSubscriptionApi.md#searchmessagesubscriptions) | **POST** /message-subscriptions/search | Search message subscriptions
*ProcessDefinitionApi* | [**getProcessDefinition**](docs/Api/ProcessDefinitionApi.md#getprocessdefinition) | **GET** /process-definitions/{processDefinitionKey} | Get process definition
*ProcessDefinitionApi* | [**getProcessDefinitionInstanceStatistics**](docs/Api/ProcessDefinitionApi.md#getprocessdefinitioninstancestatistics) | **POST** /process-definitions/statistics/process-instances | Get process instance statistics
*ProcessDefinitionApi* | [**getProcessDefinitionInstanceVersionStatistics**](docs/Api/ProcessDefinitionApi.md#getprocessdefinitioninstanceversionstatistics) | **POST** /process-definitions/statistics/process-instances-by-version | Get process instance statistics by version
*ProcessDefinitionApi* | [**getProcessDefinitionMessageSubscriptionStatistics**](docs/Api/ProcessDefinitionApi.md#getprocessdefinitionmessagesubscriptionstatistics) | **POST** /process-definitions/statistics/message-subscriptions | Get message subscription statistics
*ProcessDefinitionApi* | [**getProcessDefinitionStatistics**](docs/Api/ProcessDefinitionApi.md#getprocessdefinitionstatistics) | **POST** /process-definitions/{processDefinitionKey}/statistics/element-instances | Get process definition statistics
*ProcessDefinitionApi* | [**getProcessDefinitionXML**](docs/Api/ProcessDefinitionApi.md#getprocessdefinitionxml) | **GET** /process-definitions/{processDefinitionKey}/xml | Get process definition XML
*ProcessDefinitionApi* | [**getStartProcessForm**](docs/Api/ProcessDefinitionApi.md#getstartprocessform) | **GET** /process-definitions/{processDefinitionKey}/form | Get process start form
*ProcessDefinitionApi* | [**searchProcessDefinitionVariableNames**](docs/Api/ProcessDefinitionApi.md#searchprocessdefinitionvariablenames) | **POST** /process-definitions/{processDefinitionKey}/variable-names/search | Search process definition variable names
*ProcessDefinitionApi* | [**searchProcessDefinitions**](docs/Api/ProcessDefinitionApi.md#searchprocessdefinitions) | **POST** /process-definitions/search | Search process definitions
*ProcessInstanceApi* | [**assignProcessInstanceBusinessId**](docs/Api/ProcessInstanceApi.md#assignprocessinstancebusinessid) | **POST** /process-instances/{processInstanceKey}/business-id-assignment | Assign business id to process instance
*ProcessInstanceApi* | [**cancelProcessInstance**](docs/Api/ProcessInstanceApi.md#cancelprocessinstance) | **POST** /process-instances/{processInstanceKey}/cancellation | Cancel process instance
*ProcessInstanceApi* | [**cancelProcessInstancesBatchOperation**](docs/Api/ProcessInstanceApi.md#cancelprocessinstancesbatchoperation) | **POST** /process-instances/cancellation | Cancel process instances (batch)
*ProcessInstanceApi* | [**createProcessInstance**](docs/Api/ProcessInstanceApi.md#createprocessinstance) | **POST** /process-instances | Create process instance
*ProcessInstanceApi* | [**deleteProcessInstance**](docs/Api/ProcessInstanceApi.md#deleteprocessinstance) | **POST** /process-instances/{processInstanceKey}/deletion | Delete process instance
*ProcessInstanceApi* | [**deleteProcessInstancesBatchOperation**](docs/Api/ProcessInstanceApi.md#deleteprocessinstancesbatchoperation) | **POST** /process-instances/deletion | Delete process instances (batch)
*ProcessInstanceApi* | [**getProcessInstance**](docs/Api/ProcessInstanceApi.md#getprocessinstance) | **GET** /process-instances/{processInstanceKey} | Get process instance
*ProcessInstanceApi* | [**getProcessInstanceCallHierarchy**](docs/Api/ProcessInstanceApi.md#getprocessinstancecallhierarchy) | **GET** /process-instances/{processInstanceKey}/call-hierarchy | Get call hierarchy
*ProcessInstanceApi* | [**getProcessInstanceSequenceFlows**](docs/Api/ProcessInstanceApi.md#getprocessinstancesequenceflows) | **GET** /process-instances/{processInstanceKey}/sequence-flows | Get sequence flows
*ProcessInstanceApi* | [**getProcessInstanceStatistics**](docs/Api/ProcessInstanceApi.md#getprocessinstancestatistics) | **GET** /process-instances/{processInstanceKey}/statistics/element-instances | Get element instance statistics
*ProcessInstanceApi* | [**getProcessInstanceWaitStateStatistics**](docs/Api/ProcessInstanceApi.md#getprocessinstancewaitstatestatistics) | **GET** /process-instances/{processInstanceKey}/statistics/wait-states | Get wait state statistics
*ProcessInstanceApi* | [**migrateProcessInstance**](docs/Api/ProcessInstanceApi.md#migrateprocessinstance) | **POST** /process-instances/{processInstanceKey}/migration | Migrate process instance
*ProcessInstanceApi* | [**migrateProcessInstancesBatchOperation**](docs/Api/ProcessInstanceApi.md#migrateprocessinstancesbatchoperation) | **POST** /process-instances/migration | Migrate process instances (batch)
*ProcessInstanceApi* | [**modifyProcessInstance**](docs/Api/ProcessInstanceApi.md#modifyprocessinstance) | **POST** /process-instances/{processInstanceKey}/modification | Modify process instance
*ProcessInstanceApi* | [**modifyProcessInstancesBatchOperation**](docs/Api/ProcessInstanceApi.md#modifyprocessinstancesbatchoperation) | **POST** /process-instances/modification | Modify process instances (batch)
*ProcessInstanceApi* | [**resolveIncidentsBatchOperation**](docs/Api/ProcessInstanceApi.md#resolveincidentsbatchoperation) | **POST** /process-instances/incident-resolution | Resolve related incidents (batch)
*ProcessInstanceApi* | [**resolveProcessInstanceIncidents**](docs/Api/ProcessInstanceApi.md#resolveprocessinstanceincidents) | **POST** /process-instances/{processInstanceKey}/incident-resolution | Resolve related incidents
*ProcessInstanceApi* | [**resumeProcessInstance**](docs/Api/ProcessInstanceApi.md#resumeprocessinstance) | **POST** /process-instances/{processInstanceKey}/resumption | Resume process instance
*ProcessInstanceApi* | [**resumeProcessInstancesBatchOperation**](docs/Api/ProcessInstanceApi.md#resumeprocessinstancesbatchoperation) | **POST** /process-instances/resumption | Resume process instances (batch)
*ProcessInstanceApi* | [**searchProcessInstanceIncidents**](docs/Api/ProcessInstanceApi.md#searchprocessinstanceincidents) | **POST** /process-instances/{processInstanceKey}/incidents/search | Search related incidents
*ProcessInstanceApi* | [**searchProcessInstances**](docs/Api/ProcessInstanceApi.md#searchprocessinstances) | **POST** /process-instances/search | Search process instances
*ProcessInstanceApi* | [**suspendProcessInstance**](docs/Api/ProcessInstanceApi.md#suspendprocessinstance) | **POST** /process-instances/{processInstanceKey}/suspension | Suspend process instance
*ProcessInstanceApi* | [**suspendProcessInstancesBatchOperation**](docs/Api/ProcessInstanceApi.md#suspendprocessinstancesbatchoperation) | **POST** /process-instances/suspension | Suspend process instances (batch)
*RecoveryApi* | [**changeClusterMode**](docs/Api/RecoveryApi.md#changeclustermode) | **PATCH** /mode | Change cluster mode
*RecoveryApi* | [**changeClusterModeAsClusterAdmin**](docs/Api/RecoveryApi.md#changeclustermodeasclusteradmin) | **PATCH** /cluster/v2/mode | Change the cluster mode of one or every physical tenant
*RecoveryApi* | [**getRestoreStatus**](docs/Api/RecoveryApi.md#getrestorestatus) | **GET** /restore | Get the status of the restore that is currently in progress
*RecoveryApi* | [**restore**](docs/Api/RecoveryApi.md#restore) | **POST** /restore | Restore from a backup
*RecoveryApi* | [**restoreAsClusterAdmin**](docs/Api/RecoveryApi.md#restoreasclusteradmin) | **POST** /cluster/v2/restore | Restore one or every physical tenant from a backup
*ResourceApi* | [**createDeployment**](docs/Api/ResourceApi.md#createdeployment) | **POST** /deployments | Deploy resources
*ResourceApi* | [**deleteResource**](docs/Api/ResourceApi.md#deleteresource) | **POST** /resources/{resourceKey}/deletion | Delete resource
*ResourceApi* | [**getResource**](docs/Api/ResourceApi.md#getresource) | **GET** /resources/{resourceKey} | Get resource
*ResourceApi* | [**getResourceContent**](docs/Api/ResourceApi.md#getresourcecontent) | **GET** /resources/{resourceKey}/content | Get RPA resource content (deprecated)
*ResourceApi* | [**getResourceContentBinary**](docs/Api/ResourceApi.md#getresourcecontentbinary) | **GET** /resources/{resourceKey}/content/binary | Get resource content as binary
*ResourceApi* | [**searchResources**](docs/Api/ResourceApi.md#searchresources) | **POST** /resources/search | Search resources
*RoleApi* | [**assignRoleToClient**](docs/Api/RoleApi.md#assignroletoclient) | **PUT** /roles/{roleId}/clients/{clientId} | Assign a role to a client
*RoleApi* | [**assignRoleToGroup**](docs/Api/RoleApi.md#assignroletogroup) | **PUT** /roles/{roleId}/groups/{groupId} | Assign a role to a group
*RoleApi* | [**assignRoleToMappingRule**](docs/Api/RoleApi.md#assignroletomappingrule) | **PUT** /roles/{roleId}/mapping-rules/{mappingRuleId} | Assign a role to a mapping rule
*RoleApi* | [**assignRoleToUser**](docs/Api/RoleApi.md#assignroletouser) | **PUT** /roles/{roleId}/users/{username} | Assign a role to a user
*RoleApi* | [**createRole**](docs/Api/RoleApi.md#createrole) | **POST** /roles | Create role
*RoleApi* | [**deleteRole**](docs/Api/RoleApi.md#deleterole) | **DELETE** /roles/{roleId} | Delete role
*RoleApi* | [**getRole**](docs/Api/RoleApi.md#getrole) | **GET** /roles/{roleId} | Get role
*RoleApi* | [**searchClientsForRole**](docs/Api/RoleApi.md#searchclientsforrole) | **POST** /roles/{roleId}/clients/search | Search role clients
*RoleApi* | [**searchGroupsForRole**](docs/Api/RoleApi.md#searchgroupsforrole) | **POST** /roles/{roleId}/groups/search | Search role groups
*RoleApi* | [**searchMappingRulesForRole**](docs/Api/RoleApi.md#searchmappingrulesforrole) | **POST** /roles/{roleId}/mapping-rules/search | Search role mapping rules
*RoleApi* | [**searchRoles**](docs/Api/RoleApi.md#searchroles) | **POST** /roles/search | Search roles
*RoleApi* | [**searchUsersForRole**](docs/Api/RoleApi.md#searchusersforrole) | **POST** /roles/{roleId}/users/search | Search role users
*RoleApi* | [**unassignRoleFromClient**](docs/Api/RoleApi.md#unassignrolefromclient) | **DELETE** /roles/{roleId}/clients/{clientId} | Unassign a role from a client
*RoleApi* | [**unassignRoleFromGroup**](docs/Api/RoleApi.md#unassignrolefromgroup) | **DELETE** /roles/{roleId}/groups/{groupId} | Unassign a role from a group
*RoleApi* | [**unassignRoleFromMappingRule**](docs/Api/RoleApi.md#unassignrolefrommappingrule) | **DELETE** /roles/{roleId}/mapping-rules/{mappingRuleId} | Unassign a role from a mapping rule
*RoleApi* | [**unassignRoleFromUser**](docs/Api/RoleApi.md#unassignrolefromuser) | **DELETE** /roles/{roleId}/users/{username} | Unassign a role from a user
*RoleApi* | [**updateRole**](docs/Api/RoleApi.md#updaterole) | **PUT** /roles/{roleId} | Update role
*SecretApi* | [**listSecrets**](docs/Api/SecretApi.md#listsecrets) | **POST** /secrets/list | List secrets (alpha)
*SecretApi* | [**resolveSecrets**](docs/Api/SecretApi.md#resolvesecrets) | **POST** /secrets/resolve | Resolve secrets (alpha)
*SetupApi* | [**createAdminUser**](docs/Api/SetupApi.md#createadminuser) | **POST** /setup/user | Create admin user
*SignalApi* | [**broadcastSignal**](docs/Api/SignalApi.md#broadcastsignal) | **POST** /signals/broadcast | Broadcast signal
*SystemApi* | [**getSystemConfiguration**](docs/Api/SystemApi.md#getsystemconfiguration) | **GET** /system/configuration | System configuration (alpha)
*SystemApi* | [**getUsageMetrics**](docs/Api/SystemApi.md#getusagemetrics) | **GET** /system/usage-metrics | Get usage metrics
*TenantApi* | [**assignClientToTenant**](docs/Api/TenantApi.md#assignclienttotenant) | **PUT** /tenants/{tenantId}/clients/{clientId} | Assign a client to a tenant
*TenantApi* | [**assignGroupToTenant**](docs/Api/TenantApi.md#assigngrouptotenant) | **PUT** /tenants/{tenantId}/groups/{groupId} | Assign a group to a tenant
*TenantApi* | [**assignMappingRuleToTenant**](docs/Api/TenantApi.md#assignmappingruletotenant) | **PUT** /tenants/{tenantId}/mapping-rules/{mappingRuleId} | Assign a mapping rule to a tenant
*TenantApi* | [**assignRoleToTenant**](docs/Api/TenantApi.md#assignroletotenant) | **PUT** /tenants/{tenantId}/roles/{roleId} | Assign a role to a tenant
*TenantApi* | [**assignUserToTenant**](docs/Api/TenantApi.md#assignusertotenant) | **PUT** /tenants/{tenantId}/users/{username} | Assign a user to a tenant
*TenantApi* | [**createTenant**](docs/Api/TenantApi.md#createtenant) | **POST** /tenants | Create tenant
*TenantApi* | [**deleteTenant**](docs/Api/TenantApi.md#deletetenant) | **DELETE** /tenants/{tenantId} | Delete tenant
*TenantApi* | [**getTenant**](docs/Api/TenantApi.md#gettenant) | **GET** /tenants/{tenantId} | Get tenant
*TenantApi* | [**searchClientsForTenant**](docs/Api/TenantApi.md#searchclientsfortenant) | **POST** /tenants/{tenantId}/clients/search | Search clients for tenant
*TenantApi* | [**searchGroupIdsForTenant**](docs/Api/TenantApi.md#searchgroupidsfortenant) | **POST** /tenants/{tenantId}/groups/search | Search groups for tenant
*TenantApi* | [**searchMappingRulesForTenant**](docs/Api/TenantApi.md#searchmappingrulesfortenant) | **POST** /tenants/{tenantId}/mapping-rules/search | Search mapping rules for tenant
*TenantApi* | [**searchRolesForTenant**](docs/Api/TenantApi.md#searchrolesfortenant) | **POST** /tenants/{tenantId}/roles/search | Search roles for tenant
*TenantApi* | [**searchTenants**](docs/Api/TenantApi.md#searchtenants) | **POST** /tenants/search | Search tenants
*TenantApi* | [**searchUsersForTenant**](docs/Api/TenantApi.md#searchusersfortenant) | **POST** /tenants/{tenantId}/users/search | Search users for tenant
*TenantApi* | [**unassignClientFromTenant**](docs/Api/TenantApi.md#unassignclientfromtenant) | **DELETE** /tenants/{tenantId}/clients/{clientId} | Unassign a client from a tenant
*TenantApi* | [**unassignGroupFromTenant**](docs/Api/TenantApi.md#unassigngroupfromtenant) | **DELETE** /tenants/{tenantId}/groups/{groupId} | Unassign a group from a tenant
*TenantApi* | [**unassignMappingRuleFromTenant**](docs/Api/TenantApi.md#unassignmappingrulefromtenant) | **DELETE** /tenants/{tenantId}/mapping-rules/{mappingRuleId} | Unassign a mapping rule from a tenant
*TenantApi* | [**unassignRoleFromTenant**](docs/Api/TenantApi.md#unassignrolefromtenant) | **DELETE** /tenants/{tenantId}/roles/{roleId} | Unassign a role from a tenant
*TenantApi* | [**unassignUserFromTenant**](docs/Api/TenantApi.md#unassignuserfromtenant) | **DELETE** /tenants/{tenantId}/users/{username} | Unassign a user from a tenant
*TenantApi* | [**updateTenant**](docs/Api/TenantApi.md#updatetenant) | **PUT** /tenants/{tenantId} | Update tenant
*UserApi* | [**createUser**](docs/Api/UserApi.md#createuser) | **POST** /users | Create user
*UserApi* | [**deleteUser**](docs/Api/UserApi.md#deleteuser) | **DELETE** /users/{username} | Delete user
*UserApi* | [**getUser**](docs/Api/UserApi.md#getuser) | **GET** /users/{username} | Get user
*UserApi* | [**searchUsers**](docs/Api/UserApi.md#searchusers) | **POST** /users/search | Search users
*UserApi* | [**updateUser**](docs/Api/UserApi.md#updateuser) | **PUT** /users/{username} | Update user
*UserTaskApi* | [**assignUserTask**](docs/Api/UserTaskApi.md#assignusertask) | **POST** /user-tasks/{userTaskKey}/assignment | Assign user task
*UserTaskApi* | [**completeUserTask**](docs/Api/UserTaskApi.md#completeusertask) | **POST** /user-tasks/{userTaskKey}/completion | Complete user task
*UserTaskApi* | [**getUserTask**](docs/Api/UserTaskApi.md#getusertask) | **GET** /user-tasks/{userTaskKey} | Get user task
*UserTaskApi* | [**getUserTaskForm**](docs/Api/UserTaskApi.md#getusertaskform) | **GET** /user-tasks/{userTaskKey}/form | Get user task form
*UserTaskApi* | [**searchUserTaskAuditLogs**](docs/Api/UserTaskApi.md#searchusertaskauditlogs) | **POST** /user-tasks/{userTaskKey}/audit-logs/search | Search user task audit logs
*UserTaskApi* | [**searchUserTaskEffectiveVariables**](docs/Api/UserTaskApi.md#searchusertaskeffectivevariables) | **POST** /user-tasks/{userTaskKey}/effective-variables/search | Search user task effective variables
*UserTaskApi* | [**searchUserTaskVariables**](docs/Api/UserTaskApi.md#searchusertaskvariables) | **POST** /user-tasks/{userTaskKey}/variables/search | Search user task variables
*UserTaskApi* | [**searchUserTasks**](docs/Api/UserTaskApi.md#searchusertasks) | **POST** /user-tasks/search | Search user tasks
*UserTaskApi* | [**unassignUserTask**](docs/Api/UserTaskApi.md#unassignusertask) | **DELETE** /user-tasks/{userTaskKey}/assignee | Unassign user task
*UserTaskApi* | [**updateUserTask**](docs/Api/UserTaskApi.md#updateusertask) | **PATCH** /user-tasks/{userTaskKey} | Update user task
*VariableApi* | [**getVariable**](docs/Api/VariableApi.md#getvariable) | **GET** /variables/{variableKey} | Get variable
*VariableApi* | [**searchVariables**](docs/Api/VariableApi.md#searchvariables) | **POST** /variables/search | Search variables

## Models

- [ActivatedJobResult](docs/Model/ActivatedJobResult.md)
- [AdHocSubProcessActivateActivitiesInstruction](docs/Model/AdHocSubProcessActivateActivitiesInstruction.md)
- [AdHocSubProcessActivateActivityReference](docs/Model/AdHocSubProcessActivateActivityReference.md)
- [AdvancedActorTypeFilter](docs/Model/AdvancedActorTypeFilter.md)
- [AdvancedAgentDefinitionKeyFilter](docs/Model/AdvancedAgentDefinitionKeyFilter.md)
- [AdvancedAgentDefinitionTypeFilter](docs/Model/AdvancedAgentDefinitionTypeFilter.md)
- [AdvancedAgentHistoryItemKeyFilter](docs/Model/AdvancedAgentHistoryItemKeyFilter.md)
- [AdvancedAgentInstanceHistoryCommitStatusFilter](docs/Model/AdvancedAgentInstanceHistoryCommitStatusFilter.md)
- [AdvancedAgentInstanceHistoryRoleFilter](docs/Model/AdvancedAgentInstanceHistoryRoleFilter.md)
- [AdvancedAgentInstanceKeyFilter](docs/Model/AdvancedAgentInstanceKeyFilter.md)
- [AdvancedAgentInstanceStatusFilter](docs/Model/AdvancedAgentInstanceStatusFilter.md)
- [AdvancedAuditLogEntityKeyFilter](docs/Model/AdvancedAuditLogEntityKeyFilter.md)
- [AdvancedAuditLogKeyFilter](docs/Model/AdvancedAuditLogKeyFilter.md)
- [AdvancedBatchOperationItemStateFilter](docs/Model/AdvancedBatchOperationItemStateFilter.md)
- [AdvancedBatchOperationStateFilter](docs/Model/AdvancedBatchOperationStateFilter.md)
- [AdvancedBatchOperationTypeFilter](docs/Model/AdvancedBatchOperationTypeFilter.md)
- [AdvancedCategoryFilter](docs/Model/AdvancedCategoryFilter.md)
- [AdvancedClusterVariableKindFilter](docs/Model/AdvancedClusterVariableKindFilter.md)
- [AdvancedClusterVariableScopeFilter](docs/Model/AdvancedClusterVariableScopeFilter.md)
- [AdvancedDateTimeFilter](docs/Model/AdvancedDateTimeFilter.md)
- [AdvancedDecisionDefinitionKeyFilter](docs/Model/AdvancedDecisionDefinitionKeyFilter.md)
- [AdvancedDecisionEvaluationInstanceKeyFilter](docs/Model/AdvancedDecisionEvaluationInstanceKeyFilter.md)
- [AdvancedDecisionEvaluationKeyFilter](docs/Model/AdvancedDecisionEvaluationKeyFilter.md)
- [AdvancedDecisionInstanceStateFilter](docs/Model/AdvancedDecisionInstanceStateFilter.md)
- [AdvancedDecisionRequirementsKeyFilter](docs/Model/AdvancedDecisionRequirementsKeyFilter.md)
- [AdvancedDeploymentKeyFilter](docs/Model/AdvancedDeploymentKeyFilter.md)
- [AdvancedElementIdFilter](docs/Model/AdvancedElementIdFilter.md)
- [AdvancedElementInstanceKeyFilter](docs/Model/AdvancedElementInstanceKeyFilter.md)
- [AdvancedElementInstanceStateFilter](docs/Model/AdvancedElementInstanceStateFilter.md)
- [AdvancedEntityTypeFilter](docs/Model/AdvancedEntityTypeFilter.md)
- [AdvancedFormKeyFilter](docs/Model/AdvancedFormKeyFilter.md)
- [AdvancedGlobalListenerSourceFilter](docs/Model/AdvancedGlobalListenerSourceFilter.md)
- [AdvancedGlobalTaskListenerEventTypeFilter](docs/Model/AdvancedGlobalTaskListenerEventTypeFilter.md)
- [AdvancedIncidentErrorTypeFilter](docs/Model/AdvancedIncidentErrorTypeFilter.md)
- [AdvancedIncidentStateFilter](docs/Model/AdvancedIncidentStateFilter.md)
- [AdvancedIntegerFilter](docs/Model/AdvancedIntegerFilter.md)
- [AdvancedJobKeyFilter](docs/Model/AdvancedJobKeyFilter.md)
- [AdvancedJobKindFilter](docs/Model/AdvancedJobKindFilter.md)
- [AdvancedJobListenerEventTypeFilter](docs/Model/AdvancedJobListenerEventTypeFilter.md)
- [AdvancedJobStateFilter](docs/Model/AdvancedJobStateFilter.md)
- [AdvancedMessageSubscriptionKeyFilter](docs/Model/AdvancedMessageSubscriptionKeyFilter.md)
- [AdvancedMessageSubscriptionStateFilter](docs/Model/AdvancedMessageSubscriptionStateFilter.md)
- [AdvancedMessageSubscriptionTypeFilter](docs/Model/AdvancedMessageSubscriptionTypeFilter.md)
- [AdvancedMetadataValueFilter](docs/Model/AdvancedMetadataValueFilter.md)
- [AdvancedMetadataValueFilterEq](docs/Model/AdvancedMetadataValueFilterEq.md)
- [AdvancedMetadataValueFilterNeq](docs/Model/AdvancedMetadataValueFilterNeq.md)
- [AdvancedOperationTypeFilter](docs/Model/AdvancedOperationTypeFilter.md)
- [AdvancedProcessDefinitionIdFilter](docs/Model/AdvancedProcessDefinitionIdFilter.md)
- [AdvancedProcessDefinitionKeyFilter](docs/Model/AdvancedProcessDefinitionKeyFilter.md)
- [AdvancedProcessInstanceKeyFilter](docs/Model/AdvancedProcessInstanceKeyFilter.md)
- [AdvancedProcessInstanceStateFilter](docs/Model/AdvancedProcessInstanceStateFilter.md)
- [AdvancedResourceKeyFilter](docs/Model/AdvancedResourceKeyFilter.md)
- [AdvancedResultFilter](docs/Model/AdvancedResultFilter.md)
- [AdvancedScopeKeyFilter](docs/Model/AdvancedScopeKeyFilter.md)
- [AdvancedStringFilter](docs/Model/AdvancedStringFilter.md)
- [AdvancedUserTaskStateFilter](docs/Model/AdvancedUserTaskStateFilter.md)
- [AdvancedVariableKeyFilter](docs/Model/AdvancedVariableKeyFilter.md)
- [AdvancedWaitStateElementTypeFilter](docs/Model/AdvancedWaitStateElementTypeFilter.md)
- [AdvancedWaitStateTypeFilter](docs/Model/AdvancedWaitStateTypeFilter.md)
- [AgentDefinitionFilter](docs/Model/AgentDefinitionFilter.md)
- [AgentDefinitionKeyExactMatch](docs/Model/AgentDefinitionKeyExactMatch.md)
- [AgentDefinitionKeyFilterProperty](docs/Model/AgentDefinitionKeyFilterProperty.md)
- [AgentDefinitionResult](docs/Model/AgentDefinitionResult.md)
- [AgentDefinitionSearchQuery](docs/Model/AgentDefinitionSearchQuery.md)
- [AgentDefinitionSearchQueryResult](docs/Model/AgentDefinitionSearchQueryResult.md)
- [AgentDefinitionSearchQuerySortRequest](docs/Model/AgentDefinitionSearchQuerySortRequest.md)
- [AgentDefinitionTypeEnum](docs/Model/AgentDefinitionTypeEnum.md)
- [AgentDefinitionTypeExactMatch](docs/Model/AgentDefinitionTypeExactMatch.md)
- [AgentDefinitionTypeFilterProperty](docs/Model/AgentDefinitionTypeFilterProperty.md)
- [AgentHistoryItemKeyExactMatch](docs/Model/AgentHistoryItemKeyExactMatch.md)
- [AgentHistoryItemKeyFilterProperty](docs/Model/AgentHistoryItemKeyFilterProperty.md)
- [AgentInstanceCreatedHistoryItem](docs/Model/AgentInstanceCreatedHistoryItem.md)
- [AgentInstanceCreationRequest](docs/Model/AgentInstanceCreationRequest.md)
- [AgentInstanceCreationResult](docs/Model/AgentInstanceCreationResult.md)
- [AgentInstanceDefinitionResult](docs/Model/AgentInstanceDefinitionResult.md)
- [AgentInstanceDocumentContent](docs/Model/AgentInstanceDocumentContent.md)
- [AgentInstanceFilter](docs/Model/AgentInstanceFilter.md)
- [AgentInstanceHistoryCommitStatusEnum](docs/Model/AgentInstanceHistoryCommitStatusEnum.md)
- [AgentInstanceHistoryCommitStatusExactMatch](docs/Model/AgentInstanceHistoryCommitStatusExactMatch.md)
- [AgentInstanceHistoryCommitStatusFilterProperty](docs/Model/AgentInstanceHistoryCommitStatusFilterProperty.md)
- [AgentInstanceHistoryFilter](docs/Model/AgentInstanceHistoryFilter.md)
- [AgentInstanceHistoryItem](docs/Model/AgentInstanceHistoryItem.md)
- [AgentInstanceHistoryItemMetrics](docs/Model/AgentInstanceHistoryItemMetrics.md)
- [AgentInstanceHistoryItemMetricsRequest](docs/Model/AgentInstanceHistoryItemMetricsRequest.md)
- [AgentInstanceHistoryItemResult](docs/Model/AgentInstanceHistoryItemResult.md)
- [AgentInstanceHistoryRoleEnum](docs/Model/AgentInstanceHistoryRoleEnum.md)
- [AgentInstanceHistoryRoleExactMatch](docs/Model/AgentInstanceHistoryRoleExactMatch.md)
- [AgentInstanceHistoryRoleFilterProperty](docs/Model/AgentInstanceHistoryRoleFilterProperty.md)
- [AgentInstanceHistorySearchQuery](docs/Model/AgentInstanceHistorySearchQuery.md)
- [AgentInstanceHistorySearchQueryResult](docs/Model/AgentInstanceHistorySearchQueryResult.md)
- [AgentInstanceHistorySearchQuerySortRequest](docs/Model/AgentInstanceHistorySearchQuerySortRequest.md)
- [AgentInstanceKeyExactMatch](docs/Model/AgentInstanceKeyExactMatch.md)
- [AgentInstanceKeyFilterProperty](docs/Model/AgentInstanceKeyFilterProperty.md)
- [AgentInstanceLimits](docs/Model/AgentInstanceLimits.md)
- [AgentInstanceMessageContent](docs/Model/AgentInstanceMessageContent.md)
- [AgentInstanceMessageContentTypeEnum](docs/Model/AgentInstanceMessageContentTypeEnum.md)
- [AgentInstanceMetrics](docs/Model/AgentInstanceMetrics.md)
- [AgentInstanceObjectContent](docs/Model/AgentInstanceObjectContent.md)
- [AgentInstanceResult](docs/Model/AgentInstanceResult.md)
- [AgentInstanceSearchQuery](docs/Model/AgentInstanceSearchQuery.md)
- [AgentInstanceSearchQueryResult](docs/Model/AgentInstanceSearchQueryResult.md)
- [AgentInstanceSearchQuerySortRequest](docs/Model/AgentInstanceSearchQuerySortRequest.md)
- [AgentInstanceStatusEnum](docs/Model/AgentInstanceStatusEnum.md)
- [AgentInstanceStatusExactMatch](docs/Model/AgentInstanceStatusExactMatch.md)
- [AgentInstanceStatusFilterProperty](docs/Model/AgentInstanceStatusFilterProperty.md)
- [AgentInstanceTextContent](docs/Model/AgentInstanceTextContent.md)
- [AgentInstanceToolCall](docs/Model/AgentInstanceToolCall.md)
- [AgentInstanceUpdateRequest](docs/Model/AgentInstanceUpdateRequest.md)
- [AgentInstanceUpdateResult](docs/Model/AgentInstanceUpdateResult.md)
- [AgentInstanceUpdateStatusEnum](docs/Model/AgentInstanceUpdateStatusEnum.md)
- [AgentTool](docs/Model/AgentTool.md)
- [AncestorScopeInstruction](docs/Model/AncestorScopeInstruction.md)
- [AuditLogActorTypeEnum](docs/Model/AuditLogActorTypeEnum.md)
- [AuditLogActorTypeExactMatch](docs/Model/AuditLogActorTypeExactMatch.md)
- [AuditLogActorTypeFilterProperty](docs/Model/AuditLogActorTypeFilterProperty.md)
- [AuditLogCategoryEnum](docs/Model/AuditLogCategoryEnum.md)
- [AuditLogEntityKeyFilterProperty](docs/Model/AuditLogEntityKeyFilterProperty.md)
- [AuditLogEntityTypeEnum](docs/Model/AuditLogEntityTypeEnum.md)
- [AuditLogFilter](docs/Model/AuditLogFilter.md)
- [AuditLogKeyExactMatch](docs/Model/AuditLogKeyExactMatch.md)
- [AuditLogKeyFilterProperty](docs/Model/AuditLogKeyFilterProperty.md)
- [AuditLogOperationTypeEnum](docs/Model/AuditLogOperationTypeEnum.md)
- [AuditLogResult](docs/Model/AuditLogResult.md)
- [AuditLogResultEnum](docs/Model/AuditLogResultEnum.md)
- [AuditLogResultExactMatch](docs/Model/AuditLogResultExactMatch.md)
- [AuditLogResultFilterProperty](docs/Model/AuditLogResultFilterProperty.md)
- [AuditLogSearchQueryRequest](docs/Model/AuditLogSearchQueryRequest.md)
- [AuditLogSearchQueryResult](docs/Model/AuditLogSearchQueryResult.md)
- [AuditLogSearchQuerySortRequest](docs/Model/AuditLogSearchQuerySortRequest.md)
- [AuthenticationConfigurationResponse](docs/Model/AuthenticationConfigurationResponse.md)
- [AuthorizationCreateResult](docs/Model/AuthorizationCreateResult.md)
- [AuthorizationFilter](docs/Model/AuthorizationFilter.md)
- [AuthorizationIdBasedRequest](docs/Model/AuthorizationIdBasedRequest.md)
- [AuthorizationPropertyBasedRequest](docs/Model/AuthorizationPropertyBasedRequest.md)
- [AuthorizationRequest](docs/Model/AuthorizationRequest.md)
- [AuthorizationResult](docs/Model/AuthorizationResult.md)
- [AuthorizationSearchQuery](docs/Model/AuthorizationSearchQuery.md)
- [AuthorizationSearchQuerySortRequest](docs/Model/AuthorizationSearchQuerySortRequest.md)
- [AuthorizationSearchResult](docs/Model/AuthorizationSearchResult.md)
- [BackupInfo](docs/Model/BackupInfo.md)
- [BackupType](docs/Model/BackupType.md)
- [BaseProcessInstanceFilterFields](docs/Model/BaseProcessInstanceFilterFields.md)
- [BaseWaitStateDetails](docs/Model/BaseWaitStateDetails.md)
- [BasicStringFilter](docs/Model/BasicStringFilter.md)
- [BasicStringFilterProperty](docs/Model/BasicStringFilterProperty.md)
- [BatchOperationCreatedResult](docs/Model/BatchOperationCreatedResult.md)
- [BatchOperationError](docs/Model/BatchOperationError.md)
- [BatchOperationFilter](docs/Model/BatchOperationFilter.md)
- [BatchOperationItemFilter](docs/Model/BatchOperationItemFilter.md)
- [BatchOperationItemResponse](docs/Model/BatchOperationItemResponse.md)
- [BatchOperationItemSearchQuery](docs/Model/BatchOperationItemSearchQuery.md)
- [BatchOperationItemSearchQueryResult](docs/Model/BatchOperationItemSearchQueryResult.md)
- [BatchOperationItemSearchQuerySortRequest](docs/Model/BatchOperationItemSearchQuerySortRequest.md)
- [BatchOperationItemStateEnum](docs/Model/BatchOperationItemStateEnum.md)
- [BatchOperationItemStateExactMatch](docs/Model/BatchOperationItemStateExactMatch.md)
- [BatchOperationItemStateFilterProperty](docs/Model/BatchOperationItemStateFilterProperty.md)
- [BatchOperationResponse](docs/Model/BatchOperationResponse.md)
- [BatchOperationSearchQuery](docs/Model/BatchOperationSearchQuery.md)
- [BatchOperationSearchQueryResult](docs/Model/BatchOperationSearchQueryResult.md)
- [BatchOperationSearchQuerySortRequest](docs/Model/BatchOperationSearchQuerySortRequest.md)
- [BatchOperationStateEnum](docs/Model/BatchOperationStateEnum.md)
- [BatchOperationStateExactMatch](docs/Model/BatchOperationStateExactMatch.md)
- [BatchOperationStateFilterProperty](docs/Model/BatchOperationStateFilterProperty.md)
- [BatchOperationTypeEnum](docs/Model/BatchOperationTypeEnum.md)
- [BatchOperationTypeExactMatch](docs/Model/BatchOperationTypeExactMatch.md)
- [BatchOperationTypeFilterProperty](docs/Model/BatchOperationTypeFilterProperty.md)
- [BrokerInfo](docs/Model/BrokerInfo.md)
- [CamundaUserResult](docs/Model/CamundaUserResult.md)
- [CancelProcessInstanceRequest](docs/Model/CancelProcessInstanceRequest.md)
- [CategoryExactMatch](docs/Model/CategoryExactMatch.md)
- [CategoryFilterProperty](docs/Model/CategoryFilterProperty.md)
- [Changeset](docs/Model/Changeset.md)
- [CheckpointType](docs/Model/CheckpointType.md)
- [ClockPinRequest](docs/Model/ClockPinRequest.md)
- [CloudConfigurationResponse](docs/Model/CloudConfigurationResponse.md)
- [CloudStage](docs/Model/CloudStage.md)
- [ClusterBalanceResponse](docs/Model/ClusterBalanceResponse.md)
- [ClusterBrokerInfo](docs/Model/ClusterBrokerInfo.md)
- [ClusterCompletedRebalance](docs/Model/ClusterCompletedRebalance.md)
- [ClusterHistoryBackupInfo](docs/Model/ClusterHistoryBackupInfo.md)
- [ClusterHistoryBackupTakeResult](docs/Model/ClusterHistoryBackupTakeResult.md)
- [ClusterHistoryBackupTenantInfo](docs/Model/ClusterHistoryBackupTenantInfo.md)
- [ClusterHistoryBackupTenantState](docs/Model/ClusterHistoryBackupTenantState.md)
- [ClusterModeChangeOperation](docs/Model/ClusterModeChangeOperation.md)
- [ClusterModeChangePlannedChange](docs/Model/ClusterModeChangePlannedChange.md)
- [ClusterModeChangeResponse](docs/Model/ClusterModeChangeResponse.md)
- [ClusterRebalance](docs/Model/ClusterRebalance.md)
- [ClusterRebalanceOperationPartition](docs/Model/ClusterRebalanceOperationPartition.md)
- [ClusterRebalancePartition](docs/Model/ClusterRebalancePartition.md)
- [ClusterRebalanceRequest](docs/Model/ClusterRebalanceRequest.md)
- [ClusterRestoreAwaitModeChangeOperation](docs/Model/ClusterRestoreAwaitModeChangeOperation.md)
- [ClusterRestoreBrokerOperation](docs/Model/ClusterRestoreBrokerOperation.md)
- [ClusterRestoreModeChangeOperation](docs/Model/ClusterRestoreModeChangeOperation.md)
- [ClusterRestoreOperation](docs/Model/ClusterRestoreOperation.md)
- [ClusterRestorePartitionOperation](docs/Model/ClusterRestorePartitionOperation.md)
- [ClusterRestorePartitionRestoreOperation](docs/Model/ClusterRestorePartitionRestoreOperation.md)
- [ClusterRestorePlannedChange](docs/Model/ClusterRestorePlannedChange.md)
- [ClusterRestoreRequest](docs/Model/ClusterRestoreRequest.md)
- [ClusterRestoreResponse](docs/Model/ClusterRestoreResponse.md)
- [ClusterRunningRebalance](docs/Model/ClusterRunningRebalance.md)
- [ClusterRuntimeBackupInfo](docs/Model/ClusterRuntimeBackupInfo.md)
- [ClusterRuntimeBackupState](docs/Model/ClusterRuntimeBackupState.md)
- [ClusterRuntimeBackupTakeOutcome](docs/Model/ClusterRuntimeBackupTakeOutcome.md)
- [ClusterRuntimeBackupTakeResult](docs/Model/ClusterRuntimeBackupTakeResult.md)
- [ClusterRuntimeBackupTenantInfo](docs/Model/ClusterRuntimeBackupTenantInfo.md)
- [ClusterRuntimeBackupTenantState](docs/Model/ClusterRuntimeBackupTenantState.md)
- [ClusterStatusResponse](docs/Model/ClusterStatusResponse.md)
- [ClusterTakeHistoryBackupResponse](docs/Model/ClusterTakeHistoryBackupResponse.md)
- [ClusterTakeRuntimeBackupResponse](docs/Model/ClusterTakeRuntimeBackupResponse.md)
- [ClusterTopologyResponse](docs/Model/ClusterTopologyResponse.md)
- [ClusterVariableKindEnum](docs/Model/ClusterVariableKindEnum.md)
- [ClusterVariableKindExactMatch](docs/Model/ClusterVariableKindExactMatch.md)
- [ClusterVariableKindFilterProperty](docs/Model/ClusterVariableKindFilterProperty.md)
- [ClusterVariableResult](docs/Model/ClusterVariableResult.md)
- [ClusterVariableResultBase](docs/Model/ClusterVariableResultBase.md)
- [ClusterVariableScopeEnum](docs/Model/ClusterVariableScopeEnum.md)
- [ClusterVariableScopeExactMatch](docs/Model/ClusterVariableScopeExactMatch.md)
- [ClusterVariableScopeFilterProperty](docs/Model/ClusterVariableScopeFilterProperty.md)
- [ClusterVariableSearchQueryFilterRequest](docs/Model/ClusterVariableSearchQueryFilterRequest.md)
- [ClusterVariableSearchQueryRequest](docs/Model/ClusterVariableSearchQueryRequest.md)
- [ClusterVariableSearchQueryResult](docs/Model/ClusterVariableSearchQueryResult.md)
- [ClusterVariableSearchQuerySortRequest](docs/Model/ClusterVariableSearchQuerySortRequest.md)
- [ClusterVariableSearchResult](docs/Model/ClusterVariableSearchResult.md)
- [ComponentsConfigurationResponse](docs/Model/ComponentsConfigurationResponse.md)
- [ConditionWaitStateDetails](docs/Model/ConditionWaitStateDetails.md)
- [ConditionalEvaluationInstruction](docs/Model/ConditionalEvaluationInstruction.md)
- [CorrelatedMessageSubscriptionFilter](docs/Model/CorrelatedMessageSubscriptionFilter.md)
- [CorrelatedMessageSubscriptionResult](docs/Model/CorrelatedMessageSubscriptionResult.md)
- [CorrelatedMessageSubscriptionSearchQuery](docs/Model/CorrelatedMessageSubscriptionSearchQuery.md)
- [CorrelatedMessageSubscriptionSearchQueryResult](docs/Model/CorrelatedMessageSubscriptionSearchQueryResult.md)
- [CorrelatedMessageSubscriptionSearchQuerySortRequest](docs/Model/CorrelatedMessageSubscriptionSearchQuerySortRequest.md)
- [CreateClusterVariableRequest](docs/Model/CreateClusterVariableRequest.md)
- [CreateClusterVariableRequestMetadataValue](docs/Model/CreateClusterVariableRequestMetadataValue.md)
- [CreateGlobalTaskListenerRequest](docs/Model/CreateGlobalTaskListenerRequest.md)
- [CreateProcessInstanceResult](docs/Model/CreateProcessInstanceResult.md)
- [CursorBackwardPagination](docs/Model/CursorBackwardPagination.md)
- [CursorForwardPagination](docs/Model/CursorForwardPagination.md)
- [DateTimeFilterProperty](docs/Model/DateTimeFilterProperty.md)
- [DecisionDefinitionFilter](docs/Model/DecisionDefinitionFilter.md)
- [DecisionDefinitionKeyExactMatch](docs/Model/DecisionDefinitionKeyExactMatch.md)
- [DecisionDefinitionKeyFilterProperty](docs/Model/DecisionDefinitionKeyFilterProperty.md)
- [DecisionDefinitionResult](docs/Model/DecisionDefinitionResult.md)
- [DecisionDefinitionSearchQuery](docs/Model/DecisionDefinitionSearchQuery.md)
- [DecisionDefinitionSearchQueryResult](docs/Model/DecisionDefinitionSearchQueryResult.md)
- [DecisionDefinitionSearchQuerySortRequest](docs/Model/DecisionDefinitionSearchQuerySortRequest.md)
- [DecisionDefinitionTypeEnum](docs/Model/DecisionDefinitionTypeEnum.md)
- [DecisionEvaluationById](docs/Model/DecisionEvaluationById.md)
- [DecisionEvaluationByKey](docs/Model/DecisionEvaluationByKey.md)
- [DecisionEvaluationInstanceKeyFilterProperty](docs/Model/DecisionEvaluationInstanceKeyFilterProperty.md)
- [DecisionEvaluationInstruction](docs/Model/DecisionEvaluationInstruction.md)
- [DecisionEvaluationKeyExactMatch](docs/Model/DecisionEvaluationKeyExactMatch.md)
- [DecisionEvaluationKeyFilterProperty](docs/Model/DecisionEvaluationKeyFilterProperty.md)
- [DecisionInstanceDeletionBatchOperationRequest](docs/Model/DecisionInstanceDeletionBatchOperationRequest.md)
- [DecisionInstanceFilter](docs/Model/DecisionInstanceFilter.md)
- [DecisionInstanceGetQueryResult](docs/Model/DecisionInstanceGetQueryResult.md)
- [DecisionInstanceResult](docs/Model/DecisionInstanceResult.md)
- [DecisionInstanceSearchQuery](docs/Model/DecisionInstanceSearchQuery.md)
- [DecisionInstanceSearchQueryResult](docs/Model/DecisionInstanceSearchQueryResult.md)
- [DecisionInstanceSearchQuerySortRequest](docs/Model/DecisionInstanceSearchQuerySortRequest.md)
- [DecisionInstanceStateEnum](docs/Model/DecisionInstanceStateEnum.md)
- [DecisionInstanceStateExactMatch](docs/Model/DecisionInstanceStateExactMatch.md)
- [DecisionInstanceStateFilterProperty](docs/Model/DecisionInstanceStateFilterProperty.md)
- [DecisionRequirementsFilter](docs/Model/DecisionRequirementsFilter.md)
- [DecisionRequirementsKeyExactMatch](docs/Model/DecisionRequirementsKeyExactMatch.md)
- [DecisionRequirementsKeyFilterProperty](docs/Model/DecisionRequirementsKeyFilterProperty.md)
- [DecisionRequirementsResult](docs/Model/DecisionRequirementsResult.md)
- [DecisionRequirementsSearchQuery](docs/Model/DecisionRequirementsSearchQuery.md)
- [DecisionRequirementsSearchQueryResult](docs/Model/DecisionRequirementsSearchQueryResult.md)
- [DecisionRequirementsSearchQuerySortRequest](docs/Model/DecisionRequirementsSearchQuerySortRequest.md)
- [DeleteDecisionInstanceRequest](docs/Model/DeleteDecisionInstanceRequest.md)
- [DeleteProcessInstanceRequest](docs/Model/DeleteProcessInstanceRequest.md)
- [DeleteResourceRequest](docs/Model/DeleteResourceRequest.md)
- [DeleteResourceResponse](docs/Model/DeleteResourceResponse.md)
- [DeploymentConfigurationResponse](docs/Model/DeploymentConfigurationResponse.md)
- [DeploymentDecisionRequirementsResult](docs/Model/DeploymentDecisionRequirementsResult.md)
- [DeploymentDecisionResult](docs/Model/DeploymentDecisionResult.md)
- [DeploymentFormResult](docs/Model/DeploymentFormResult.md)
- [DeploymentKeyExactMatch](docs/Model/DeploymentKeyExactMatch.md)
- [DeploymentKeyFilterProperty](docs/Model/DeploymentKeyFilterProperty.md)
- [DeploymentMetadataResult](docs/Model/DeploymentMetadataResult.md)
- [DeploymentProcessResult](docs/Model/DeploymentProcessResult.md)
- [DeploymentResourceResult](docs/Model/DeploymentResourceResult.md)
- [DeploymentResult](docs/Model/DeploymentResult.md)
- [DirectAncestorKeyInstruction](docs/Model/DirectAncestorKeyInstruction.md)
- [DocumentCreationBatchResponse](docs/Model/DocumentCreationBatchResponse.md)
- [DocumentCreationFailureDetail](docs/Model/DocumentCreationFailureDetail.md)
- [DocumentLink](docs/Model/DocumentLink.md)
- [DocumentLinkRequest](docs/Model/DocumentLinkRequest.md)
- [DocumentMetadata](docs/Model/DocumentMetadata.md)
- [DocumentMetadataResponse](docs/Model/DocumentMetadataResponse.md)
- [DocumentReference](docs/Model/DocumentReference.md)
- [ElementIdFilterProperty](docs/Model/ElementIdFilterProperty.md)
- [ElementInstanceFilter](docs/Model/ElementInstanceFilter.md)
- [ElementInstanceFilterFields](docs/Model/ElementInstanceFilterFields.md)
- [ElementInstanceKeyExactMatch](docs/Model/ElementInstanceKeyExactMatch.md)
- [ElementInstanceKeyFilterProperty](docs/Model/ElementInstanceKeyFilterProperty.md)
- [ElementInstanceResult](docs/Model/ElementInstanceResult.md)
- [ElementInstanceSearchQuery](docs/Model/ElementInstanceSearchQuery.md)
- [ElementInstanceSearchQueryResult](docs/Model/ElementInstanceSearchQueryResult.md)
- [ElementInstanceSearchQuerySortRequest](docs/Model/ElementInstanceSearchQuerySortRequest.md)
- [ElementInstanceStateEnum](docs/Model/ElementInstanceStateEnum.md)
- [ElementInstanceStateExactMatch](docs/Model/ElementInstanceStateExactMatch.md)
- [ElementInstanceStateFilterProperty](docs/Model/ElementInstanceStateFilterProperty.md)
- [ElementInstanceWaitStateFilter](docs/Model/ElementInstanceWaitStateFilter.md)
- [ElementInstanceWaitStateQuery](docs/Model/ElementInstanceWaitStateQuery.md)
- [ElementInstanceWaitStateQueryResult](docs/Model/ElementInstanceWaitStateQueryResult.md)
- [ElementInstanceWaitStateQuerySortRequest](docs/Model/ElementInstanceWaitStateQuerySortRequest.md)
- [ElementInstanceWaitStateResult](docs/Model/ElementInstanceWaitStateResult.md)
- [EntityTypeExactMatch](docs/Model/EntityTypeExactMatch.md)
- [EntityTypeFilterProperty](docs/Model/EntityTypeFilterProperty.md)
- [EvaluateConditionalResult](docs/Model/EvaluateConditionalResult.md)
- [EvaluateDecisionResult](docs/Model/EvaluateDecisionResult.md)
- [EvaluatedDecisionInputItem](docs/Model/EvaluatedDecisionInputItem.md)
- [EvaluatedDecisionOutputItem](docs/Model/EvaluatedDecisionOutputItem.md)
- [EvaluatedDecisionResult](docs/Model/EvaluatedDecisionResult.md)
- [ExportingStatusCode](docs/Model/ExportingStatusCode.md)
- [ExportingStatusResponse](docs/Model/ExportingStatusResponse.md)
- [ExpressionEvaluationRequest](docs/Model/ExpressionEvaluationRequest.md)
- [ExpressionEvaluationResult](docs/Model/ExpressionEvaluationResult.md)
- [ExpressionEvaluationWarningItem](docs/Model/ExpressionEvaluationWarningItem.md)
- [ExpressionSecretReferenceItem](docs/Model/ExpressionSecretReferenceItem.md)
- [FormKeyExactMatch](docs/Model/FormKeyExactMatch.md)
- [FormKeyFilterProperty](docs/Model/FormKeyFilterProperty.md)
- [FormResult](docs/Model/FormResult.md)
- [GlobalJobStatisticsQueryResult](docs/Model/GlobalJobStatisticsQueryResult.md)
- [GlobalListenerBase](docs/Model/GlobalListenerBase.md)
- [GlobalListenerSourceEnum](docs/Model/GlobalListenerSourceEnum.md)
- [GlobalListenerSourceExactMatch](docs/Model/GlobalListenerSourceExactMatch.md)
- [GlobalListenerSourceFilterProperty](docs/Model/GlobalListenerSourceFilterProperty.md)
- [GlobalTaskListenerBase](docs/Model/GlobalTaskListenerBase.md)
- [GlobalTaskListenerEventTypeEnum](docs/Model/GlobalTaskListenerEventTypeEnum.md)
- [GlobalTaskListenerEventTypeExactMatch](docs/Model/GlobalTaskListenerEventTypeExactMatch.md)
- [GlobalTaskListenerEventTypeFilterProperty](docs/Model/GlobalTaskListenerEventTypeFilterProperty.md)
- [GlobalTaskListenerResult](docs/Model/GlobalTaskListenerResult.md)
- [GlobalTaskListenerSearchQueryFilterRequest](docs/Model/GlobalTaskListenerSearchQueryFilterRequest.md)
- [GlobalTaskListenerSearchQueryRequest](docs/Model/GlobalTaskListenerSearchQueryRequest.md)
- [GlobalTaskListenerSearchQueryResult](docs/Model/GlobalTaskListenerSearchQueryResult.md)
- [GlobalTaskListenerSearchQuerySortRequest](docs/Model/GlobalTaskListenerSearchQuerySortRequest.md)
- [GroupClientResult](docs/Model/GroupClientResult.md)
- [GroupClientSearchQueryRequest](docs/Model/GroupClientSearchQueryRequest.md)
- [GroupClientSearchQuerySortRequest](docs/Model/GroupClientSearchQuerySortRequest.md)
- [GroupClientSearchResult](docs/Model/GroupClientSearchResult.md)
- [GroupCreateRequest](docs/Model/GroupCreateRequest.md)
- [GroupCreateResult](docs/Model/GroupCreateResult.md)
- [GroupFilter](docs/Model/GroupFilter.md)
- [GroupFilterFields](docs/Model/GroupFilterFields.md)
- [GroupMappingRuleSearchResult](docs/Model/GroupMappingRuleSearchResult.md)
- [GroupResult](docs/Model/GroupResult.md)
- [GroupRoleSearchResult](docs/Model/GroupRoleSearchResult.md)
- [GroupSearchQueryRequest](docs/Model/GroupSearchQueryRequest.md)
- [GroupSearchQueryResult](docs/Model/GroupSearchQueryResult.md)
- [GroupSearchQuerySortRequest](docs/Model/GroupSearchQuerySortRequest.md)
- [GroupUpdateRequest](docs/Model/GroupUpdateRequest.md)
- [GroupUpdateResult](docs/Model/GroupUpdateResult.md)
- [GroupUserResult](docs/Model/GroupUserResult.md)
- [GroupUserSearchQueryRequest](docs/Model/GroupUserSearchQueryRequest.md)
- [GroupUserSearchQuerySortRequest](docs/Model/GroupUserSearchQuerySortRequest.md)
- [GroupUserSearchResult](docs/Model/GroupUserSearchResult.md)
- [HistoryBackupInfo](docs/Model/HistoryBackupInfo.md)
- [HistoryBackupSnapshotInfo](docs/Model/HistoryBackupSnapshotInfo.md)
- [HistoryBackupStateCode](docs/Model/HistoryBackupStateCode.md)
- [IncidentErrorTypeEnum](docs/Model/IncidentErrorTypeEnum.md)
- [IncidentErrorTypeExactMatch](docs/Model/IncidentErrorTypeExactMatch.md)
- [IncidentErrorTypeFilterProperty](docs/Model/IncidentErrorTypeFilterProperty.md)
- [IncidentFilter](docs/Model/IncidentFilter.md)
- [IncidentProcessInstanceStatisticsByDefinitionFilter](docs/Model/IncidentProcessInstanceStatisticsByDefinitionFilter.md)
- [IncidentProcessInstanceStatisticsByDefinitionQuery](docs/Model/IncidentProcessInstanceStatisticsByDefinitionQuery.md)
- [IncidentProcessInstanceStatisticsByDefinitionQueryResult](docs/Model/IncidentProcessInstanceStatisticsByDefinitionQueryResult.md)
- [IncidentProcessInstanceStatisticsByDefinitionQuerySortRequest](docs/Model/IncidentProcessInstanceStatisticsByDefinitionQuerySortRequest.md)
- [IncidentProcessInstanceStatisticsByDefinitionResult](docs/Model/IncidentProcessInstanceStatisticsByDefinitionResult.md)
- [IncidentProcessInstanceStatisticsByErrorQuery](docs/Model/IncidentProcessInstanceStatisticsByErrorQuery.md)
- [IncidentProcessInstanceStatisticsByErrorQueryResult](docs/Model/IncidentProcessInstanceStatisticsByErrorQueryResult.md)
- [IncidentProcessInstanceStatisticsByErrorQuerySortRequest](docs/Model/IncidentProcessInstanceStatisticsByErrorQuerySortRequest.md)
- [IncidentProcessInstanceStatisticsByErrorResult](docs/Model/IncidentProcessInstanceStatisticsByErrorResult.md)
- [IncidentResolutionRequest](docs/Model/IncidentResolutionRequest.md)
- [IncidentResult](docs/Model/IncidentResult.md)
- [IncidentSearchQuery](docs/Model/IncidentSearchQuery.md)
- [IncidentSearchQueryResult](docs/Model/IncidentSearchQueryResult.md)
- [IncidentSearchQuerySortRequest](docs/Model/IncidentSearchQuerySortRequest.md)
- [IncidentStateEnum](docs/Model/IncidentStateEnum.md)
- [IncidentStateExactMatch](docs/Model/IncidentStateExactMatch.md)
- [IncidentStateFilterProperty](docs/Model/IncidentStateFilterProperty.md)
- [InferredAncestorKeyInstruction](docs/Model/InferredAncestorKeyInstruction.md)
- [IntegerFilterProperty](docs/Model/IntegerFilterProperty.md)
- [JobActivationRequest](docs/Model/JobActivationRequest.md)
- [JobActivationResult](docs/Model/JobActivationResult.md)
- [JobBatchUpdateRequest](docs/Model/JobBatchUpdateRequest.md)
- [JobChangeset](docs/Model/JobChangeset.md)
- [JobCompletionRequest](docs/Model/JobCompletionRequest.md)
- [JobErrorRequest](docs/Model/JobErrorRequest.md)
- [JobErrorStatisticsFilter](docs/Model/JobErrorStatisticsFilter.md)
- [JobErrorStatisticsItem](docs/Model/JobErrorStatisticsItem.md)
- [JobErrorStatisticsQuery](docs/Model/JobErrorStatisticsQuery.md)
- [JobErrorStatisticsQueryResult](docs/Model/JobErrorStatisticsQueryResult.md)
- [JobFailRequest](docs/Model/JobFailRequest.md)
- [JobFilter](docs/Model/JobFilter.md)
- [JobKeyExactMatch](docs/Model/JobKeyExactMatch.md)
- [JobKeyFilterProperty](docs/Model/JobKeyFilterProperty.md)
- [JobKindEnum](docs/Model/JobKindEnum.md)
- [JobKindExactMatch](docs/Model/JobKindExactMatch.md)
- [JobKindFilterProperty](docs/Model/JobKindFilterProperty.md)
- [JobListenerEventTypeEnum](docs/Model/JobListenerEventTypeEnum.md)
- [JobListenerEventTypeExactMatch](docs/Model/JobListenerEventTypeExactMatch.md)
- [JobListenerEventTypeFilterProperty](docs/Model/JobListenerEventTypeFilterProperty.md)
- [JobMetricsConfigurationResponse](docs/Model/JobMetricsConfigurationResponse.md)
- [JobResult](docs/Model/JobResult.md)
- [JobResultActivateElement](docs/Model/JobResultActivateElement.md)
- [JobResultAdHocSubProcess](docs/Model/JobResultAdHocSubProcess.md)
- [JobResultCorrections](docs/Model/JobResultCorrections.md)
- [JobResultUserTask](docs/Model/JobResultUserTask.md)
- [JobSearchQuery](docs/Model/JobSearchQuery.md)
- [JobSearchQueryResult](docs/Model/JobSearchQueryResult.md)
- [JobSearchQuerySortRequest](docs/Model/JobSearchQuerySortRequest.md)
- [JobSearchResult](docs/Model/JobSearchResult.md)
- [JobStateEnum](docs/Model/JobStateEnum.md)
- [JobStateExactMatch](docs/Model/JobStateExactMatch.md)
- [JobStateFilterProperty](docs/Model/JobStateFilterProperty.md)
- [JobTimeSeriesStatisticsFilter](docs/Model/JobTimeSeriesStatisticsFilter.md)
- [JobTimeSeriesStatisticsItem](docs/Model/JobTimeSeriesStatisticsItem.md)
- [JobTimeSeriesStatisticsQuery](docs/Model/JobTimeSeriesStatisticsQuery.md)
- [JobTimeSeriesStatisticsQueryResult](docs/Model/JobTimeSeriesStatisticsQueryResult.md)
- [JobTypeStatisticsFilter](docs/Model/JobTypeStatisticsFilter.md)
- [JobTypeStatisticsItem](docs/Model/JobTypeStatisticsItem.md)
- [JobTypeStatisticsQuery](docs/Model/JobTypeStatisticsQuery.md)
- [JobTypeStatisticsQueryResult](docs/Model/JobTypeStatisticsQueryResult.md)
- [JobUpdateRequest](docs/Model/JobUpdateRequest.md)
- [JobWaitStateDetails](docs/Model/JobWaitStateDetails.md)
- [JobWorkerStatisticsFilter](docs/Model/JobWorkerStatisticsFilter.md)
- [JobWorkerStatisticsItem](docs/Model/JobWorkerStatisticsItem.md)
- [JobWorkerStatisticsQuery](docs/Model/JobWorkerStatisticsQuery.md)
- [JobWorkerStatisticsQueryResult](docs/Model/JobWorkerStatisticsQueryResult.md)
- [LicenseResponse](docs/Model/LicenseResponse.md)
- [LimitPagination](docs/Model/LimitPagination.md)
- [MappingRuleCreateRequest](docs/Model/MappingRuleCreateRequest.md)
- [MappingRuleCreateResult](docs/Model/MappingRuleCreateResult.md)
- [MappingRuleCreateUpdateRequest](docs/Model/MappingRuleCreateUpdateRequest.md)
- [MappingRuleCreateUpdateResult](docs/Model/MappingRuleCreateUpdateResult.md)
- [MappingRuleFilter](docs/Model/MappingRuleFilter.md)
- [MappingRuleFilterFields](docs/Model/MappingRuleFilterFields.md)
- [MappingRuleResult](docs/Model/MappingRuleResult.md)
- [MappingRuleSearchQueryRequest](docs/Model/MappingRuleSearchQueryRequest.md)
- [MappingRuleSearchQueryResult](docs/Model/MappingRuleSearchQueryResult.md)
- [MappingRuleSearchQuerySortRequest](docs/Model/MappingRuleSearchQuerySortRequest.md)
- [MappingRuleUpdateRequest](docs/Model/MappingRuleUpdateRequest.md)
- [MappingRuleUpdateResult](docs/Model/MappingRuleUpdateResult.md)
- [MatchedDecisionRuleItem](docs/Model/MatchedDecisionRuleItem.md)
- [MessageCorrelationRequest](docs/Model/MessageCorrelationRequest.md)
- [MessageCorrelationResult](docs/Model/MessageCorrelationResult.md)
- [MessagePublicationRequest](docs/Model/MessagePublicationRequest.md)
- [MessagePublicationResult](docs/Model/MessagePublicationResult.md)
- [MessageSubscriptionFilter](docs/Model/MessageSubscriptionFilter.md)
- [MessageSubscriptionKeyExactMatch](docs/Model/MessageSubscriptionKeyExactMatch.md)
- [MessageSubscriptionKeyFilterProperty](docs/Model/MessageSubscriptionKeyFilterProperty.md)
- [MessageSubscriptionResult](docs/Model/MessageSubscriptionResult.md)
- [MessageSubscriptionSearchQuery](docs/Model/MessageSubscriptionSearchQuery.md)
- [MessageSubscriptionSearchQueryResult](docs/Model/MessageSubscriptionSearchQueryResult.md)
- [MessageSubscriptionSearchQuerySortRequest](docs/Model/MessageSubscriptionSearchQuerySortRequest.md)
- [MessageSubscriptionStateEnum](docs/Model/MessageSubscriptionStateEnum.md)
- [MessageSubscriptionStateExactMatch](docs/Model/MessageSubscriptionStateExactMatch.md)
- [MessageSubscriptionStateFilterProperty](docs/Model/MessageSubscriptionStateFilterProperty.md)
- [MessageSubscriptionTypeEnum](docs/Model/MessageSubscriptionTypeEnum.md)
- [MessageSubscriptionTypeExactMatch](docs/Model/MessageSubscriptionTypeExactMatch.md)
- [MessageSubscriptionTypeFilterProperty](docs/Model/MessageSubscriptionTypeFilterProperty.md)
- [MessageWaitStateDetails](docs/Model/MessageWaitStateDetails.md)
- [MigrateProcessInstanceMappingInstruction](docs/Model/MigrateProcessInstanceMappingInstruction.md)
- [Mode](docs/Model/Mode.md)
- [ModifyProcessInstanceVariableInstruction](docs/Model/ModifyProcessInstanceVariableInstruction.md)
- [OffsetPagination](docs/Model/OffsetPagination.md)
- [OperationTypeExactMatch](docs/Model/OperationTypeExactMatch.md)
- [OperationTypeFilterProperty](docs/Model/OperationTypeFilterProperty.md)
- [OwnAuthorizationSearchResult](docs/Model/OwnAuthorizationSearchResult.md)
- [OwnerTypeEnum](docs/Model/OwnerTypeEnum.md)
- [Partition](docs/Model/Partition.md)
- [PartitionBackupInfo](docs/Model/PartitionBackupInfo.md)
- [PartitionBackupRange](docs/Model/PartitionBackupRange.md)
- [PartitionBackupState](docs/Model/PartitionBackupState.md)
- [PartitionCheckpointState](docs/Model/PartitionCheckpointState.md)
- [PermissionTypeEnum](docs/Model/PermissionTypeEnum.md)
- [PhysicalTenantBrokerTopology](docs/Model/PhysicalTenantBrokerTopology.md)
- [PhysicalTenantTopology](docs/Model/PhysicalTenantTopology.md)
- [ProblemDetail](docs/Model/ProblemDetail.md)
- [ProcessDefinitionElementStatisticsQuery](docs/Model/ProcessDefinitionElementStatisticsQuery.md)
- [ProcessDefinitionElementStatisticsQueryResult](docs/Model/ProcessDefinitionElementStatisticsQueryResult.md)
- [ProcessDefinitionFilter](docs/Model/ProcessDefinitionFilter.md)
- [ProcessDefinitionIdFilterProperty](docs/Model/ProcessDefinitionIdFilterProperty.md)
- [ProcessDefinitionInstanceStatisticsQuery](docs/Model/ProcessDefinitionInstanceStatisticsQuery.md)
- [ProcessDefinitionInstanceStatisticsQueryResult](docs/Model/ProcessDefinitionInstanceStatisticsQueryResult.md)
- [ProcessDefinitionInstanceStatisticsQuerySortRequest](docs/Model/ProcessDefinitionInstanceStatisticsQuerySortRequest.md)
- [ProcessDefinitionInstanceStatisticsResult](docs/Model/ProcessDefinitionInstanceStatisticsResult.md)
- [ProcessDefinitionInstanceVersionStatisticsFilter](docs/Model/ProcessDefinitionInstanceVersionStatisticsFilter.md)
- [ProcessDefinitionInstanceVersionStatisticsQuery](docs/Model/ProcessDefinitionInstanceVersionStatisticsQuery.md)
- [ProcessDefinitionInstanceVersionStatisticsQueryResult](docs/Model/ProcessDefinitionInstanceVersionStatisticsQueryResult.md)
- [ProcessDefinitionInstanceVersionStatisticsQuerySortRequest](docs/Model/ProcessDefinitionInstanceVersionStatisticsQuerySortRequest.md)
- [ProcessDefinitionInstanceVersionStatisticsResult](docs/Model/ProcessDefinitionInstanceVersionStatisticsResult.md)
- [ProcessDefinitionKeyExactMatch](docs/Model/ProcessDefinitionKeyExactMatch.md)
- [ProcessDefinitionKeyFilterProperty](docs/Model/ProcessDefinitionKeyFilterProperty.md)
- [ProcessDefinitionMessageSubscriptionStatisticsQuery](docs/Model/ProcessDefinitionMessageSubscriptionStatisticsQuery.md)
- [ProcessDefinitionMessageSubscriptionStatisticsQueryResult](docs/Model/ProcessDefinitionMessageSubscriptionStatisticsQueryResult.md)
- [ProcessDefinitionMessageSubscriptionStatisticsResult](docs/Model/ProcessDefinitionMessageSubscriptionStatisticsResult.md)
- [ProcessDefinitionResult](docs/Model/ProcessDefinitionResult.md)
- [ProcessDefinitionSearchQuery](docs/Model/ProcessDefinitionSearchQuery.md)
- [ProcessDefinitionSearchQueryResult](docs/Model/ProcessDefinitionSearchQueryResult.md)
- [ProcessDefinitionSearchQuerySortRequest](docs/Model/ProcessDefinitionSearchQuerySortRequest.md)
- [ProcessDefinitionStatisticsFilter](docs/Model/ProcessDefinitionStatisticsFilter.md)
- [ProcessDefinitionVariableNameFilter](docs/Model/ProcessDefinitionVariableNameFilter.md)
- [ProcessDefinitionVariableNameSearchQuery](docs/Model/ProcessDefinitionVariableNameSearchQuery.md)
- [ProcessDefinitionVariableNameSearchQueryResult](docs/Model/ProcessDefinitionVariableNameSearchQueryResult.md)
- [ProcessDefinitionVariableNameSearchResult](docs/Model/ProcessDefinitionVariableNameSearchResult.md)
- [ProcessElementStatisticsResult](docs/Model/ProcessElementStatisticsResult.md)
- [ProcessInstanceBusinessIdAssignmentInstruction](docs/Model/ProcessInstanceBusinessIdAssignmentInstruction.md)
- [ProcessInstanceCallHierarchyEntry](docs/Model/ProcessInstanceCallHierarchyEntry.md)
- [ProcessInstanceCancellationBatchOperationRequest](docs/Model/ProcessInstanceCancellationBatchOperationRequest.md)
- [ProcessInstanceCreationInstruction](docs/Model/ProcessInstanceCreationInstruction.md)
- [ProcessInstanceCreationInstructionById](docs/Model/ProcessInstanceCreationInstructionById.md)
- [ProcessInstanceCreationInstructionByKey](docs/Model/ProcessInstanceCreationInstructionByKey.md)
- [ProcessInstanceCreationStartInstruction](docs/Model/ProcessInstanceCreationStartInstruction.md)
- [ProcessInstanceCreationTerminateInstruction](docs/Model/ProcessInstanceCreationTerminateInstruction.md)
- [ProcessInstanceDeletionBatchOperationRequest](docs/Model/ProcessInstanceDeletionBatchOperationRequest.md)
- [ProcessInstanceElementStatisticsQueryResult](docs/Model/ProcessInstanceElementStatisticsQueryResult.md)
- [ProcessInstanceFilter](docs/Model/ProcessInstanceFilter.md)
- [ProcessInstanceFilterFields](docs/Model/ProcessInstanceFilterFields.md)
- [ProcessInstanceIncidentResolutionBatchOperationRequest](docs/Model/ProcessInstanceIncidentResolutionBatchOperationRequest.md)
- [ProcessInstanceKeyExactMatch](docs/Model/ProcessInstanceKeyExactMatch.md)
- [ProcessInstanceKeyFilterProperty](docs/Model/ProcessInstanceKeyFilterProperty.md)
- [ProcessInstanceMigrationBatchOperationPlan](docs/Model/ProcessInstanceMigrationBatchOperationPlan.md)
- [ProcessInstanceMigrationBatchOperationRequest](docs/Model/ProcessInstanceMigrationBatchOperationRequest.md)
- [ProcessInstanceMigrationInstruction](docs/Model/ProcessInstanceMigrationInstruction.md)
- [ProcessInstanceModificationActivateInstruction](docs/Model/ProcessInstanceModificationActivateInstruction.md)
- [ProcessInstanceModificationBatchOperationRequest](docs/Model/ProcessInstanceModificationBatchOperationRequest.md)
- [ProcessInstanceModificationInstruction](docs/Model/ProcessInstanceModificationInstruction.md)
- [ProcessInstanceModificationMoveBatchOperationInstruction](docs/Model/ProcessInstanceModificationMoveBatchOperationInstruction.md)
- [ProcessInstanceModificationMoveInstruction](docs/Model/ProcessInstanceModificationMoveInstruction.md)
- [ProcessInstanceModificationTerminateByIdInstruction](docs/Model/ProcessInstanceModificationTerminateByIdInstruction.md)
- [ProcessInstanceModificationTerminateByKeyInstruction](docs/Model/ProcessInstanceModificationTerminateByKeyInstruction.md)
- [ProcessInstanceModificationTerminateInstruction](docs/Model/ProcessInstanceModificationTerminateInstruction.md)
- [ProcessInstanceReference](docs/Model/ProcessInstanceReference.md)
- [ProcessInstanceResult](docs/Model/ProcessInstanceResult.md)
- [ProcessInstanceResumptionBatchOperationRequest](docs/Model/ProcessInstanceResumptionBatchOperationRequest.md)
- [ProcessInstanceSearchQuery](docs/Model/ProcessInstanceSearchQuery.md)
- [ProcessInstanceSearchQueryResult](docs/Model/ProcessInstanceSearchQueryResult.md)
- [ProcessInstanceSearchQuerySortRequest](docs/Model/ProcessInstanceSearchQuerySortRequest.md)
- [ProcessInstanceSequenceFlowResult](docs/Model/ProcessInstanceSequenceFlowResult.md)
- [ProcessInstanceSequenceFlowsQueryResult](docs/Model/ProcessInstanceSequenceFlowsQueryResult.md)
- [ProcessInstanceStateEnum](docs/Model/ProcessInstanceStateEnum.md)
- [ProcessInstanceStateExactMatch](docs/Model/ProcessInstanceStateExactMatch.md)
- [ProcessInstanceStateFilterProperty](docs/Model/ProcessInstanceStateFilterProperty.md)
- [ProcessInstanceSuspensionBatchOperationRequest](docs/Model/ProcessInstanceSuspensionBatchOperationRequest.md)
- [ProcessInstanceWaitStateStatisticsQueryResult](docs/Model/ProcessInstanceWaitStateStatisticsQueryResult.md)
- [ProcessInstanceWaitStateStatisticsResult](docs/Model/ProcessInstanceWaitStateStatisticsResult.md)
- [RebalanceCancellationResponse](docs/Model/RebalanceCancellationResponse.md)
- [ResolvedSecret](docs/Model/ResolvedSecret.md)
- [ResourceFilter](docs/Model/ResourceFilter.md)
- [ResourceKey](docs/Model/ResourceKey.md)
- [ResourceKeyExactMatch](docs/Model/ResourceKeyExactMatch.md)
- [ResourceKeyFilterProperty](docs/Model/ResourceKeyFilterProperty.md)
- [ResourceResult](docs/Model/ResourceResult.md)
- [ResourceSearchQuery](docs/Model/ResourceSearchQuery.md)
- [ResourceSearchQueryResult](docs/Model/ResourceSearchQueryResult.md)
- [ResourceSearchQuerySortRequest](docs/Model/ResourceSearchQuerySortRequest.md)
- [ResourceTypeEnum](docs/Model/ResourceTypeEnum.md)
- [RestoreBrokerStatus](docs/Model/RestoreBrokerStatus.md)
- [RestorePartitionStatus](docs/Model/RestorePartitionStatus.md)
- [RestoreRequest](docs/Model/RestoreRequest.md)
- [RestoreStatusResponse](docs/Model/RestoreStatusResponse.md)
- [ResumeProcessInstanceRequest](docs/Model/ResumeProcessInstanceRequest.md)
- [RoleClientResult](docs/Model/RoleClientResult.md)
- [RoleClientSearchQueryRequest](docs/Model/RoleClientSearchQueryRequest.md)
- [RoleClientSearchQuerySortRequest](docs/Model/RoleClientSearchQuerySortRequest.md)
- [RoleClientSearchResult](docs/Model/RoleClientSearchResult.md)
- [RoleCreateRequest](docs/Model/RoleCreateRequest.md)
- [RoleCreateResult](docs/Model/RoleCreateResult.md)
- [RoleFilter](docs/Model/RoleFilter.md)
- [RoleFilterFields](docs/Model/RoleFilterFields.md)
- [RoleGroupResult](docs/Model/RoleGroupResult.md)
- [RoleGroupSearchQueryRequest](docs/Model/RoleGroupSearchQueryRequest.md)
- [RoleGroupSearchQuerySortRequest](docs/Model/RoleGroupSearchQuerySortRequest.md)
- [RoleGroupSearchResult](docs/Model/RoleGroupSearchResult.md)
- [RoleMappingRuleSearchResult](docs/Model/RoleMappingRuleSearchResult.md)
- [RoleResult](docs/Model/RoleResult.md)
- [RoleSearchQueryRequest](docs/Model/RoleSearchQueryRequest.md)
- [RoleSearchQueryResult](docs/Model/RoleSearchQueryResult.md)
- [RoleSearchQuerySortRequest](docs/Model/RoleSearchQuerySortRequest.md)
- [RoleUpdateRequest](docs/Model/RoleUpdateRequest.md)
- [RoleUpdateResult](docs/Model/RoleUpdateResult.md)
- [RoleUserResult](docs/Model/RoleUserResult.md)
- [RoleUserSearchQueryRequest](docs/Model/RoleUserSearchQueryRequest.md)
- [RoleUserSearchQuerySortRequest](docs/Model/RoleUserSearchQuerySortRequest.md)
- [RoleUserSearchResult](docs/Model/RoleUserSearchResult.md)
- [RuntimeBackupState](docs/Model/RuntimeBackupState.md)
- [ScopeKey](docs/Model/ScopeKey.md)
- [ScopeKeyExactMatch](docs/Model/ScopeKeyExactMatch.md)
- [ScopeKeyFilterProperty](docs/Model/ScopeKeyFilterProperty.md)
- [SearchQueryPageRequest](docs/Model/SearchQueryPageRequest.md)
- [SearchQueryPageResponse](docs/Model/SearchQueryPageResponse.md)
- [SearchQueryRequest](docs/Model/SearchQueryRequest.md)
- [SearchQueryResponse](docs/Model/SearchQueryResponse.md)
- [SecretErrorCode](docs/Model/SecretErrorCode.md)
- [SecretListResult](docs/Model/SecretListResult.md)
- [SecretResolutionError](docs/Model/SecretResolutionError.md)
- [SecretResolveRequest](docs/Model/SecretResolveRequest.md)
- [SecretResolveResult](docs/Model/SecretResolveResult.md)
- [SetVariableRequest](docs/Model/SetVariableRequest.md)
- [SignalBroadcastRequest](docs/Model/SignalBroadcastRequest.md)
- [SignalBroadcastResult](docs/Model/SignalBroadcastResult.md)
- [SignalWaitStateDetails](docs/Model/SignalWaitStateDetails.md)
- [SortOrderEnum](docs/Model/SortOrderEnum.md)
- [SourceElementIdInstruction](docs/Model/SourceElementIdInstruction.md)
- [SourceElementInstanceKeyInstruction](docs/Model/SourceElementInstanceKeyInstruction.md)
- [SourceElementInstruction](docs/Model/SourceElementInstruction.md)
- [StateCode](docs/Model/StateCode.md)
- [StatusMetric](docs/Model/StatusMetric.md)
- [StringFilterProperty](docs/Model/StringFilterProperty.md)
- [SuspendProcessInstanceRequest](docs/Model/SuspendProcessInstanceRequest.md)
- [SystemConfigurationResponse](docs/Model/SystemConfigurationResponse.md)
- [TakeHistoryBackupRequest](docs/Model/TakeHistoryBackupRequest.md)
- [TakeHistoryBackupResponse](docs/Model/TakeHistoryBackupResponse.md)
- [TakeRuntimeBackupRequest](docs/Model/TakeRuntimeBackupRequest.md)
- [TakeRuntimeBackupResponse](docs/Model/TakeRuntimeBackupResponse.md)
- [TenantClientResult](docs/Model/TenantClientResult.md)
- [TenantClientSearchQueryRequest](docs/Model/TenantClientSearchQueryRequest.md)
- [TenantClientSearchQuerySortRequest](docs/Model/TenantClientSearchQuerySortRequest.md)
- [TenantClientSearchResult](docs/Model/TenantClientSearchResult.md)
- [TenantCreateRequest](docs/Model/TenantCreateRequest.md)
- [TenantCreateResult](docs/Model/TenantCreateResult.md)
- [TenantFilter](docs/Model/TenantFilter.md)
- [TenantFilterEnum](docs/Model/TenantFilterEnum.md)
- [TenantGroupResult](docs/Model/TenantGroupResult.md)
- [TenantGroupSearchQueryRequest](docs/Model/TenantGroupSearchQueryRequest.md)
- [TenantGroupSearchQuerySortRequest](docs/Model/TenantGroupSearchQuerySortRequest.md)
- [TenantGroupSearchResult](docs/Model/TenantGroupSearchResult.md)
- [TenantMappingRuleSearchResult](docs/Model/TenantMappingRuleSearchResult.md)
- [TenantResult](docs/Model/TenantResult.md)
- [TenantRoleSearchResult](docs/Model/TenantRoleSearchResult.md)
- [TenantSearchQueryRequest](docs/Model/TenantSearchQueryRequest.md)
- [TenantSearchQueryResult](docs/Model/TenantSearchQueryResult.md)
- [TenantSearchQuerySortRequest](docs/Model/TenantSearchQuerySortRequest.md)
- [TenantUpdateRequest](docs/Model/TenantUpdateRequest.md)
- [TenantUpdateResult](docs/Model/TenantUpdateResult.md)
- [TenantUserResult](docs/Model/TenantUserResult.md)
- [TenantUserSearchQueryRequest](docs/Model/TenantUserSearchQueryRequest.md)
- [TenantUserSearchQuerySortRequest](docs/Model/TenantUserSearchQuerySortRequest.md)
- [TenantUserSearchResult](docs/Model/TenantUserSearchResult.md)
- [TimerWaitStateDetails](docs/Model/TimerWaitStateDetails.md)
- [TopologyResponse](docs/Model/TopologyResponse.md)
- [UpdateClusterVariableRequest](docs/Model/UpdateClusterVariableRequest.md)
- [UpdateGlobalTaskListenerRequest](docs/Model/UpdateGlobalTaskListenerRequest.md)
- [UsageMetricsResponse](docs/Model/UsageMetricsResponse.md)
- [UsageMetricsResponseItem](docs/Model/UsageMetricsResponseItem.md)
- [UseSourceParentKeyInstruction](docs/Model/UseSourceParentKeyInstruction.md)
- [UserCreateResult](docs/Model/UserCreateResult.md)
- [UserFilter](docs/Model/UserFilter.md)
- [UserFilterFields](docs/Model/UserFilterFields.md)
- [UserRequest](docs/Model/UserRequest.md)
- [UserResult](docs/Model/UserResult.md)
- [UserSearchQueryRequest](docs/Model/UserSearchQueryRequest.md)
- [UserSearchQuerySortRequest](docs/Model/UserSearchQuerySortRequest.md)
- [UserSearchResult](docs/Model/UserSearchResult.md)
- [UserTaskAssignmentRequest](docs/Model/UserTaskAssignmentRequest.md)
- [UserTaskAuditLogFilter](docs/Model/UserTaskAuditLogFilter.md)
- [UserTaskAuditLogSearchQueryRequest](docs/Model/UserTaskAuditLogSearchQueryRequest.md)
- [UserTaskCompletionRequest](docs/Model/UserTaskCompletionRequest.md)
- [UserTaskEffectiveVariableSearchQueryRequest](docs/Model/UserTaskEffectiveVariableSearchQueryRequest.md)
- [UserTaskFilter](docs/Model/UserTaskFilter.md)
- [UserTaskFilterFields](docs/Model/UserTaskFilterFields.md)
- [UserTaskProperties](docs/Model/UserTaskProperties.md)
- [UserTaskResult](docs/Model/UserTaskResult.md)
- [UserTaskSearchQuery](docs/Model/UserTaskSearchQuery.md)
- [UserTaskSearchQueryResult](docs/Model/UserTaskSearchQueryResult.md)
- [UserTaskSearchQuerySortRequest](docs/Model/UserTaskSearchQuerySortRequest.md)
- [UserTaskStateEnum](docs/Model/UserTaskStateEnum.md)
- [UserTaskStateExactMatch](docs/Model/UserTaskStateExactMatch.md)
- [UserTaskStateFilterProperty](docs/Model/UserTaskStateFilterProperty.md)
- [UserTaskUpdateRequest](docs/Model/UserTaskUpdateRequest.md)
- [UserTaskVariableFilter](docs/Model/UserTaskVariableFilter.md)
- [UserTaskVariableSearchQueryRequest](docs/Model/UserTaskVariableSearchQueryRequest.md)
- [UserTaskVariableSearchQuerySortRequest](docs/Model/UserTaskVariableSearchQuerySortRequest.md)
- [UserTaskWaitStateDetails](docs/Model/UserTaskWaitStateDetails.md)
- [UserUpdateRequest](docs/Model/UserUpdateRequest.md)
- [UserUpdateResult](docs/Model/UserUpdateResult.md)
- [VariableFilter](docs/Model/VariableFilter.md)
- [VariableKeyExactMatch](docs/Model/VariableKeyExactMatch.md)
- [VariableKeyFilterProperty](docs/Model/VariableKeyFilterProperty.md)
- [VariableResult](docs/Model/VariableResult.md)
- [VariableResultBase](docs/Model/VariableResultBase.md)
- [VariableSearchQuery](docs/Model/VariableSearchQuery.md)
- [VariableSearchQueryResult](docs/Model/VariableSearchQueryResult.md)
- [VariableSearchQuerySortRequest](docs/Model/VariableSearchQuerySortRequest.md)
- [VariableSearchResult](docs/Model/VariableSearchResult.md)
- [VariableValueFilterProperty](docs/Model/VariableValueFilterProperty.md)
- [WaitStateDetails](docs/Model/WaitStateDetails.md)
- [WaitStateElementTypeEnum](docs/Model/WaitStateElementTypeEnum.md)
- [WaitStateElementTypeExactMatch](docs/Model/WaitStateElementTypeExactMatch.md)
- [WaitStateElementTypeFilterProperty](docs/Model/WaitStateElementTypeFilterProperty.md)
- [WaitStateTypeEnum](docs/Model/WaitStateTypeEnum.md)
- [WaitStateTypeExactMatch](docs/Model/WaitStateTypeExactMatch.md)
- [WaitStateTypeFilterProperty](docs/Model/WaitStateTypeFilterProperty.md)
- [WebappComponent](docs/Model/WebappComponent.md)

## Authorization

### bearerAuth

- **Type**: Bearer authentication (JWT)


### basicAuth

- **Type**: HTTP basic authentication

## Tests

To run the tests, use:

```bash
composer install
vendor/bin/phpunit
```

## Author



## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `0.1`
    - Package version: `0.1.0`
    - Generator version: `7.25.0`
- Build package: `org.openapitools.codegen.languages.PhpNextgenClientCodegen`
