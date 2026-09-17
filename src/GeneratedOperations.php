<?php

declare(strict_types=1);

namespace Camunda\Orchestration;

/**
 * Flat, synchronous facade over every generated Camunda Orchestration Cluster
 * API operation.
 *
 * Each method forwards to the matching generated API group via $this->api().
 * This trait is regenerated from the generated API classes by
 * hooks/post_gen/0500_flat_facade.php; do not edit by hand.
 *
 * @internal
 */
trait GeneratedOperations
{
    /**
     * Operation activateAdHocSubProcessActivities
     *
     * Activate activities within an ad-hoc sub-process
     *
     * @param  string $adHocSubProcessInstanceKey The key of the ad-hoc sub-process instance that contains the activities. (required)
     * @param  \Camunda\Orchestration\Api\Model\AdHocSubProcessActivateActivitiesInstruction $adHocSubProcessActivateActivitiesInstruction adHocSubProcessActivateActivitiesInstruction (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['activateAdHocSubProcessActivities'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function activateAdHocSubProcessActivities(string $adHocSubProcessInstanceKey, \Camunda\Orchestration\Api\Model\AdHocSubProcessActivateActivitiesInstruction $adHocSubProcessActivateActivitiesInstruction, string $contentType = \Camunda\Orchestration\Api\Api\AdHocSubProcessApi::contentTypes['activateAdHocSubProcessActivities'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\AdHocSubProcessApi::class)->activateAdHocSubProcessActivities($adHocSubProcessInstanceKey, $adHocSubProcessActivateActivitiesInstruction, $contentType);
    }

    /**
     * Operation getAgentDefinition
     *
     * Get agent definition
     *
     * @param  string $agentDefinitionKey The assigned key of the agent definition, which acts as a unique identifier for this agent definition. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAgentDefinition'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\AgentDefinitionResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getAgentDefinition(string $agentDefinitionKey, string $contentType = \Camunda\Orchestration\Api\Api\AgentDefinitionApi::contentTypes['getAgentDefinition'][0]): \Camunda\Orchestration\Api\Model\AgentDefinitionResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\AgentDefinitionApi::class)->getAgentDefinition($agentDefinitionKey, $contentType);
    }

    /**
     * Operation searchAgentDefinitions
     *
     * Search agent definitions
     *
     * @param  \Camunda\Orchestration\Api\Model\AgentDefinitionSearchQuery|null $agentDefinitionSearchQuery agentDefinitionSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchAgentDefinitions'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\AgentDefinitionSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchAgentDefinitions(?\Camunda\Orchestration\Api\Model\AgentDefinitionSearchQuery $agentDefinitionSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\AgentDefinitionApi::contentTypes['searchAgentDefinitions'][0]): \Camunda\Orchestration\Api\Model\AgentDefinitionSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\AgentDefinitionApi::class)->searchAgentDefinitions($agentDefinitionSearchQuery, $contentType);
    }

    /**
     * Operation createAgentInstance
     *
     * Create agent instance
     *
     * @param  \Camunda\Orchestration\Api\Model\AgentInstanceCreationRequest $agentInstanceCreationRequest agentInstanceCreationRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['createAgentInstance'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\AgentInstanceCreationResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function createAgentInstance(\Camunda\Orchestration\Api\Model\AgentInstanceCreationRequest $agentInstanceCreationRequest, string $contentType = \Camunda\Orchestration\Api\Api\AgentInstanceApi::contentTypes['createAgentInstance'][0]): \Camunda\Orchestration\Api\Model\AgentInstanceCreationResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\AgentInstanceApi::class)->createAgentInstance($agentInstanceCreationRequest, $contentType);
    }

    /**
     * Operation getAgentInstance
     *
     * Get agent instance
     *
     * @param  string $agentInstanceKey The key of the agent instance to retrieve. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAgentInstance'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\AgentInstanceResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getAgentInstance(string $agentInstanceKey, string $contentType = \Camunda\Orchestration\Api\Api\AgentInstanceApi::contentTypes['getAgentInstance'][0]): \Camunda\Orchestration\Api\Model\AgentInstanceResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\AgentInstanceApi::class)->getAgentInstance($agentInstanceKey, $contentType);
    }

    /**
     * Operation searchAgentInstanceHistory
     *
     * Search agent instance history
     *
     * @param  string $agentInstanceKey The key of the agent instance whose history to search. (required)
     * @param  \Camunda\Orchestration\Api\Model\AgentInstanceHistorySearchQuery|null $agentInstanceHistorySearchQuery agentInstanceHistorySearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchAgentInstanceHistory'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\AgentInstanceHistorySearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchAgentInstanceHistory(string $agentInstanceKey, ?\Camunda\Orchestration\Api\Model\AgentInstanceHistorySearchQuery $agentInstanceHistorySearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\AgentInstanceApi::contentTypes['searchAgentInstanceHistory'][0]): \Camunda\Orchestration\Api\Model\AgentInstanceHistorySearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\AgentInstanceApi::class)->searchAgentInstanceHistory($agentInstanceKey, $agentInstanceHistorySearchQuery, $contentType);
    }

    /**
     * Operation searchAgentInstances
     *
     * Search agent instances
     *
     * @param  \Camunda\Orchestration\Api\Model\AgentInstanceSearchQuery|null $agentInstanceSearchQuery agentInstanceSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchAgentInstances'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\AgentInstanceSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchAgentInstances(?\Camunda\Orchestration\Api\Model\AgentInstanceSearchQuery $agentInstanceSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\AgentInstanceApi::contentTypes['searchAgentInstances'][0]): \Camunda\Orchestration\Api\Model\AgentInstanceSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\AgentInstanceApi::class)->searchAgentInstances($agentInstanceSearchQuery, $contentType);
    }

    /**
     * Operation updateAgentInstance
     *
     * Update agent instance
     *
     * @param  string $agentInstanceKey The key of the agent instance to update. (required)
     * @param  \Camunda\Orchestration\Api\Model\AgentInstanceUpdateRequest $agentInstanceUpdateRequest agentInstanceUpdateRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['updateAgentInstance'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\AgentInstanceUpdateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function updateAgentInstance(string $agentInstanceKey, \Camunda\Orchestration\Api\Model\AgentInstanceUpdateRequest $agentInstanceUpdateRequest, string $contentType = \Camunda\Orchestration\Api\Api\AgentInstanceApi::contentTypes['updateAgentInstance'][0]): \Camunda\Orchestration\Api\Model\AgentInstanceUpdateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\AgentInstanceApi::class)->updateAgentInstance($agentInstanceKey, $agentInstanceUpdateRequest, $contentType);
    }

    /**
     * Operation getAuditLog
     *
     * Get audit log
     *
     * @param  string $auditLogKey The audit log key. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAuditLog'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\AuditLogResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getAuditLog(string $auditLogKey, string $contentType = \Camunda\Orchestration\Api\Api\AuditLogApi::contentTypes['getAuditLog'][0]): \Camunda\Orchestration\Api\Model\AuditLogResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\AuditLogApi::class)->getAuditLog($auditLogKey, $contentType);
    }

    /**
     * Operation searchAuditLogs
     *
     * Search audit logs
     *
     * @param  \Camunda\Orchestration\Api\Model\AuditLogSearchQueryRequest|null $auditLogSearchQueryRequest auditLogSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchAuditLogs'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\AuditLogSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchAuditLogs(?\Camunda\Orchestration\Api\Model\AuditLogSearchQueryRequest $auditLogSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\AuditLogApi::contentTypes['searchAuditLogs'][0]): \Camunda\Orchestration\Api\Model\AuditLogSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\AuditLogApi::class)->searchAuditLogs($auditLogSearchQueryRequest, $contentType);
    }

    /**
     * Operation getAuthentication
     *
     * Get current user
     *
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAuthentication'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\CamundaUserResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getAuthentication(string $contentType = \Camunda\Orchestration\Api\Api\AuthenticationApi::contentTypes['getAuthentication'][0]): \Camunda\Orchestration\Api\Model\CamundaUserResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\AuthenticationApi::class)->getAuthentication($contentType);
    }

    /**
     * Operation searchOwnAuthorizations
     *
     * Search own authorizations
     *
     * @param  \Camunda\Orchestration\Api\Model\AuthorizationSearchQuery|null $authorizationSearchQuery authorizationSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchOwnAuthorizations'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\OwnAuthorizationSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchOwnAuthorizations(?\Camunda\Orchestration\Api\Model\AuthorizationSearchQuery $authorizationSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\AuthenticationApi::contentTypes['searchOwnAuthorizations'][0]): \Camunda\Orchestration\Api\Model\OwnAuthorizationSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\AuthenticationApi::class)->searchOwnAuthorizations($authorizationSearchQuery, $contentType);
    }

    /**
     * Operation createAuthorization
     *
     * Create authorization
     *
     * @param  \Camunda\Orchestration\Api\Model\AuthorizationIdBasedRequest|\Camunda\Orchestration\Api\Model\AuthorizationPropertyBasedRequest $authorizationRequest authorizationRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['createAuthorization'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\AuthorizationCreateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function createAuthorization(\Camunda\Orchestration\Api\Model\AuthorizationIdBasedRequest|\Camunda\Orchestration\Api\Model\AuthorizationPropertyBasedRequest $authorizationRequest, string $contentType = \Camunda\Orchestration\Api\Api\AuthorizationApi::contentTypes['createAuthorization'][0]): \Camunda\Orchestration\Api\Model\AuthorizationCreateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\AuthorizationApi::class)->createAuthorization($authorizationRequest, $contentType);
    }

    /**
     * Operation deleteAuthorization
     *
     * Delete authorization
     *
     * @param  string $authorizationKey The key of the authorization to delete. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteAuthorization'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function deleteAuthorization(string $authorizationKey, string $contentType = \Camunda\Orchestration\Api\Api\AuthorizationApi::contentTypes['deleteAuthorization'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\AuthorizationApi::class)->deleteAuthorization($authorizationKey, $contentType);
    }

    /**
     * Operation getAuthorization
     *
     * Get authorization
     *
     * @param  string $authorizationKey The key of the authorization to get. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getAuthorization'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\AuthorizationResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getAuthorization(string $authorizationKey, string $contentType = \Camunda\Orchestration\Api\Api\AuthorizationApi::contentTypes['getAuthorization'][0]): \Camunda\Orchestration\Api\Model\AuthorizationResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\AuthorizationApi::class)->getAuthorization($authorizationKey, $contentType);
    }

    /**
     * Operation searchAuthorizations
     *
     * Search authorizations
     *
     * @param  \Camunda\Orchestration\Api\Model\AuthorizationSearchQuery|null $authorizationSearchQuery authorizationSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchAuthorizations'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\AuthorizationSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchAuthorizations(?\Camunda\Orchestration\Api\Model\AuthorizationSearchQuery $authorizationSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\AuthorizationApi::contentTypes['searchAuthorizations'][0]): \Camunda\Orchestration\Api\Model\AuthorizationSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\AuthorizationApi::class)->searchAuthorizations($authorizationSearchQuery, $contentType);
    }

    /**
     * Operation updateAuthorization
     *
     * Update authorization
     *
     * @param  string $authorizationKey The key of the authorization to delete. (required)
     * @param  \Camunda\Orchestration\Api\Model\AuthorizationIdBasedRequest|\Camunda\Orchestration\Api\Model\AuthorizationPropertyBasedRequest $authorizationRequest authorizationRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['updateAuthorization'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function updateAuthorization(string $authorizationKey, \Camunda\Orchestration\Api\Model\AuthorizationIdBasedRequest|\Camunda\Orchestration\Api\Model\AuthorizationPropertyBasedRequest $authorizationRequest, string $contentType = \Camunda\Orchestration\Api\Api\AuthorizationApi::contentTypes['updateAuthorization'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\AuthorizationApi::class)->updateAuthorization($authorizationKey, $authorizationRequest, $contentType);
    }

    /**
     * Operation deleteHistoryBackup
     *
     * Delete history backup
     *
     * @param  int $backupId The id of the backup. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteHistoryBackup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function deleteHistoryBackup(int $backupId, string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['deleteHistoryBackup'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->deleteHistoryBackup($backupId, $contentType);
    }

    /**
     * Operation deleteHistoryBackupAsClusterAdmin
     *
     * Delete a history backup across physical tenants
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  int $backupId The id of the backup. (required)
     * @param  string|null $physicalTenantId The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. (optional)
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteHistoryBackupAsClusterAdmin'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function deleteHistoryBackupAsClusterAdmin(int $backupId, ?string $physicalTenantId = null, ?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['deleteHistoryBackupAsClusterAdmin'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->deleteHistoryBackupAsClusterAdmin($backupId, $physicalTenantId, $hostIndex, $variables, $contentType);
    }

    /**
     * Operation deleteRuntimeBackup
     *
     * Delete runtime backup
     *
     * @param  int $backupId The id of the backup. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteRuntimeBackup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function deleteRuntimeBackup(int $backupId, string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['deleteRuntimeBackup'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->deleteRuntimeBackup($backupId, $contentType);
    }

    /**
     * Operation deleteRuntimeBackupAsClusterAdmin
     *
     * Delete a runtime backup across physical tenants
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  int $backupId The id of the backup. (required)
     * @param  string|null $physicalTenantId The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. (optional)
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteRuntimeBackupAsClusterAdmin'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function deleteRuntimeBackupAsClusterAdmin(int $backupId, ?string $physicalTenantId = null, ?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['deleteRuntimeBackupAsClusterAdmin'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->deleteRuntimeBackupAsClusterAdmin($backupId, $physicalTenantId, $hostIndex, $variables, $contentType);
    }

    /**
     * Operation deleteRuntimeBackupState
     *
     * Delete runtime backup state
     *
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteRuntimeBackupState'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function deleteRuntimeBackupState(string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['deleteRuntimeBackupState'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->deleteRuntimeBackupState($contentType);
    }

    /**
     * Operation deleteRuntimeBackupStateAsClusterAdmin
     *
     * Delete runtime backup state across physical tenants
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  string|null $physicalTenantId The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. (optional)
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteRuntimeBackupStateAsClusterAdmin'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function deleteRuntimeBackupStateAsClusterAdmin(?string $physicalTenantId = null, ?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['deleteRuntimeBackupStateAsClusterAdmin'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->deleteRuntimeBackupStateAsClusterAdmin($physicalTenantId, $hostIndex, $variables, $contentType);
    }

    /**
     * Operation getHistoryBackup
     *
     * Get history backup
     *
     * @param  int $backupId The id of the backup. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getHistoryBackup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\HistoryBackupInfo|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getHistoryBackup(int $backupId, string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['getHistoryBackup'][0]): \Camunda\Orchestration\Api\Model\HistoryBackupInfo|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->getHistoryBackup($backupId, $contentType);
    }

    /**
     * Operation getHistoryBackupAsClusterAdmin
     *
     * Get a history backup across physical tenants
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  int $backupId The id of the backup. (required)
     * @param  string|null $physicalTenantId The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. (optional)
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getHistoryBackupAsClusterAdmin'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterHistoryBackupInfo|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getHistoryBackupAsClusterAdmin(int $backupId, ?string $physicalTenantId = null, ?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['getHistoryBackupAsClusterAdmin'][0]): \Camunda\Orchestration\Api\Model\ClusterHistoryBackupInfo|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->getHistoryBackupAsClusterAdmin($backupId, $physicalTenantId, $hostIndex, $variables, $contentType);
    }

    /**
     * Operation getRuntimeBackup
     *
     * Get runtime backup
     *
     * @param  int $backupId The id of the backup. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getRuntimeBackup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\BackupInfo|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getRuntimeBackup(int $backupId, string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['getRuntimeBackup'][0]): \Camunda\Orchestration\Api\Model\BackupInfo|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->getRuntimeBackup($backupId, $contentType);
    }

    /**
     * Operation getRuntimeBackupAsClusterAdmin
     *
     * Get a runtime backup across physical tenants
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  int $backupId The id of the backup. (required)
     * @param  string|null $physicalTenantId The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. (optional)
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getRuntimeBackupAsClusterAdmin'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterRuntimeBackupInfo|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getRuntimeBackupAsClusterAdmin(int $backupId, ?string $physicalTenantId = null, ?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['getRuntimeBackupAsClusterAdmin'][0]): \Camunda\Orchestration\Api\Model\ClusterRuntimeBackupInfo|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->getRuntimeBackupAsClusterAdmin($backupId, $physicalTenantId, $hostIndex, $variables, $contentType);
    }

    /**
     * Operation getRuntimeBackupState
     *
     * Get runtime backup state
     *
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getRuntimeBackupState'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\RuntimeBackupState|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getRuntimeBackupState(string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['getRuntimeBackupState'][0]): \Camunda\Orchestration\Api\Model\RuntimeBackupState|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->getRuntimeBackupState($contentType);
    }

    /**
     * Operation getRuntimeBackupStateAsClusterAdmin
     *
     * Get runtime backup state across physical tenants
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  string|null $physicalTenantId The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. (optional)
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getRuntimeBackupStateAsClusterAdmin'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterRuntimeBackupState|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getRuntimeBackupStateAsClusterAdmin(?string $physicalTenantId = null, ?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['getRuntimeBackupStateAsClusterAdmin'][0]): \Camunda\Orchestration\Api\Model\ClusterRuntimeBackupState|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->getRuntimeBackupStateAsClusterAdmin($physicalTenantId, $hostIndex, $variables, $contentType);
    }

    /**
     * Operation listHistoryBackups
     *
     * List history backups
     *
     * @param  string|null $prefix A prefix that backup ids must match, ending in a single &#39;*&#39;. If omitted, all backups are returned. (optional)
     * @param  bool|null $verbose Whether to ask the secondary storage for snapshot-level detail. Setting this to &#x60;false&#x60; makes the query cheaper, but the store then reports neither snapshot state nor start time, so both the per-snapshot &#x60;details&#x60; and the aggregated &#x60;state&#x60; are incomplete and the listing order is unspecified. (optional, default to true)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['listHistoryBackups'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\HistoryBackupInfo[]|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function listHistoryBackups(?string $prefix = null, ?bool $verbose = true, string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['listHistoryBackups'][0]): array|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->listHistoryBackups($prefix, $verbose, $contentType);
    }

    /**
     * Operation listHistoryBackupsAsClusterAdmin
     *
     * List history backups across physical tenants
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  string|null $physicalTenantId The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. (optional)
     * @param  string|null $prefix A prefix that backup ids must match, ending in a single &#39;*&#39;. If omitted, all backups are returned. (optional)
     * @param  bool|null $verbose Whether to ask the secondary storage for snapshot-level detail. Setting this to &#x60;false&#x60; makes the query cheaper, but the store then reports neither snapshot state nor start time, so both the per-snapshot &#x60;details&#x60; and the per-tenant &#x60;state&#x60; are incomplete and the listing order is unspecified. (optional, default to true)
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['listHistoryBackupsAsClusterAdmin'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterHistoryBackupInfo[]|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function listHistoryBackupsAsClusterAdmin(?string $physicalTenantId = null, ?string $prefix = null, ?bool $verbose = true, ?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['listHistoryBackupsAsClusterAdmin'][0]): array|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->listHistoryBackupsAsClusterAdmin($physicalTenantId, $prefix, $verbose, $hostIndex, $variables, $contentType);
    }

    /**
     * Operation listRuntimeBackups
     *
     * List runtime backups
     *
     * @param  string|null $prefix A prefix that backup ids must match, ending in a single &#39;*&#39;. If omitted, all backups are returned. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['listRuntimeBackups'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\BackupInfo[]|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function listRuntimeBackups(?string $prefix = null, string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['listRuntimeBackups'][0]): array|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->listRuntimeBackups($prefix, $contentType);
    }

    /**
     * Operation listRuntimeBackupsAsClusterAdmin
     *
     * List runtime backups across physical tenants
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  string|null $physicalTenantId The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. (optional)
     * @param  string|null $prefix A prefix that backup ids must match, ending in a single &#39;*&#39;. If omitted, all backups are returned. (optional)
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['listRuntimeBackupsAsClusterAdmin'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterRuntimeBackupInfo[]|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function listRuntimeBackupsAsClusterAdmin(?string $physicalTenantId = null, ?string $prefix = null, ?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['listRuntimeBackupsAsClusterAdmin'][0]): array|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->listRuntimeBackupsAsClusterAdmin($physicalTenantId, $prefix, $hostIndex, $variables, $contentType);
    }

    /**
     * Operation syncRuntimeBackupState
     *
     * Force-write runtime backup state
     *
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['syncRuntimeBackupState'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\RuntimeBackupState|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function syncRuntimeBackupState(string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['syncRuntimeBackupState'][0]): \Camunda\Orchestration\Api\Model\RuntimeBackupState|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->syncRuntimeBackupState($contentType);
    }

    /**
     * Operation syncRuntimeBackupStateAsClusterAdmin
     *
     * Force-write runtime backup state across physical tenants
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  string|null $physicalTenantId The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. (optional)
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['syncRuntimeBackupStateAsClusterAdmin'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterRuntimeBackupState|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function syncRuntimeBackupStateAsClusterAdmin(?string $physicalTenantId = null, ?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['syncRuntimeBackupStateAsClusterAdmin'][0]): \Camunda\Orchestration\Api\Model\ClusterRuntimeBackupState|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->syncRuntimeBackupStateAsClusterAdmin($physicalTenantId, $hostIndex, $variables, $contentType);
    }

    /**
     * Operation takeHistoryBackup
     *
     * Take a history backup
     *
     * @param  \Camunda\Orchestration\Api\Model\TakeHistoryBackupRequest $takeHistoryBackupRequest takeHistoryBackupRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['takeHistoryBackup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\TakeHistoryBackupResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function takeHistoryBackup(\Camunda\Orchestration\Api\Model\TakeHistoryBackupRequest $takeHistoryBackupRequest, string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['takeHistoryBackup'][0]): \Camunda\Orchestration\Api\Model\TakeHistoryBackupResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->takeHistoryBackup($takeHistoryBackupRequest, $contentType);
    }

    /**
     * Operation takeHistoryBackupAsClusterAdmin
     *
     * Take a history backup on one or every physical tenant
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  \Camunda\Orchestration\Api\Model\TakeHistoryBackupRequest $takeHistoryBackupRequest takeHistoryBackupRequest (required)
     * @param  string|null $physicalTenantId The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. (optional)
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['takeHistoryBackupAsClusterAdmin'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterTakeHistoryBackupResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function takeHistoryBackupAsClusterAdmin(\Camunda\Orchestration\Api\Model\TakeHistoryBackupRequest $takeHistoryBackupRequest, ?string $physicalTenantId = null, ?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['takeHistoryBackupAsClusterAdmin'][0]): \Camunda\Orchestration\Api\Model\ClusterTakeHistoryBackupResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->takeHistoryBackupAsClusterAdmin($takeHistoryBackupRequest, $physicalTenantId, $hostIndex, $variables, $contentType);
    }

    /**
     * Operation takeRuntimeBackup
     *
     * Take a runtime backup
     *
     * @param  \Camunda\Orchestration\Api\Model\TakeRuntimeBackupRequest|null $takeRuntimeBackupRequest takeRuntimeBackupRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['takeRuntimeBackup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\TakeRuntimeBackupResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function takeRuntimeBackup(?\Camunda\Orchestration\Api\Model\TakeRuntimeBackupRequest $takeRuntimeBackupRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['takeRuntimeBackup'][0]): \Camunda\Orchestration\Api\Model\TakeRuntimeBackupResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->takeRuntimeBackup($takeRuntimeBackupRequest, $contentType);
    }

    /**
     * Operation takeRuntimeBackupAsClusterAdmin
     *
     * Take a runtime backup on one or every physical tenant
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  string|null $physicalTenantId The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. (optional)
     * @param  \Camunda\Orchestration\Api\Model\TakeRuntimeBackupRequest|null $takeRuntimeBackupRequest takeRuntimeBackupRequest (optional)
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['takeRuntimeBackupAsClusterAdmin'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterTakeRuntimeBackupResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function takeRuntimeBackupAsClusterAdmin(?string $physicalTenantId = null, ?\Camunda\Orchestration\Api\Model\TakeRuntimeBackupRequest $takeRuntimeBackupRequest = null, ?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\BackupApi::contentTypes['takeRuntimeBackupAsClusterAdmin'][0]): \Camunda\Orchestration\Api\Model\ClusterTakeRuntimeBackupResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class)->takeRuntimeBackupAsClusterAdmin($physicalTenantId, $takeRuntimeBackupRequest, $hostIndex, $variables, $contentType);
    }

    /**
     * Operation cancelBatchOperation
     *
     * Cancel Batch operation
     *
     * @param  string $batchOperationKey The key (or operate legacy ID) of the batch operation. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['cancelBatchOperation'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function cancelBatchOperation(string $batchOperationKey, string $contentType = \Camunda\Orchestration\Api\Api\BatchOperationApi::contentTypes['cancelBatchOperation'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BatchOperationApi::class)->cancelBatchOperation($batchOperationKey, $contentType);
    }

    /**
     * Operation getBatchOperation
     *
     * Get batch operation
     *
     * @param  string $batchOperationKey The key (or operate legacy ID) of the batch operation. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getBatchOperation'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\BatchOperationResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getBatchOperation(string $batchOperationKey, string $contentType = \Camunda\Orchestration\Api\Api\BatchOperationApi::contentTypes['getBatchOperation'][0]): \Camunda\Orchestration\Api\Model\BatchOperationResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BatchOperationApi::class)->getBatchOperation($batchOperationKey, $contentType);
    }

    /**
     * Operation resumeBatchOperation
     *
     * Resume Batch operation
     *
     * @param  string $batchOperationKey The key (or operate legacy ID) of the batch operation. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['resumeBatchOperation'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function resumeBatchOperation(string $batchOperationKey, string $contentType = \Camunda\Orchestration\Api\Api\BatchOperationApi::contentTypes['resumeBatchOperation'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BatchOperationApi::class)->resumeBatchOperation($batchOperationKey, $contentType);
    }

    /**
     * Operation searchBatchOperationItems
     *
     * Search batch operation items
     *
     * @param  \Camunda\Orchestration\Api\Model\BatchOperationItemSearchQuery|null $batchOperationItemSearchQuery batchOperationItemSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchBatchOperationItems'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\BatchOperationItemSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchBatchOperationItems(?\Camunda\Orchestration\Api\Model\BatchOperationItemSearchQuery $batchOperationItemSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\BatchOperationApi::contentTypes['searchBatchOperationItems'][0]): \Camunda\Orchestration\Api\Model\BatchOperationItemSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BatchOperationApi::class)->searchBatchOperationItems($batchOperationItemSearchQuery, $contentType);
    }

    /**
     * Operation searchBatchOperations
     *
     * Search batch operations
     *
     * @param  \Camunda\Orchestration\Api\Model\BatchOperationSearchQuery|null $batchOperationSearchQuery batchOperationSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchBatchOperations'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\BatchOperationSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchBatchOperations(?\Camunda\Orchestration\Api\Model\BatchOperationSearchQuery $batchOperationSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\BatchOperationApi::contentTypes['searchBatchOperations'][0]): \Camunda\Orchestration\Api\Model\BatchOperationSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BatchOperationApi::class)->searchBatchOperations($batchOperationSearchQuery, $contentType);
    }

    /**
     * Operation suspendBatchOperation
     *
     * Suspend Batch operation
     *
     * @param  string $batchOperationKey The key (or operate legacy ID) of the batch operation. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['suspendBatchOperation'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function suspendBatchOperation(string $batchOperationKey, string $contentType = \Camunda\Orchestration\Api\Api\BatchOperationApi::contentTypes['suspendBatchOperation'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\BatchOperationApi::class)->suspendBatchOperation($batchOperationKey, $contentType);
    }

    /**
     * Operation pinClock
     *
     * Pin internal clock (alpha)
     *
     * @param  \Camunda\Orchestration\Api\Model\ClockPinRequest $clockPinRequest clockPinRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['pinClock'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function pinClock(\Camunda\Orchestration\Api\Model\ClockPinRequest $clockPinRequest, string $contentType = \Camunda\Orchestration\Api\Api\ClockApi::contentTypes['pinClock'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ClockApi::class)->pinClock($clockPinRequest, $contentType);
    }

    /**
     * Operation resetClock
     *
     * Reset internal clock (alpha)
     *
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['resetClock'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function resetClock(string $contentType = \Camunda\Orchestration\Api\Api\ClockApi::contentTypes['resetClock'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ClockApi::class)->resetClock($contentType);
    }

    /**
     * Operation cancelClusterRebalance
     *
     * Stop the running rebalance
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['cancelClusterRebalance'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\RebalanceCancellationResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function cancelClusterRebalance(?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\ClusterApi::contentTypes['cancelClusterRebalance'][0]): \Camunda\Orchestration\Api\Model\RebalanceCancellationResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ClusterApi::class)->cancelClusterRebalance($hostIndex, $variables, $contentType);
    }

    /**
     * Operation getClusterRebalance
     *
     * Report the cluster&#39;s current leadership balance
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getClusterRebalance'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterBalanceResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getClusterRebalance(?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\ClusterApi::contentTypes['getClusterRebalance'][0]): \Camunda\Orchestration\Api\Model\ClusterBalanceResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ClusterApi::class)->getClusterRebalance($hostIndex, $variables, $contentType);
    }

    /**
     * Operation getClusterStatus
     *
     * Get the status of the whole cluster
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getClusterStatus'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterStatusResponse
     */
    public function getClusterStatus(?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\ClusterApi::contentTypes['getClusterStatus'][0]): \Camunda\Orchestration\Api\Model\ClusterStatusResponse
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ClusterApi::class)->getClusterStatus($hostIndex, $variables, $contentType);
    }

    /**
     * Operation getClusterTopology
     *
     * Get the topology of the whole cluster
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getClusterTopology'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterTopologyResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getClusterTopology(?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\ClusterApi::contentTypes['getClusterTopology'][0]): \Camunda\Orchestration\Api\Model\ClusterTopologyResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ClusterApi::class)->getClusterTopology($hostIndex, $variables, $contentType);
    }

    /**
     * Operation getStatus
     *
     * Get physical tenant status
     *
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getStatus'] to see the possible values for this operation
     *
     * @return void
     */
    public function getStatus(string $contentType = \Camunda\Orchestration\Api\Api\ClusterApi::contentTypes['getStatus'][0]): void
    {
        $this->api(\Camunda\Orchestration\Api\Api\ClusterApi::class)->getStatus($contentType);
    }

    /**
     * Operation getTopology
     *
     * Get cluster topology
     *
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getTopology'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\TopologyResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getTopology(string $contentType = \Camunda\Orchestration\Api\Api\ClusterApi::contentTypes['getTopology'][0]): \Camunda\Orchestration\Api\Model\TopologyResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ClusterApi::class)->getTopology($contentType);
    }

    /**
     * Operation triggerClusterRebalance
     *
     * Trigger a cluster-wide leadership rebalance
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  bool|null $dryRun If true, report the plan the rebalance would carry out without pausing any partition or transferring any leadership. (optional, default to false)
     * @param  \Camunda\Orchestration\Api\Model\ClusterRebalanceRequest|null $clusterRebalanceRequest clusterRebalanceRequest (optional)
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['triggerClusterRebalance'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterBalanceResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function triggerClusterRebalance(?bool $dryRun = false, ?\Camunda\Orchestration\Api\Model\ClusterRebalanceRequest $clusterRebalanceRequest = null, ?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\ClusterApi::contentTypes['triggerClusterRebalance'][0]): \Camunda\Orchestration\Api\Model\ClusterBalanceResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ClusterApi::class)->triggerClusterRebalance($dryRun, $clusterRebalanceRequest, $hostIndex, $variables, $contentType);
    }

    /**
     * Operation createGlobalClusterVariable
     *
     * Create a global-scoped cluster variable
     *
     * @param  \Camunda\Orchestration\Api\Model\CreateClusterVariableRequest $createClusterVariableRequest createClusterVariableRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['createGlobalClusterVariable'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterVariableResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function createGlobalClusterVariable(\Camunda\Orchestration\Api\Model\CreateClusterVariableRequest $createClusterVariableRequest, string $contentType = \Camunda\Orchestration\Api\Api\ClusterVariableApi::contentTypes['createGlobalClusterVariable'][0]): \Camunda\Orchestration\Api\Model\ClusterVariableResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ClusterVariableApi::class)->createGlobalClusterVariable($createClusterVariableRequest, $contentType);
    }

    /**
     * Operation createTenantClusterVariable
     *
     * Create a tenant-scoped cluster variable
     *
     * @param  string $tenantId The tenant ID (required)
     * @param  \Camunda\Orchestration\Api\Model\CreateClusterVariableRequest $createClusterVariableRequest createClusterVariableRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['createTenantClusterVariable'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterVariableResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function createTenantClusterVariable(string $tenantId, \Camunda\Orchestration\Api\Model\CreateClusterVariableRequest $createClusterVariableRequest, string $contentType = \Camunda\Orchestration\Api\Api\ClusterVariableApi::contentTypes['createTenantClusterVariable'][0]): \Camunda\Orchestration\Api\Model\ClusterVariableResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ClusterVariableApi::class)->createTenantClusterVariable($tenantId, $createClusterVariableRequest, $contentType);
    }

    /**
     * Operation deleteGlobalClusterVariable
     *
     * Delete a global-scoped cluster variable
     *
     * @param  string $name The name of the cluster variable (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteGlobalClusterVariable'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function deleteGlobalClusterVariable(string $name, string $contentType = \Camunda\Orchestration\Api\Api\ClusterVariableApi::contentTypes['deleteGlobalClusterVariable'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ClusterVariableApi::class)->deleteGlobalClusterVariable($name, $contentType);
    }

    /**
     * Operation deleteTenantClusterVariable
     *
     * Delete a tenant-scoped cluster variable
     *
     * @param  string $tenantId The tenant ID (required)
     * @param  string $name The name of the cluster variable (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteTenantClusterVariable'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function deleteTenantClusterVariable(string $tenantId, string $name, string $contentType = \Camunda\Orchestration\Api\Api\ClusterVariableApi::contentTypes['deleteTenantClusterVariable'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ClusterVariableApi::class)->deleteTenantClusterVariable($tenantId, $name, $contentType);
    }

    /**
     * Operation getGlobalClusterVariable
     *
     * Get a global-scoped cluster variable
     *
     * @param  string $name The name of the cluster variable (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getGlobalClusterVariable'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterVariableResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getGlobalClusterVariable(string $name, string $contentType = \Camunda\Orchestration\Api\Api\ClusterVariableApi::contentTypes['getGlobalClusterVariable'][0]): \Camunda\Orchestration\Api\Model\ClusterVariableResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ClusterVariableApi::class)->getGlobalClusterVariable($name, $contentType);
    }

    /**
     * Operation getTenantClusterVariable
     *
     * Get a tenant-scoped cluster variable
     *
     * @param  string $tenantId The tenant ID (required)
     * @param  string $name The name of the cluster variable (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getTenantClusterVariable'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterVariableResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getTenantClusterVariable(string $tenantId, string $name, string $contentType = \Camunda\Orchestration\Api\Api\ClusterVariableApi::contentTypes['getTenantClusterVariable'][0]): \Camunda\Orchestration\Api\Model\ClusterVariableResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ClusterVariableApi::class)->getTenantClusterVariable($tenantId, $name, $contentType);
    }

    /**
     * Operation searchClusterVariables
     *
     * @param  bool|null $truncateValues When true (default), long variable values in the response are truncated. When false, full variable values are returned. (optional)
     * @param  \Camunda\Orchestration\Api\Model\ClusterVariableSearchQueryRequest|null $clusterVariableSearchQueryRequest clusterVariableSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchClusterVariables'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterVariableSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchClusterVariables(?bool $truncateValues = null, ?\Camunda\Orchestration\Api\Model\ClusterVariableSearchQueryRequest $clusterVariableSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\ClusterVariableApi::contentTypes['searchClusterVariables'][0]): \Camunda\Orchestration\Api\Model\ClusterVariableSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ClusterVariableApi::class)->searchClusterVariables($truncateValues, $clusterVariableSearchQueryRequest, $contentType);
    }

    /**
     * Operation updateGlobalClusterVariable
     *
     * Update a global-scoped cluster variable
     *
     * @param  string $name The name of the cluster variable (required)
     * @param  \Camunda\Orchestration\Api\Model\UpdateClusterVariableRequest $updateClusterVariableRequest updateClusterVariableRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['updateGlobalClusterVariable'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterVariableResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function updateGlobalClusterVariable(string $name, \Camunda\Orchestration\Api\Model\UpdateClusterVariableRequest $updateClusterVariableRequest, string $contentType = \Camunda\Orchestration\Api\Api\ClusterVariableApi::contentTypes['updateGlobalClusterVariable'][0]): \Camunda\Orchestration\Api\Model\ClusterVariableResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ClusterVariableApi::class)->updateGlobalClusterVariable($name, $updateClusterVariableRequest, $contentType);
    }

    /**
     * Operation updateTenantClusterVariable
     *
     * Update a tenant-scoped cluster variable
     *
     * @param  string $tenantId The tenant ID (required)
     * @param  string $name The name of the cluster variable (required)
     * @param  \Camunda\Orchestration\Api\Model\UpdateClusterVariableRequest $updateClusterVariableRequest updateClusterVariableRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['updateTenantClusterVariable'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterVariableResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function updateTenantClusterVariable(string $tenantId, string $name, \Camunda\Orchestration\Api\Model\UpdateClusterVariableRequest $updateClusterVariableRequest, string $contentType = \Camunda\Orchestration\Api\Api\ClusterVariableApi::contentTypes['updateTenantClusterVariable'][0]): \Camunda\Orchestration\Api\Model\ClusterVariableResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ClusterVariableApi::class)->updateTenantClusterVariable($tenantId, $name, $updateClusterVariableRequest, $contentType);
    }

    /**
     * Operation evaluateConditionals
     *
     * Evaluate root level conditional start events
     *
     * @param  \Camunda\Orchestration\Api\Model\ConditionalEvaluationInstruction $conditionalEvaluationInstruction conditionalEvaluationInstruction (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['evaluateConditionals'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\EvaluateConditionalResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function evaluateConditionals(\Camunda\Orchestration\Api\Model\ConditionalEvaluationInstruction $conditionalEvaluationInstruction, string $contentType = \Camunda\Orchestration\Api\Api\ConditionalApi::contentTypes['evaluateConditionals'][0]): \Camunda\Orchestration\Api\Model\EvaluateConditionalResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ConditionalApi::class)->evaluateConditionals($conditionalEvaluationInstruction, $contentType);
    }

    /**
     * Operation evaluateDecision
     *
     * Evaluate decision
     *
     * @param  \Camunda\Orchestration\Api\Model\DecisionEvaluationById|\Camunda\Orchestration\Api\Model\DecisionEvaluationByKey $decisionEvaluationInstruction decisionEvaluationInstruction (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['evaluateDecision'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\EvaluateDecisionResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function evaluateDecision(\Camunda\Orchestration\Api\Model\DecisionEvaluationById|\Camunda\Orchestration\Api\Model\DecisionEvaluationByKey $decisionEvaluationInstruction, string $contentType = \Camunda\Orchestration\Api\Api\DecisionDefinitionApi::contentTypes['evaluateDecision'][0]): \Camunda\Orchestration\Api\Model\EvaluateDecisionResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\DecisionDefinitionApi::class)->evaluateDecision($decisionEvaluationInstruction, $contentType);
    }

    /**
     * Operation getDecisionDefinition
     *
     * Get decision definition
     *
     * @param  string $decisionDefinitionKey The assigned key of the decision definition, which acts as a unique identifier for this decision. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getDecisionDefinition'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\DecisionDefinitionResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getDecisionDefinition(string $decisionDefinitionKey, string $contentType = \Camunda\Orchestration\Api\Api\DecisionDefinitionApi::contentTypes['getDecisionDefinition'][0]): \Camunda\Orchestration\Api\Model\DecisionDefinitionResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\DecisionDefinitionApi::class)->getDecisionDefinition($decisionDefinitionKey, $contentType);
    }

    /**
     * Operation getDecisionDefinitionXML
     *
     * Get decision definition XML
     *
     * @param  string $decisionDefinitionKey The assigned key of the decision definition, which acts as a unique identifier for this decision. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getDecisionDefinitionXML'] to see the possible values for this operation
     *
     * @return string|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getDecisionDefinitionXML(string $decisionDefinitionKey, string $contentType = \Camunda\Orchestration\Api\Api\DecisionDefinitionApi::contentTypes['getDecisionDefinitionXML'][0]): string|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\DecisionDefinitionApi::class)->getDecisionDefinitionXML($decisionDefinitionKey, $contentType);
    }

    /**
     * Operation searchDecisionDefinitions
     *
     * Search decision definitions
     *
     * @param  \Camunda\Orchestration\Api\Model\DecisionDefinitionSearchQuery|null $decisionDefinitionSearchQuery decisionDefinitionSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchDecisionDefinitions'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\DecisionDefinitionSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchDecisionDefinitions(?\Camunda\Orchestration\Api\Model\DecisionDefinitionSearchQuery $decisionDefinitionSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\DecisionDefinitionApi::contentTypes['searchDecisionDefinitions'][0]): \Camunda\Orchestration\Api\Model\DecisionDefinitionSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\DecisionDefinitionApi::class)->searchDecisionDefinitions($decisionDefinitionSearchQuery, $contentType);
    }

    /**
     * Operation deleteDecisionInstance
     *
     * Delete decision instance
     *
     * @param  string $decisionEvaluationKey The key of the decision evaluation to delete. (required)
     * @param  \Camunda\Orchestration\Api\Model\DeleteDecisionInstanceRequest|null $deleteDecisionInstanceRequest deleteDecisionInstanceRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteDecisionInstance'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function deleteDecisionInstance(string $decisionEvaluationKey, ?\Camunda\Orchestration\Api\Model\DeleteDecisionInstanceRequest $deleteDecisionInstanceRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\DecisionInstanceApi::contentTypes['deleteDecisionInstance'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\DecisionInstanceApi::class)->deleteDecisionInstance($decisionEvaluationKey, $deleteDecisionInstanceRequest, $contentType);
    }

    /**
     * Operation deleteDecisionInstancesBatchOperation
     *
     * Delete decision instances (batch)
     *
     * @param  \Camunda\Orchestration\Api\Model\DecisionInstanceDeletionBatchOperationRequest $decisionInstanceDeletionBatchOperationRequest decisionInstanceDeletionBatchOperationRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteDecisionInstancesBatchOperation'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function deleteDecisionInstancesBatchOperation(\Camunda\Orchestration\Api\Model\DecisionInstanceDeletionBatchOperationRequest $decisionInstanceDeletionBatchOperationRequest, string $contentType = \Camunda\Orchestration\Api\Api\DecisionInstanceApi::contentTypes['deleteDecisionInstancesBatchOperation'][0]): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\DecisionInstanceApi::class)->deleteDecisionInstancesBatchOperation($decisionInstanceDeletionBatchOperationRequest, $contentType);
    }

    /**
     * Operation getDecisionInstance
     *
     * Get decision instance
     *
     * @param  string $decisionEvaluationInstanceKey The assigned key of the decision instance, which acts as a unique identifier for this decision instance. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getDecisionInstance'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\DecisionInstanceGetQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getDecisionInstance(string $decisionEvaluationInstanceKey, string $contentType = \Camunda\Orchestration\Api\Api\DecisionInstanceApi::contentTypes['getDecisionInstance'][0]): \Camunda\Orchestration\Api\Model\DecisionInstanceGetQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\DecisionInstanceApi::class)->getDecisionInstance($decisionEvaluationInstanceKey, $contentType);
    }

    /**
     * Operation searchDecisionInstances
     *
     * Search decision instances
     *
     * @param  \Camunda\Orchestration\Api\Model\DecisionInstanceSearchQuery|null $decisionInstanceSearchQuery decisionInstanceSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchDecisionInstances'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\DecisionInstanceSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchDecisionInstances(?\Camunda\Orchestration\Api\Model\DecisionInstanceSearchQuery $decisionInstanceSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\DecisionInstanceApi::contentTypes['searchDecisionInstances'][0]): \Camunda\Orchestration\Api\Model\DecisionInstanceSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\DecisionInstanceApi::class)->searchDecisionInstances($decisionInstanceSearchQuery, $contentType);
    }

    /**
     * Operation getDecisionRequirements
     *
     * Get decision requirements
     *
     * @param  string $decisionRequirementsKey The assigned key of the decision requirements, which acts as a unique identifier for this decision requirements. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getDecisionRequirements'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\DecisionRequirementsResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getDecisionRequirements(string $decisionRequirementsKey, string $contentType = \Camunda\Orchestration\Api\Api\DecisionRequirementsApi::contentTypes['getDecisionRequirements'][0]): \Camunda\Orchestration\Api\Model\DecisionRequirementsResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\DecisionRequirementsApi::class)->getDecisionRequirements($decisionRequirementsKey, $contentType);
    }

    /**
     * Operation getDecisionRequirementsXML
     *
     * Get decision requirements XML
     *
     * @param  string $decisionRequirementsKey The assigned key of the decision requirements, which acts as a unique identifier for this decision. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getDecisionRequirementsXML'] to see the possible values for this operation
     *
     * @return string|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getDecisionRequirementsXML(string $decisionRequirementsKey, string $contentType = \Camunda\Orchestration\Api\Api\DecisionRequirementsApi::contentTypes['getDecisionRequirementsXML'][0]): string|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\DecisionRequirementsApi::class)->getDecisionRequirementsXML($decisionRequirementsKey, $contentType);
    }

    /**
     * Operation searchDecisionRequirements
     *
     * Search decision requirements
     *
     * @param  \Camunda\Orchestration\Api\Model\DecisionRequirementsSearchQuery|null $decisionRequirementsSearchQuery decisionRequirementsSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchDecisionRequirements'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\DecisionRequirementsSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchDecisionRequirements(?\Camunda\Orchestration\Api\Model\DecisionRequirementsSearchQuery $decisionRequirementsSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\DecisionRequirementsApi::contentTypes['searchDecisionRequirements'][0]): \Camunda\Orchestration\Api\Model\DecisionRequirementsSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\DecisionRequirementsApi::class)->searchDecisionRequirements($decisionRequirementsSearchQuery, $contentType);
    }

    /**
     * Operation createDocument
     *
     * Upload document
     *
     * @param  \SplFileObject $file file (required)
     * @param  string|null $storeId The ID of the document store to upload the documents to. Currently, only a single document store is supported per cluster. However, this attribute is included to allow for potential future support of multiple document stores. (optional)
     * @param  string|null $documentId The ID of the document to upload. If not provided, a new ID will be generated. Specifying an existing ID will result in an error if the document already exists. (optional)
     * @param  \Camunda\Orchestration\Api\Model\DocumentMetadata|null $metadata metadata (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['createDocument'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\DocumentReference|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function createDocument(\SplFileObject $file, ?string $storeId = null, ?string $documentId = null, ?\Camunda\Orchestration\Api\Model\DocumentMetadata $metadata = null, string $contentType = \Camunda\Orchestration\Api\Api\DocumentApi::contentTypes['createDocument'][0]): \Camunda\Orchestration\Api\Model\DocumentReference|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\DocumentApi::class)->createDocument($file, $storeId, $documentId, $metadata, $contentType);
    }

    /**
     * Operation createDocumentLink
     *
     * Create document link
     *
     * @param  string $documentId The ID of the document to link. (required)
     * @param  string|null $storeId The ID of the document store where the document is located. (optional)
     * @param  string|null $contentHash The hash of the document content that was computed by the document store during upload. The hash is part of the document reference that is returned when uploading a document. If the client fails to provide the correct hash, the request will be rejected. (optional)
     * @param  \Camunda\Orchestration\Api\Model\DocumentLinkRequest|null $documentLinkRequest documentLinkRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['createDocumentLink'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\DocumentLink|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function createDocumentLink(string $documentId, ?string $storeId = null, ?string $contentHash = null, ?\Camunda\Orchestration\Api\Model\DocumentLinkRequest $documentLinkRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\DocumentApi::contentTypes['createDocumentLink'][0]): \Camunda\Orchestration\Api\Model\DocumentLink|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\DocumentApi::class)->createDocumentLink($documentId, $storeId, $contentHash, $documentLinkRequest, $contentType);
    }

    /**
     * Operation createDocuments
     *
     * Upload multiple documents
     *
     * @param  \SplFileObject[] $files The documents to upload. (required)
     * @param  string|null $storeId The ID of the document store to upload the documents to. Currently, only a single document store is supported per cluster. However, this attribute is included to allow for potential future support of multiple document stores. (optional)
     * @param  \Camunda\Orchestration\Api\Model\DocumentMetadata[]|null $metadataList Optional JSON array of metadata object whose index aligns with each file entry. The metadata array must have the same length as the files array. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['createDocuments'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\DocumentCreationBatchResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function createDocuments(array $files, ?string $storeId = null, ?array $metadataList = null, string $contentType = \Camunda\Orchestration\Api\Api\DocumentApi::contentTypes['createDocuments'][0]): \Camunda\Orchestration\Api\Model\DocumentCreationBatchResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\DocumentApi::class)->createDocuments($files, $storeId, $metadataList, $contentType);
    }

    /**
     * Operation deleteDocument
     *
     * Delete document
     *
     * @param  string $documentId The ID of the document to delete. (required)
     * @param  string|null $storeId The ID of the document store to delete the document from. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteDocument'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function deleteDocument(string $documentId, ?string $storeId = null, string $contentType = \Camunda\Orchestration\Api\Api\DocumentApi::contentTypes['deleteDocument'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\DocumentApi::class)->deleteDocument($documentId, $storeId, $contentType);
    }

    /**
     * Operation getDocument
     *
     * Download document
     *
     * @param  string $documentId The ID of the document to download. (required)
     * @param  string|null $storeId The ID of the document store to download the document from. (optional)
     * @param  string|null $contentHash The hash of the document content that was computed by the document store during upload. The hash is part of the document reference that is returned when uploading a document. If the client fails to provide the correct hash, the request will be rejected. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getDocument'] to see the possible values for this operation
     *
     * @return \SplFileObject|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getDocument(string $documentId, ?string $storeId = null, ?string $contentHash = null, string $contentType = \Camunda\Orchestration\Api\Api\DocumentApi::contentTypes['getDocument'][0]): \SplFileObject|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\DocumentApi::class)->getDocument($documentId, $storeId, $contentHash, $contentType);
    }

    /**
     * Operation createElementInstanceVariables
     *
     * Update element instance variables
     *
     * @param  string $elementInstanceKey The key of the element instance to update the variables for. This can be the process instance key (as obtained during instance creation), or a given element, such as a service task (see the &#x60;elementInstanceKey&#x60; on the job message). (required)
     * @param  \Camunda\Orchestration\Api\Model\SetVariableRequest $setVariableRequest setVariableRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['createElementInstanceVariables'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function createElementInstanceVariables(string $elementInstanceKey, \Camunda\Orchestration\Api\Model\SetVariableRequest $setVariableRequest, string $contentType = \Camunda\Orchestration\Api\Api\ElementInstanceApi::contentTypes['createElementInstanceVariables'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ElementInstanceApi::class)->createElementInstanceVariables($elementInstanceKey, $setVariableRequest, $contentType);
    }

    /**
     * Operation getElementInstance
     *
     * Get element instance
     *
     * @param  string $elementInstanceKey The assigned key of the element instance, which acts as a unique identifier for this element instance. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getElementInstance'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ElementInstanceResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getElementInstance(string $elementInstanceKey, string $contentType = \Camunda\Orchestration\Api\Api\ElementInstanceApi::contentTypes['getElementInstance'][0]): \Camunda\Orchestration\Api\Model\ElementInstanceResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ElementInstanceApi::class)->getElementInstance($elementInstanceKey, $contentType);
    }

    /**
     * Operation searchElementInstanceIncidents
     *
     * Search for incidents of a specific element instance
     *
     * @param  string $elementInstanceKey The unique key of the element instance to search incidents for. (required)
     * @param  \Camunda\Orchestration\Api\Model\IncidentSearchQuery $incidentSearchQuery incidentSearchQuery (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchElementInstanceIncidents'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\IncidentSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchElementInstanceIncidents(string $elementInstanceKey, \Camunda\Orchestration\Api\Model\IncidentSearchQuery $incidentSearchQuery, string $contentType = \Camunda\Orchestration\Api\Api\ElementInstanceApi::contentTypes['searchElementInstanceIncidents'][0]): \Camunda\Orchestration\Api\Model\IncidentSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ElementInstanceApi::class)->searchElementInstanceIncidents($elementInstanceKey, $incidentSearchQuery, $contentType);
    }

    /**
     * Operation searchElementInstanceWaitStates
     *
     * Search element instance wait states
     *
     * @param  \Camunda\Orchestration\Api\Model\ElementInstanceWaitStateQuery|null $elementInstanceWaitStateQuery elementInstanceWaitStateQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchElementInstanceWaitStates'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ElementInstanceWaitStateQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchElementInstanceWaitStates(?\Camunda\Orchestration\Api\Model\ElementInstanceWaitStateQuery $elementInstanceWaitStateQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\ElementInstanceApi::contentTypes['searchElementInstanceWaitStates'][0]): \Camunda\Orchestration\Api\Model\ElementInstanceWaitStateQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ElementInstanceApi::class)->searchElementInstanceWaitStates($elementInstanceWaitStateQuery, $contentType);
    }

    /**
     * Operation searchElementInstances
     *
     * Search element instances
     *
     * @param  \Camunda\Orchestration\Api\Model\ElementInstanceSearchQuery|null $elementInstanceSearchQuery elementInstanceSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchElementInstances'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ElementInstanceSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchElementInstances(?\Camunda\Orchestration\Api\Model\ElementInstanceSearchQuery $elementInstanceSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\ElementInstanceApi::contentTypes['searchElementInstances'][0]): \Camunda\Orchestration\Api\Model\ElementInstanceSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ElementInstanceApi::class)->searchElementInstances($elementInstanceSearchQuery, $contentType);
    }

    /**
     * Operation getClusterExportingStatus
     *
     * Get exporting status of the whole cluster
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getClusterExportingStatus'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ExportingStatusResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getClusterExportingStatus(?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\ExportingApi::contentTypes['getClusterExportingStatus'][0]): \Camunda\Orchestration\Api\Model\ExportingStatusResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ExportingApi::class)->getClusterExportingStatus($hostIndex, $variables, $contentType);
    }

    /**
     * Operation getExportingStatus
     *
     * Get exporting status
     *
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getExportingStatus'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ExportingStatusResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getExportingStatus(string $contentType = \Camunda\Orchestration\Api\Api\ExportingApi::contentTypes['getExportingStatus'][0]): \Camunda\Orchestration\Api\Model\ExportingStatusResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ExportingApi::class)->getExportingStatus($contentType);
    }

    /**
     * Operation pauseClusterExporting
     *
     * Pause exporting across the whole cluster
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  bool|null $soft If true, soft-pauses exporting instead of a hard pause. (optional, default to false)
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['pauseClusterExporting'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function pauseClusterExporting(?bool $soft = false, ?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\ExportingApi::contentTypes['pauseClusterExporting'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ExportingApi::class)->pauseClusterExporting($soft, $hostIndex, $variables, $contentType);
    }

    /**
     * Operation pauseExporting
     *
     * Pause exporting
     *
     * @param  bool|null $soft If true, soft-pauses exporting instead of a hard pause. (optional, default to false)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['pauseExporting'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function pauseExporting(?bool $soft = false, string $contentType = \Camunda\Orchestration\Api\Api\ExportingApi::contentTypes['pauseExporting'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ExportingApi::class)->pauseExporting($soft, $contentType);
    }

    /**
     * Operation resumeClusterExporting
     *
     * Resume exporting across the whole cluster
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['resumeClusterExporting'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function resumeClusterExporting(?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\ExportingApi::contentTypes['resumeClusterExporting'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ExportingApi::class)->resumeClusterExporting($hostIndex, $variables, $contentType);
    }

    /**
     * Operation resumeExporting
     *
     * Resume exporting
     *
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['resumeExporting'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function resumeExporting(string $contentType = \Camunda\Orchestration\Api\Api\ExportingApi::contentTypes['resumeExporting'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ExportingApi::class)->resumeExporting($contentType);
    }

    /**
     * Operation evaluateExpression
     *
     * Evaluate an expression
     *
     * @param  \Camunda\Orchestration\Api\Model\ExpressionEvaluationRequest $expressionEvaluationRequest expressionEvaluationRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['evaluateExpression'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ExpressionEvaluationResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function evaluateExpression(\Camunda\Orchestration\Api\Model\ExpressionEvaluationRequest $expressionEvaluationRequest, string $contentType = \Camunda\Orchestration\Api\Api\ExpressionApi::contentTypes['evaluateExpression'][0]): \Camunda\Orchestration\Api\Model\ExpressionEvaluationResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ExpressionApi::class)->evaluateExpression($expressionEvaluationRequest, $contentType);
    }

    /**
     * Operation getFormByKey
     *
     * Get form by key
     *
     * @param  string $formKey The form key. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getFormByKey'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\FormResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getFormByKey(string $formKey, string $contentType = \Camunda\Orchestration\Api\Api\FormApi::contentTypes['getFormByKey'][0]): \Camunda\Orchestration\Api\Model\FormResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\FormApi::class)->getFormByKey($formKey, $contentType);
    }

    /**
     * Operation createGlobalTaskListener
     *
     * Create global user task listener
     *
     * @param  \Camunda\Orchestration\Api\Model\CreateGlobalTaskListenerRequest $createGlobalTaskListenerRequest createGlobalTaskListenerRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['createGlobalTaskListener'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\GlobalTaskListenerResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function createGlobalTaskListener(\Camunda\Orchestration\Api\Model\CreateGlobalTaskListenerRequest $createGlobalTaskListenerRequest, string $contentType = \Camunda\Orchestration\Api\Api\GlobalListenerApi::contentTypes['createGlobalTaskListener'][0]): \Camunda\Orchestration\Api\Model\GlobalTaskListenerResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GlobalListenerApi::class)->createGlobalTaskListener($createGlobalTaskListenerRequest, $contentType);
    }

    /**
     * Operation deleteGlobalTaskListener
     *
     * Delete global user task listener
     *
     * @param  string $id The id of the global user task listener to delete. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteGlobalTaskListener'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function deleteGlobalTaskListener(string $id, string $contentType = \Camunda\Orchestration\Api\Api\GlobalListenerApi::contentTypes['deleteGlobalTaskListener'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GlobalListenerApi::class)->deleteGlobalTaskListener($id, $contentType);
    }

    /**
     * Operation getGlobalTaskListener
     *
     * Get global user task listener
     *
     * @param  string $id The id of the global user task listener. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getGlobalTaskListener'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\GlobalTaskListenerResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getGlobalTaskListener(string $id, string $contentType = \Camunda\Orchestration\Api\Api\GlobalListenerApi::contentTypes['getGlobalTaskListener'][0]): \Camunda\Orchestration\Api\Model\GlobalTaskListenerResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GlobalListenerApi::class)->getGlobalTaskListener($id, $contentType);
    }

    /**
     * Operation searchGlobalTaskListeners
     *
     * Search global user task listeners
     *
     * @param  \Camunda\Orchestration\Api\Model\GlobalTaskListenerSearchQueryRequest|null $globalTaskListenerSearchQueryRequest globalTaskListenerSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchGlobalTaskListeners'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\GlobalTaskListenerSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchGlobalTaskListeners(?\Camunda\Orchestration\Api\Model\GlobalTaskListenerSearchQueryRequest $globalTaskListenerSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\GlobalListenerApi::contentTypes['searchGlobalTaskListeners'][0]): \Camunda\Orchestration\Api\Model\GlobalTaskListenerSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GlobalListenerApi::class)->searchGlobalTaskListeners($globalTaskListenerSearchQueryRequest, $contentType);
    }

    /**
     * Operation updateGlobalTaskListener
     *
     * Update global user task listener
     *
     * @param  string $id The id of the global user task listener to update. (required)
     * @param  \Camunda\Orchestration\Api\Model\UpdateGlobalTaskListenerRequest $updateGlobalTaskListenerRequest updateGlobalTaskListenerRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['updateGlobalTaskListener'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\GlobalTaskListenerResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function updateGlobalTaskListener(string $id, \Camunda\Orchestration\Api\Model\UpdateGlobalTaskListenerRequest $updateGlobalTaskListenerRequest, string $contentType = \Camunda\Orchestration\Api\Api\GlobalListenerApi::contentTypes['updateGlobalTaskListener'][0]): \Camunda\Orchestration\Api\Model\GlobalTaskListenerResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GlobalListenerApi::class)->updateGlobalTaskListener($id, $updateGlobalTaskListenerRequest, $contentType);
    }

    /**
     * Operation assignClientToGroup
     *
     * Assign a client to a group
     *
     * @param  string $groupId The group ID. (required)
     * @param  string $clientId The client ID. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['assignClientToGroup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function assignClientToGroup(string $groupId, string $clientId, string $contentType = \Camunda\Orchestration\Api\Api\GroupApi::contentTypes['assignClientToGroup'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GroupApi::class)->assignClientToGroup($groupId, $clientId, $contentType);
    }

    /**
     * Operation assignMappingRuleToGroup
     *
     * Assign a mapping rule to a group
     *
     * @param  string $groupId The group ID. (required)
     * @param  string $mappingRuleId The mapping rule ID. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['assignMappingRuleToGroup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function assignMappingRuleToGroup(string $groupId, string $mappingRuleId, string $contentType = \Camunda\Orchestration\Api\Api\GroupApi::contentTypes['assignMappingRuleToGroup'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GroupApi::class)->assignMappingRuleToGroup($groupId, $mappingRuleId, $contentType);
    }

    /**
     * Operation assignUserToGroup
     *
     * Assign a user to a group
     *
     * @param  string $groupId The group ID. (required)
     * @param  string $username The user username. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['assignUserToGroup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function assignUserToGroup(string $groupId, string $username, string $contentType = \Camunda\Orchestration\Api\Api\GroupApi::contentTypes['assignUserToGroup'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GroupApi::class)->assignUserToGroup($groupId, $username, $contentType);
    }

    /**
     * Operation createGroup
     *
     * Create group
     *
     * @param  \Camunda\Orchestration\Api\Model\GroupCreateRequest|null $groupCreateRequest groupCreateRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['createGroup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\GroupCreateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function createGroup(?\Camunda\Orchestration\Api\Model\GroupCreateRequest $groupCreateRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\GroupApi::contentTypes['createGroup'][0]): \Camunda\Orchestration\Api\Model\GroupCreateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GroupApi::class)->createGroup($groupCreateRequest, $contentType);
    }

    /**
     * Operation deleteGroup
     *
     * Delete group
     *
     * @param  string $groupId The group ID. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteGroup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function deleteGroup(string $groupId, string $contentType = \Camunda\Orchestration\Api\Api\GroupApi::contentTypes['deleteGroup'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GroupApi::class)->deleteGroup($groupId, $contentType);
    }

    /**
     * Operation getGroup
     *
     * Get group
     *
     * @param  string $groupId The group ID. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getGroup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\GroupResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getGroup(string $groupId, string $contentType = \Camunda\Orchestration\Api\Api\GroupApi::contentTypes['getGroup'][0]): \Camunda\Orchestration\Api\Model\GroupResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GroupApi::class)->getGroup($groupId, $contentType);
    }

    /**
     * Operation searchClientsForGroup
     *
     * Search group clients
     *
     * @param  string $groupId The group ID. (required)
     * @param  \Camunda\Orchestration\Api\Model\GroupClientSearchQueryRequest|null $groupClientSearchQueryRequest groupClientSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchClientsForGroup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\GroupClientSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchClientsForGroup(string $groupId, ?\Camunda\Orchestration\Api\Model\GroupClientSearchQueryRequest $groupClientSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\GroupApi::contentTypes['searchClientsForGroup'][0]): \Camunda\Orchestration\Api\Model\GroupClientSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GroupApi::class)->searchClientsForGroup($groupId, $groupClientSearchQueryRequest, $contentType);
    }

    /**
     * Operation searchGroups
     *
     * Search groups
     *
     * @param  \Camunda\Orchestration\Api\Model\GroupSearchQueryRequest|null $groupSearchQueryRequest groupSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchGroups'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\GroupSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchGroups(?\Camunda\Orchestration\Api\Model\GroupSearchQueryRequest $groupSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\GroupApi::contentTypes['searchGroups'][0]): \Camunda\Orchestration\Api\Model\GroupSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GroupApi::class)->searchGroups($groupSearchQueryRequest, $contentType);
    }

    /**
     * Operation searchMappingRulesForGroup
     *
     * Search group mapping rules
     *
     * @param  string $groupId The group ID. (required)
     * @param  \Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest|null $mappingRuleSearchQueryRequest mappingRuleSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchMappingRulesForGroup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\GroupMappingRuleSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchMappingRulesForGroup(string $groupId, ?\Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest $mappingRuleSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\GroupApi::contentTypes['searchMappingRulesForGroup'][0]): \Camunda\Orchestration\Api\Model\GroupMappingRuleSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GroupApi::class)->searchMappingRulesForGroup($groupId, $mappingRuleSearchQueryRequest, $contentType);
    }

    /**
     * Operation searchRolesForGroup
     *
     * Search group roles
     *
     * @param  string $groupId The group ID. (required)
     * @param  \Camunda\Orchestration\Api\Model\RoleSearchQueryRequest|null $roleSearchQueryRequest roleSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchRolesForGroup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\GroupRoleSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchRolesForGroup(string $groupId, ?\Camunda\Orchestration\Api\Model\RoleSearchQueryRequest $roleSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\GroupApi::contentTypes['searchRolesForGroup'][0]): \Camunda\Orchestration\Api\Model\GroupRoleSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GroupApi::class)->searchRolesForGroup($groupId, $roleSearchQueryRequest, $contentType);
    }

    /**
     * Operation searchUsersForGroup
     *
     * Search group users
     *
     * @param  string $groupId The group ID. (required)
     * @param  \Camunda\Orchestration\Api\Model\GroupUserSearchQueryRequest|null $groupUserSearchQueryRequest groupUserSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchUsersForGroup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\GroupUserSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchUsersForGroup(string $groupId, ?\Camunda\Orchestration\Api\Model\GroupUserSearchQueryRequest $groupUserSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\GroupApi::contentTypes['searchUsersForGroup'][0]): \Camunda\Orchestration\Api\Model\GroupUserSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GroupApi::class)->searchUsersForGroup($groupId, $groupUserSearchQueryRequest, $contentType);
    }

    /**
     * Operation unassignClientFromGroup
     *
     * Unassign a client from a group
     *
     * @param  string $groupId The group ID. (required)
     * @param  string $clientId The client ID. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['unassignClientFromGroup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function unassignClientFromGroup(string $groupId, string $clientId, string $contentType = \Camunda\Orchestration\Api\Api\GroupApi::contentTypes['unassignClientFromGroup'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GroupApi::class)->unassignClientFromGroup($groupId, $clientId, $contentType);
    }

    /**
     * Operation unassignMappingRuleFromGroup
     *
     * Unassign a mapping rule from a group
     *
     * @param  string $groupId The group ID. (required)
     * @param  string $mappingRuleId The mapping rule ID. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['unassignMappingRuleFromGroup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function unassignMappingRuleFromGroup(string $groupId, string $mappingRuleId, string $contentType = \Camunda\Orchestration\Api\Api\GroupApi::contentTypes['unassignMappingRuleFromGroup'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GroupApi::class)->unassignMappingRuleFromGroup($groupId, $mappingRuleId, $contentType);
    }

    /**
     * Operation unassignUserFromGroup
     *
     * Unassign a user from a group
     *
     * @param  string $groupId The group ID. (required)
     * @param  string $username The user username. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['unassignUserFromGroup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function unassignUserFromGroup(string $groupId, string $username, string $contentType = \Camunda\Orchestration\Api\Api\GroupApi::contentTypes['unassignUserFromGroup'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GroupApi::class)->unassignUserFromGroup($groupId, $username, $contentType);
    }

    /**
     * Operation updateGroup
     *
     * Update group
     *
     * @param  string $groupId The group ID. (required)
     * @param  \Camunda\Orchestration\Api\Model\GroupUpdateRequest $groupUpdateRequest groupUpdateRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['updateGroup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\GroupUpdateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function updateGroup(string $groupId, \Camunda\Orchestration\Api\Model\GroupUpdateRequest $groupUpdateRequest, string $contentType = \Camunda\Orchestration\Api\Api\GroupApi::contentTypes['updateGroup'][0]): \Camunda\Orchestration\Api\Model\GroupUpdateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\GroupApi::class)->updateGroup($groupId, $groupUpdateRequest, $contentType);
    }

    /**
     * Operation getIncident
     *
     * Get incident
     *
     * @param  string $incidentKey The assigned key of the incident, which acts as a unique identifier for this incident. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getIncident'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\IncidentResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getIncident(string $incidentKey, string $contentType = \Camunda\Orchestration\Api\Api\IncidentApi::contentTypes['getIncident'][0]): \Camunda\Orchestration\Api\Model\IncidentResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\IncidentApi::class)->getIncident($incidentKey, $contentType);
    }

    /**
     * Operation getProcessInstanceStatisticsByDefinition
     *
     * Get process instance statistics by definition
     *
     * @param  \Camunda\Orchestration\Api\Model\IncidentProcessInstanceStatisticsByDefinitionQuery $incidentProcessInstanceStatisticsByDefinitionQuery incidentProcessInstanceStatisticsByDefinitionQuery (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getProcessInstanceStatisticsByDefinition'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\IncidentProcessInstanceStatisticsByDefinitionQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getProcessInstanceStatisticsByDefinition(\Camunda\Orchestration\Api\Model\IncidentProcessInstanceStatisticsByDefinitionQuery $incidentProcessInstanceStatisticsByDefinitionQuery, string $contentType = \Camunda\Orchestration\Api\Api\IncidentApi::contentTypes['getProcessInstanceStatisticsByDefinition'][0]): \Camunda\Orchestration\Api\Model\IncidentProcessInstanceStatisticsByDefinitionQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\IncidentApi::class)->getProcessInstanceStatisticsByDefinition($incidentProcessInstanceStatisticsByDefinitionQuery, $contentType);
    }

    /**
     * Operation getProcessInstanceStatisticsByError
     *
     * Get process instance statistics by error
     *
     * @param  \Camunda\Orchestration\Api\Model\IncidentProcessInstanceStatisticsByErrorQuery|null $incidentProcessInstanceStatisticsByErrorQuery incidentProcessInstanceStatisticsByErrorQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getProcessInstanceStatisticsByError'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\IncidentProcessInstanceStatisticsByErrorQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getProcessInstanceStatisticsByError(?\Camunda\Orchestration\Api\Model\IncidentProcessInstanceStatisticsByErrorQuery $incidentProcessInstanceStatisticsByErrorQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\IncidentApi::contentTypes['getProcessInstanceStatisticsByError'][0]): \Camunda\Orchestration\Api\Model\IncidentProcessInstanceStatisticsByErrorQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\IncidentApi::class)->getProcessInstanceStatisticsByError($incidentProcessInstanceStatisticsByErrorQuery, $contentType);
    }

    /**
     * Operation resolveIncident
     *
     * Resolve incident
     *
     * @param  string $incidentKey Key of the incident to resolve. (required)
     * @param  \Camunda\Orchestration\Api\Model\IncidentResolutionRequest|null $incidentResolutionRequest incidentResolutionRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['resolveIncident'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function resolveIncident(string $incidentKey, ?\Camunda\Orchestration\Api\Model\IncidentResolutionRequest $incidentResolutionRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\IncidentApi::contentTypes['resolveIncident'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\IncidentApi::class)->resolveIncident($incidentKey, $incidentResolutionRequest, $contentType);
    }

    /**
     * Operation searchIncidents
     *
     * Search incidents
     *
     * @param  \Camunda\Orchestration\Api\Model\IncidentSearchQuery|null $incidentSearchQuery incidentSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchIncidents'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\IncidentSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchIncidents(?\Camunda\Orchestration\Api\Model\IncidentSearchQuery $incidentSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\IncidentApi::contentTypes['searchIncidents'][0]): \Camunda\Orchestration\Api\Model\IncidentSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\IncidentApi::class)->searchIncidents($incidentSearchQuery, $contentType);
    }

    /**
     * Operation activateJobs
     *
     * Activate jobs
     *
     * @param  \Camunda\Orchestration\Api\Model\JobActivationRequest $jobActivationRequest jobActivationRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['activateJobs'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\JobActivationResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function activateJobs(\Camunda\Orchestration\Api\Model\JobActivationRequest $jobActivationRequest, string $contentType = \Camunda\Orchestration\Api\Api\JobApi::contentTypes['activateJobs'][0]): \Camunda\Orchestration\Api\Model\JobActivationResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\JobApi::class)->activateJobs($jobActivationRequest, $contentType);
    }

    /**
     * Operation completeJob
     *
     * Complete job
     *
     * @param  string $jobKey The key of the job to complete. (required)
     * @param  \Camunda\Orchestration\Api\Model\JobCompletionRequest|null $jobCompletionRequest jobCompletionRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['completeJob'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function completeJob(string $jobKey, ?\Camunda\Orchestration\Api\Model\JobCompletionRequest $jobCompletionRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\JobApi::contentTypes['completeJob'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\JobApi::class)->completeJob($jobKey, $jobCompletionRequest, $contentType);
    }

    /**
     * Operation failJob
     *
     * Fail job
     *
     * @param  string $jobKey The key of the job to fail. (required)
     * @param  \Camunda\Orchestration\Api\Model\JobFailRequest|null $jobFailRequest jobFailRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['failJob'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function failJob(string $jobKey, ?\Camunda\Orchestration\Api\Model\JobFailRequest $jobFailRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\JobApi::contentTypes['failJob'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\JobApi::class)->failJob($jobKey, $jobFailRequest, $contentType);
    }

    /**
     * Operation getGlobalJobStatistics
     *
     * Global job statistics
     *
     * @param  \DateTime $from Start of the time window to filter metrics. ISO 8601 date-time format. (required)
     * @param  \DateTime $to End of the time window to filter metrics. ISO 8601 date-time format. (required)
     * @param  string|null $jobType Optional job type to limit the aggregation to a single job type. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getGlobalJobStatistics'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\GlobalJobStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getGlobalJobStatistics(\DateTime $from, \DateTime $to, ?string $jobType = null, string $contentType = \Camunda\Orchestration\Api\Api\JobApi::contentTypes['getGlobalJobStatistics'][0]): \Camunda\Orchestration\Api\Model\GlobalJobStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\JobApi::class)->getGlobalJobStatistics($from, $to, $jobType, $contentType);
    }

    /**
     * Operation getJobErrorStatistics
     *
     * Get error metrics for a job type
     *
     * @param  \Camunda\Orchestration\Api\Model\JobErrorStatisticsQuery $jobErrorStatisticsQuery jobErrorStatisticsQuery (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getJobErrorStatistics'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\JobErrorStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getJobErrorStatistics(\Camunda\Orchestration\Api\Model\JobErrorStatisticsQuery $jobErrorStatisticsQuery, string $contentType = \Camunda\Orchestration\Api\Api\JobApi::contentTypes['getJobErrorStatistics'][0]): \Camunda\Orchestration\Api\Model\JobErrorStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\JobApi::class)->getJobErrorStatistics($jobErrorStatisticsQuery, $contentType);
    }

    /**
     * Operation getJobTimeSeriesStatistics
     *
     * Get time-series metrics for a job type
     *
     * @param  \Camunda\Orchestration\Api\Model\JobTimeSeriesStatisticsQuery $jobTimeSeriesStatisticsQuery jobTimeSeriesStatisticsQuery (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getJobTimeSeriesStatistics'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\JobTimeSeriesStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getJobTimeSeriesStatistics(\Camunda\Orchestration\Api\Model\JobTimeSeriesStatisticsQuery $jobTimeSeriesStatisticsQuery, string $contentType = \Camunda\Orchestration\Api\Api\JobApi::contentTypes['getJobTimeSeriesStatistics'][0]): \Camunda\Orchestration\Api\Model\JobTimeSeriesStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\JobApi::class)->getJobTimeSeriesStatistics($jobTimeSeriesStatisticsQuery, $contentType);
    }

    /**
     * Operation getJobTypeStatistics
     *
     * Get job statistics by type
     *
     * @param  \Camunda\Orchestration\Api\Model\JobTypeStatisticsQuery $jobTypeStatisticsQuery jobTypeStatisticsQuery (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getJobTypeStatistics'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\JobTypeStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getJobTypeStatistics(\Camunda\Orchestration\Api\Model\JobTypeStatisticsQuery $jobTypeStatisticsQuery, string $contentType = \Camunda\Orchestration\Api\Api\JobApi::contentTypes['getJobTypeStatistics'][0]): \Camunda\Orchestration\Api\Model\JobTypeStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\JobApi::class)->getJobTypeStatistics($jobTypeStatisticsQuery, $contentType);
    }

    /**
     * Operation getJobWorkerStatistics
     *
     * Get job statistics by worker
     *
     * @param  \Camunda\Orchestration\Api\Model\JobWorkerStatisticsQuery $jobWorkerStatisticsQuery jobWorkerStatisticsQuery (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getJobWorkerStatistics'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\JobWorkerStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getJobWorkerStatistics(\Camunda\Orchestration\Api\Model\JobWorkerStatisticsQuery $jobWorkerStatisticsQuery, string $contentType = \Camunda\Orchestration\Api\Api\JobApi::contentTypes['getJobWorkerStatistics'][0]): \Camunda\Orchestration\Api\Model\JobWorkerStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\JobApi::class)->getJobWorkerStatistics($jobWorkerStatisticsQuery, $contentType);
    }

    /**
     * Operation searchJobs
     *
     * Search jobs
     *
     * @param  \Camunda\Orchestration\Api\Model\JobSearchQuery|null $jobSearchQuery jobSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchJobs'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\JobSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchJobs(?\Camunda\Orchestration\Api\Model\JobSearchQuery $jobSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\JobApi::contentTypes['searchJobs'][0]): \Camunda\Orchestration\Api\Model\JobSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\JobApi::class)->searchJobs($jobSearchQuery, $contentType);
    }

    /**
     * Operation throwJobError
     *
     * Throw error for job
     *
     * @param  string $jobKey The key of the job. (required)
     * @param  \Camunda\Orchestration\Api\Model\JobErrorRequest $jobErrorRequest jobErrorRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['throwJobError'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function throwJobError(string $jobKey, \Camunda\Orchestration\Api\Model\JobErrorRequest $jobErrorRequest, string $contentType = \Camunda\Orchestration\Api\Api\JobApi::contentTypes['throwJobError'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\JobApi::class)->throwJobError($jobKey, $jobErrorRequest, $contentType);
    }

    /**
     * Operation updateJob
     *
     * Update job
     *
     * @param  string $jobKey The key of the job to update. (required)
     * @param  \Camunda\Orchestration\Api\Model\JobUpdateRequest $jobUpdateRequest jobUpdateRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['updateJob'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function updateJob(string $jobKey, \Camunda\Orchestration\Api\Model\JobUpdateRequest $jobUpdateRequest, string $contentType = \Camunda\Orchestration\Api\Api\JobApi::contentTypes['updateJob'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\JobApi::class)->updateJob($jobKey, $jobUpdateRequest, $contentType);
    }

    /**
     * Operation updateJobsBatchOperation
     *
     * Update jobs (batch)
     *
     * @param  \Camunda\Orchestration\Api\Model\JobBatchUpdateRequest $jobBatchUpdateRequest jobBatchUpdateRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['updateJobsBatchOperation'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function updateJobsBatchOperation(\Camunda\Orchestration\Api\Model\JobBatchUpdateRequest $jobBatchUpdateRequest, string $contentType = \Camunda\Orchestration\Api\Api\JobApi::contentTypes['updateJobsBatchOperation'][0]): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\JobApi::class)->updateJobsBatchOperation($jobBatchUpdateRequest, $contentType);
    }

    /**
     * Operation getLicense
     *
     * Get license status
     *
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getLicense'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\LicenseResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getLicense(string $contentType = \Camunda\Orchestration\Api\Api\LicenseApi::contentTypes['getLicense'][0]): \Camunda\Orchestration\Api\Model\LicenseResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\LicenseApi::class)->getLicense($contentType);
    }

    /**
     * Operation createMappingRule
     *
     * Create mapping rule
     *
     * @param  \Camunda\Orchestration\Api\Model\MappingRuleCreateRequest|null $mappingRuleCreateRequest mappingRuleCreateRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['createMappingRule'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\MappingRuleCreateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function createMappingRule(?\Camunda\Orchestration\Api\Model\MappingRuleCreateRequest $mappingRuleCreateRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\MappingRuleApi::contentTypes['createMappingRule'][0]): \Camunda\Orchestration\Api\Model\MappingRuleCreateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\MappingRuleApi::class)->createMappingRule($mappingRuleCreateRequest, $contentType);
    }

    /**
     * Operation deleteMappingRule
     *
     * Delete a mapping rule
     *
     * @param  string $mappingRuleId The ID of the mapping rule to delete. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteMappingRule'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function deleteMappingRule(string $mappingRuleId, string $contentType = \Camunda\Orchestration\Api\Api\MappingRuleApi::contentTypes['deleteMappingRule'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\MappingRuleApi::class)->deleteMappingRule($mappingRuleId, $contentType);
    }

    /**
     * Operation getMappingRule
     *
     * Get a mapping rule
     *
     * @param  string $mappingRuleId The ID of the mapping rule to get. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getMappingRule'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\MappingRuleResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getMappingRule(string $mappingRuleId, string $contentType = \Camunda\Orchestration\Api\Api\MappingRuleApi::contentTypes['getMappingRule'][0]): \Camunda\Orchestration\Api\Model\MappingRuleResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\MappingRuleApi::class)->getMappingRule($mappingRuleId, $contentType);
    }

    /**
     * Operation searchMappingRule
     *
     * Search mapping rules
     *
     * @param  \Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest|null $mappingRuleSearchQueryRequest mappingRuleSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchMappingRule'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\MappingRuleSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchMappingRule(?\Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest $mappingRuleSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\MappingRuleApi::contentTypes['searchMappingRule'][0]): \Camunda\Orchestration\Api\Model\MappingRuleSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\MappingRuleApi::class)->searchMappingRule($mappingRuleSearchQueryRequest, $contentType);
    }

    /**
     * Operation updateMappingRule
     *
     * Update mapping rule
     *
     * @param  string $mappingRuleId The ID of the mapping rule to update. (required)
     * @param  \Camunda\Orchestration\Api\Model\MappingRuleUpdateRequest|null $mappingRuleUpdateRequest mappingRuleUpdateRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['updateMappingRule'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\MappingRuleUpdateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function updateMappingRule(string $mappingRuleId, ?\Camunda\Orchestration\Api\Model\MappingRuleUpdateRequest $mappingRuleUpdateRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\MappingRuleApi::contentTypes['updateMappingRule'][0]): \Camunda\Orchestration\Api\Model\MappingRuleUpdateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\MappingRuleApi::class)->updateMappingRule($mappingRuleId, $mappingRuleUpdateRequest, $contentType);
    }

    /**
     * Operation correlateMessage
     *
     * Correlate message
     *
     * @param  \Camunda\Orchestration\Api\Model\MessageCorrelationRequest $messageCorrelationRequest messageCorrelationRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['correlateMessage'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\MessageCorrelationResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function correlateMessage(\Camunda\Orchestration\Api\Model\MessageCorrelationRequest $messageCorrelationRequest, string $contentType = \Camunda\Orchestration\Api\Api\MessageApi::contentTypes['correlateMessage'][0]): \Camunda\Orchestration\Api\Model\MessageCorrelationResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\MessageApi::class)->correlateMessage($messageCorrelationRequest, $contentType);
    }

    /**
     * Operation publishMessage
     *
     * Publish message
     *
     * @param  \Camunda\Orchestration\Api\Model\MessagePublicationRequest $messagePublicationRequest messagePublicationRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['publishMessage'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\MessagePublicationResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function publishMessage(\Camunda\Orchestration\Api\Model\MessagePublicationRequest $messagePublicationRequest, string $contentType = \Camunda\Orchestration\Api\Api\MessageApi::contentTypes['publishMessage'][0]): \Camunda\Orchestration\Api\Model\MessagePublicationResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\MessageApi::class)->publishMessage($messagePublicationRequest, $contentType);
    }

    /**
     * Operation searchCorrelatedMessageSubscriptions
     *
     * Search correlated message subscriptions
     *
     * @param  \Camunda\Orchestration\Api\Model\CorrelatedMessageSubscriptionSearchQuery|null $correlatedMessageSubscriptionSearchQuery correlatedMessageSubscriptionSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchCorrelatedMessageSubscriptions'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\CorrelatedMessageSubscriptionSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchCorrelatedMessageSubscriptions(?\Camunda\Orchestration\Api\Model\CorrelatedMessageSubscriptionSearchQuery $correlatedMessageSubscriptionSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\MessageSubscriptionApi::contentTypes['searchCorrelatedMessageSubscriptions'][0]): \Camunda\Orchestration\Api\Model\CorrelatedMessageSubscriptionSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\MessageSubscriptionApi::class)->searchCorrelatedMessageSubscriptions($correlatedMessageSubscriptionSearchQuery, $contentType);
    }

    /**
     * Operation searchMessageSubscriptions
     *
     * Search message subscriptions
     *
     * @param  \Camunda\Orchestration\Api\Model\MessageSubscriptionSearchQuery|null $messageSubscriptionSearchQuery messageSubscriptionSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchMessageSubscriptions'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\MessageSubscriptionSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchMessageSubscriptions(?\Camunda\Orchestration\Api\Model\MessageSubscriptionSearchQuery $messageSubscriptionSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\MessageSubscriptionApi::contentTypes['searchMessageSubscriptions'][0]): \Camunda\Orchestration\Api\Model\MessageSubscriptionSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\MessageSubscriptionApi::class)->searchMessageSubscriptions($messageSubscriptionSearchQuery, $contentType);
    }

    /**
     * Operation getProcessDefinition
     *
     * Get process definition
     *
     * @param  string $processDefinitionKey The assigned key of the process definition, which acts as a unique identifier for this process definition. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getProcessDefinition'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProcessDefinitionResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getProcessDefinition(string $processDefinitionKey, string $contentType = \Camunda\Orchestration\Api\Api\ProcessDefinitionApi::contentTypes['getProcessDefinition'][0]): \Camunda\Orchestration\Api\Model\ProcessDefinitionResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessDefinitionApi::class)->getProcessDefinition($processDefinitionKey, $contentType);
    }

    /**
     * Operation getProcessDefinitionInstanceStatistics
     *
     * Get process instance statistics
     *
     * @param  \Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceStatisticsQuery|null $processDefinitionInstanceStatisticsQuery processDefinitionInstanceStatisticsQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getProcessDefinitionInstanceStatistics'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getProcessDefinitionInstanceStatistics(?\Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceStatisticsQuery $processDefinitionInstanceStatisticsQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\ProcessDefinitionApi::contentTypes['getProcessDefinitionInstanceStatistics'][0]): \Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessDefinitionApi::class)->getProcessDefinitionInstanceStatistics($processDefinitionInstanceStatisticsQuery, $contentType);
    }

    /**
     * Operation getProcessDefinitionInstanceVersionStatistics
     *
     * Get process instance statistics by version
     *
     * @param  \Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceVersionStatisticsQuery $processDefinitionInstanceVersionStatisticsQuery processDefinitionInstanceVersionStatisticsQuery (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getProcessDefinitionInstanceVersionStatistics'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceVersionStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getProcessDefinitionInstanceVersionStatistics(\Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceVersionStatisticsQuery $processDefinitionInstanceVersionStatisticsQuery, string $contentType = \Camunda\Orchestration\Api\Api\ProcessDefinitionApi::contentTypes['getProcessDefinitionInstanceVersionStatistics'][0]): \Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceVersionStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessDefinitionApi::class)->getProcessDefinitionInstanceVersionStatistics($processDefinitionInstanceVersionStatisticsQuery, $contentType);
    }

    /**
     * Operation getProcessDefinitionMessageSubscriptionStatistics
     *
     * Get message subscription statistics
     *
     * @param  \Camunda\Orchestration\Api\Model\ProcessDefinitionMessageSubscriptionStatisticsQuery|null $processDefinitionMessageSubscriptionStatisticsQuery processDefinitionMessageSubscriptionStatisticsQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getProcessDefinitionMessageSubscriptionStatistics'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProcessDefinitionMessageSubscriptionStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getProcessDefinitionMessageSubscriptionStatistics(?\Camunda\Orchestration\Api\Model\ProcessDefinitionMessageSubscriptionStatisticsQuery $processDefinitionMessageSubscriptionStatisticsQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\ProcessDefinitionApi::contentTypes['getProcessDefinitionMessageSubscriptionStatistics'][0]): \Camunda\Orchestration\Api\Model\ProcessDefinitionMessageSubscriptionStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessDefinitionApi::class)->getProcessDefinitionMessageSubscriptionStatistics($processDefinitionMessageSubscriptionStatisticsQuery, $contentType);
    }

    /**
     * Operation getProcessDefinitionStatistics
     *
     * Get process definition statistics
     *
     * @param  string $processDefinitionKey The assigned key of the process definition, which acts as a unique identifier for this process definition. (required)
     * @param  \Camunda\Orchestration\Api\Model\ProcessDefinitionElementStatisticsQuery|null $processDefinitionElementStatisticsQuery processDefinitionElementStatisticsQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getProcessDefinitionStatistics'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProcessDefinitionElementStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getProcessDefinitionStatistics(string $processDefinitionKey, ?\Camunda\Orchestration\Api\Model\ProcessDefinitionElementStatisticsQuery $processDefinitionElementStatisticsQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\ProcessDefinitionApi::contentTypes['getProcessDefinitionStatistics'][0]): \Camunda\Orchestration\Api\Model\ProcessDefinitionElementStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessDefinitionApi::class)->getProcessDefinitionStatistics($processDefinitionKey, $processDefinitionElementStatisticsQuery, $contentType);
    }

    /**
     * Operation getProcessDefinitionXML
     *
     * Get process definition XML
     *
     * @param  string $processDefinitionKey The assigned key of the process definition, which acts as a unique identifier for this process definition. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getProcessDefinitionXML'] to see the possible values for this operation
     *
     * @return string|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getProcessDefinitionXML(string $processDefinitionKey, string $contentType = \Camunda\Orchestration\Api\Api\ProcessDefinitionApi::contentTypes['getProcessDefinitionXML'][0]): string|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessDefinitionApi::class)->getProcessDefinitionXML($processDefinitionKey, $contentType);
    }

    /**
     * Operation getStartProcessForm
     *
     * Get process start form
     *
     * @param  string $processDefinitionKey The process key. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getStartProcessForm'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\FormResult|\Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function getStartProcessForm(string $processDefinitionKey, string $contentType = \Camunda\Orchestration\Api\Api\ProcessDefinitionApi::contentTypes['getStartProcessForm'][0]): \Camunda\Orchestration\Api\Model\FormResult|\Camunda\Orchestration\Api\Model\ProblemDetail|null
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessDefinitionApi::class)->getStartProcessForm($processDefinitionKey, $contentType);
    }

    /**
     * Operation searchProcessDefinitionVariableNames
     *
     * Search process definition variable names
     *
     * @param  string $processDefinitionKey The assigned key of the process definition, which acts as a unique identifier for this process definition. (required)
     * @param  \Camunda\Orchestration\Api\Model\ProcessDefinitionVariableNameSearchQuery|null $processDefinitionVariableNameSearchQuery processDefinitionVariableNameSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchProcessDefinitionVariableNames'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProcessDefinitionVariableNameSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchProcessDefinitionVariableNames(string $processDefinitionKey, ?\Camunda\Orchestration\Api\Model\ProcessDefinitionVariableNameSearchQuery $processDefinitionVariableNameSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\ProcessDefinitionApi::contentTypes['searchProcessDefinitionVariableNames'][0]): \Camunda\Orchestration\Api\Model\ProcessDefinitionVariableNameSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessDefinitionApi::class)->searchProcessDefinitionVariableNames($processDefinitionKey, $processDefinitionVariableNameSearchQuery, $contentType);
    }

    /**
     * Operation searchProcessDefinitions
     *
     * Search process definitions
     *
     * @param  \Camunda\Orchestration\Api\Model\ProcessDefinitionSearchQuery|null $processDefinitionSearchQuery processDefinitionSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchProcessDefinitions'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProcessDefinitionSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchProcessDefinitions(?\Camunda\Orchestration\Api\Model\ProcessDefinitionSearchQuery $processDefinitionSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\ProcessDefinitionApi::contentTypes['searchProcessDefinitions'][0]): \Camunda\Orchestration\Api\Model\ProcessDefinitionSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessDefinitionApi::class)->searchProcessDefinitions($processDefinitionSearchQuery, $contentType);
    }

    /**
     * Operation assignProcessInstanceBusinessId
     *
     * Assign business id to process instance
     *
     * @param  string $processInstanceKey The key of the process instance to assign the business id to. (required)
     * @param  \Camunda\Orchestration\Api\Model\ProcessInstanceBusinessIdAssignmentInstruction $processInstanceBusinessIdAssignmentInstruction processInstanceBusinessIdAssignmentInstruction (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['assignProcessInstanceBusinessId'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function assignProcessInstanceBusinessId(string $processInstanceKey, \Camunda\Orchestration\Api\Model\ProcessInstanceBusinessIdAssignmentInstruction $processInstanceBusinessIdAssignmentInstruction, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['assignProcessInstanceBusinessId'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->assignProcessInstanceBusinessId($processInstanceKey, $processInstanceBusinessIdAssignmentInstruction, $contentType);
    }

    /**
     * Operation cancelProcessInstance
     *
     * Cancel process instance
     *
     * @param  string $processInstanceKey The key of the process instance to cancel. (required)
     * @param  \Camunda\Orchestration\Api\Model\CancelProcessInstanceRequest|null $cancelProcessInstanceRequest cancelProcessInstanceRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['cancelProcessInstance'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function cancelProcessInstance(string $processInstanceKey, ?\Camunda\Orchestration\Api\Model\CancelProcessInstanceRequest $cancelProcessInstanceRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['cancelProcessInstance'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->cancelProcessInstance($processInstanceKey, $cancelProcessInstanceRequest, $contentType);
    }

    /**
     * Operation cancelProcessInstancesBatchOperation
     *
     * Cancel process instances (batch)
     *
     * @param  \Camunda\Orchestration\Api\Model\ProcessInstanceCancellationBatchOperationRequest $processInstanceCancellationBatchOperationRequest processInstanceCancellationBatchOperationRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['cancelProcessInstancesBatchOperation'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function cancelProcessInstancesBatchOperation(\Camunda\Orchestration\Api\Model\ProcessInstanceCancellationBatchOperationRequest $processInstanceCancellationBatchOperationRequest, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['cancelProcessInstancesBatchOperation'][0]): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->cancelProcessInstancesBatchOperation($processInstanceCancellationBatchOperationRequest, $contentType);
    }

    /**
     * Operation createProcessInstance
     *
     * Create process instance
     *
     * @param  \Camunda\Orchestration\Api\Model\ProcessInstanceCreationInstructionByKey|\Camunda\Orchestration\Api\Model\ProcessInstanceCreationInstructionById $processInstanceCreationInstruction processInstanceCreationInstruction (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['createProcessInstance'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\CreateProcessInstanceResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function createProcessInstance(\Camunda\Orchestration\Api\Model\ProcessInstanceCreationInstructionByKey|\Camunda\Orchestration\Api\Model\ProcessInstanceCreationInstructionById $processInstanceCreationInstruction, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['createProcessInstance'][0]): \Camunda\Orchestration\Api\Model\CreateProcessInstanceResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->createProcessInstance($processInstanceCreationInstruction, $contentType);
    }

    /**
     * Operation deleteProcessInstance
     *
     * Delete process instance
     *
     * @param  string $processInstanceKey The key of the process instance to delete. (required)
     * @param  \Camunda\Orchestration\Api\Model\DeleteProcessInstanceRequest|null $deleteProcessInstanceRequest deleteProcessInstanceRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteProcessInstance'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function deleteProcessInstance(string $processInstanceKey, ?\Camunda\Orchestration\Api\Model\DeleteProcessInstanceRequest $deleteProcessInstanceRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['deleteProcessInstance'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->deleteProcessInstance($processInstanceKey, $deleteProcessInstanceRequest, $contentType);
    }

    /**
     * Operation deleteProcessInstancesBatchOperation
     *
     * Delete process instances (batch)
     *
     * @param  \Camunda\Orchestration\Api\Model\ProcessInstanceDeletionBatchOperationRequest $processInstanceDeletionBatchOperationRequest processInstanceDeletionBatchOperationRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteProcessInstancesBatchOperation'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function deleteProcessInstancesBatchOperation(\Camunda\Orchestration\Api\Model\ProcessInstanceDeletionBatchOperationRequest $processInstanceDeletionBatchOperationRequest, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['deleteProcessInstancesBatchOperation'][0]): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->deleteProcessInstancesBatchOperation($processInstanceDeletionBatchOperationRequest, $contentType);
    }

    /**
     * Operation getProcessInstance
     *
     * Get process instance
     *
     * @param  string $processInstanceKey The process instance key. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getProcessInstance'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProcessInstanceResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getProcessInstance(string $processInstanceKey, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['getProcessInstance'][0]): \Camunda\Orchestration\Api\Model\ProcessInstanceResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->getProcessInstance($processInstanceKey, $contentType);
    }

    /**
     * Operation getProcessInstanceCallHierarchy
     *
     * Get call hierarchy
     *
     * @param  string $processInstanceKey The key of the process instance to fetch the hierarchy for. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getProcessInstanceCallHierarchy'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProcessInstanceCallHierarchyEntry[]|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getProcessInstanceCallHierarchy(string $processInstanceKey, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['getProcessInstanceCallHierarchy'][0]): array|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->getProcessInstanceCallHierarchy($processInstanceKey, $contentType);
    }

    /**
     * Operation getProcessInstanceSequenceFlows
     *
     * Get sequence flows
     *
     * @param  string $processInstanceKey The assigned key of the process instance, which acts as a unique identifier for this process instance. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getProcessInstanceSequenceFlows'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProcessInstanceSequenceFlowsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getProcessInstanceSequenceFlows(string $processInstanceKey, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['getProcessInstanceSequenceFlows'][0]): \Camunda\Orchestration\Api\Model\ProcessInstanceSequenceFlowsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->getProcessInstanceSequenceFlows($processInstanceKey, $contentType);
    }

    /**
     * Operation getProcessInstanceStatistics
     *
     * Get element instance statistics
     *
     * @param  string $processInstanceKey The assigned key of the process instance, which acts as a unique identifier for this process instance. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getProcessInstanceStatistics'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProcessInstanceElementStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getProcessInstanceStatistics(string $processInstanceKey, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['getProcessInstanceStatistics'][0]): \Camunda\Orchestration\Api\Model\ProcessInstanceElementStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->getProcessInstanceStatistics($processInstanceKey, $contentType);
    }

    /**
     * Operation getProcessInstanceWaitStateStatistics
     *
     * Get wait state statistics
     *
     * @param  string $processInstanceKey The assigned key of the process instance, which acts as a unique identifier for this process instance. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getProcessInstanceWaitStateStatistics'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProcessInstanceWaitStateStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getProcessInstanceWaitStateStatistics(string $processInstanceKey, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['getProcessInstanceWaitStateStatistics'][0]): \Camunda\Orchestration\Api\Model\ProcessInstanceWaitStateStatisticsQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->getProcessInstanceWaitStateStatistics($processInstanceKey, $contentType);
    }

    /**
     * Operation migrateProcessInstance
     *
     * Migrate process instance
     *
     * @param  string $processInstanceKey The key of the process instance that should be migrated. (required)
     * @param  \Camunda\Orchestration\Api\Model\ProcessInstanceMigrationInstruction $processInstanceMigrationInstruction processInstanceMigrationInstruction (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['migrateProcessInstance'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function migrateProcessInstance(string $processInstanceKey, \Camunda\Orchestration\Api\Model\ProcessInstanceMigrationInstruction $processInstanceMigrationInstruction, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['migrateProcessInstance'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->migrateProcessInstance($processInstanceKey, $processInstanceMigrationInstruction, $contentType);
    }

    /**
     * Operation migrateProcessInstancesBatchOperation
     *
     * Migrate process instances (batch)
     *
     * @param  \Camunda\Orchestration\Api\Model\ProcessInstanceMigrationBatchOperationRequest $processInstanceMigrationBatchOperationRequest processInstanceMigrationBatchOperationRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['migrateProcessInstancesBatchOperation'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function migrateProcessInstancesBatchOperation(\Camunda\Orchestration\Api\Model\ProcessInstanceMigrationBatchOperationRequest $processInstanceMigrationBatchOperationRequest, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['migrateProcessInstancesBatchOperation'][0]): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->migrateProcessInstancesBatchOperation($processInstanceMigrationBatchOperationRequest, $contentType);
    }

    /**
     * Operation modifyProcessInstance
     *
     * Modify process instance
     *
     * @param  string $processInstanceKey The key of the process instance that should be modified. (required)
     * @param  \Camunda\Orchestration\Api\Model\ProcessInstanceModificationInstruction $processInstanceModificationInstruction processInstanceModificationInstruction (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['modifyProcessInstance'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function modifyProcessInstance(string $processInstanceKey, \Camunda\Orchestration\Api\Model\ProcessInstanceModificationInstruction $processInstanceModificationInstruction, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['modifyProcessInstance'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->modifyProcessInstance($processInstanceKey, $processInstanceModificationInstruction, $contentType);
    }

    /**
     * Operation modifyProcessInstancesBatchOperation
     *
     * Modify process instances (batch)
     *
     * @param  \Camunda\Orchestration\Api\Model\ProcessInstanceModificationBatchOperationRequest $processInstanceModificationBatchOperationRequest processInstanceModificationBatchOperationRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['modifyProcessInstancesBatchOperation'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function modifyProcessInstancesBatchOperation(\Camunda\Orchestration\Api\Model\ProcessInstanceModificationBatchOperationRequest $processInstanceModificationBatchOperationRequest, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['modifyProcessInstancesBatchOperation'][0]): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->modifyProcessInstancesBatchOperation($processInstanceModificationBatchOperationRequest, $contentType);
    }

    /**
     * Operation resolveIncidentsBatchOperation
     *
     * Resolve related incidents (batch)
     *
     * @param  \Camunda\Orchestration\Api\Model\ProcessInstanceIncidentResolutionBatchOperationRequest|null $processInstanceIncidentResolutionBatchOperationRequest processInstanceIncidentResolutionBatchOperationRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['resolveIncidentsBatchOperation'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function resolveIncidentsBatchOperation(?\Camunda\Orchestration\Api\Model\ProcessInstanceIncidentResolutionBatchOperationRequest $processInstanceIncidentResolutionBatchOperationRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['resolveIncidentsBatchOperation'][0]): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->resolveIncidentsBatchOperation($processInstanceIncidentResolutionBatchOperationRequest, $contentType);
    }

    /**
     * Operation resolveProcessInstanceIncidents
     *
     * Resolve related incidents
     *
     * @param  string $processInstanceKey The key of the process instance to resolve incidents for. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['resolveProcessInstanceIncidents'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function resolveProcessInstanceIncidents(string $processInstanceKey, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['resolveProcessInstanceIncidents'][0]): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->resolveProcessInstanceIncidents($processInstanceKey, $contentType);
    }

    /**
     * Operation resumeProcessInstance
     *
     * Resume process instance
     *
     * @param  string $processInstanceKey The key of the process instance to resume. (required)
     * @param  \Camunda\Orchestration\Api\Model\ResumeProcessInstanceRequest|null $resumeProcessInstanceRequest resumeProcessInstanceRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['resumeProcessInstance'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function resumeProcessInstance(string $processInstanceKey, ?\Camunda\Orchestration\Api\Model\ResumeProcessInstanceRequest $resumeProcessInstanceRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['resumeProcessInstance'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->resumeProcessInstance($processInstanceKey, $resumeProcessInstanceRequest, $contentType);
    }

    /**
     * Operation resumeProcessInstancesBatchOperation
     *
     * Resume process instances (batch)
     *
     * @param  \Camunda\Orchestration\Api\Model\ProcessInstanceResumptionBatchOperationRequest $processInstanceResumptionBatchOperationRequest processInstanceResumptionBatchOperationRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['resumeProcessInstancesBatchOperation'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function resumeProcessInstancesBatchOperation(\Camunda\Orchestration\Api\Model\ProcessInstanceResumptionBatchOperationRequest $processInstanceResumptionBatchOperationRequest, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['resumeProcessInstancesBatchOperation'][0]): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->resumeProcessInstancesBatchOperation($processInstanceResumptionBatchOperationRequest, $contentType);
    }

    /**
     * Operation searchProcessInstanceIncidents
     *
     * Search related incidents
     *
     * @param  string $processInstanceKey The assigned key of the process instance, which acts as a unique identifier for this process instance. (required)
     * @param  \Camunda\Orchestration\Api\Model\IncidentSearchQuery|null $incidentSearchQuery incidentSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchProcessInstanceIncidents'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\IncidentSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchProcessInstanceIncidents(string $processInstanceKey, ?\Camunda\Orchestration\Api\Model\IncidentSearchQuery $incidentSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['searchProcessInstanceIncidents'][0]): \Camunda\Orchestration\Api\Model\IncidentSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->searchProcessInstanceIncidents($processInstanceKey, $incidentSearchQuery, $contentType);
    }

    /**
     * Operation searchProcessInstances
     *
     * Search process instances
     *
     * @param  \Camunda\Orchestration\Api\Model\ProcessInstanceSearchQuery|null $processInstanceSearchQuery processInstanceSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchProcessInstances'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProcessInstanceSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchProcessInstances(?\Camunda\Orchestration\Api\Model\ProcessInstanceSearchQuery $processInstanceSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['searchProcessInstances'][0]): \Camunda\Orchestration\Api\Model\ProcessInstanceSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->searchProcessInstances($processInstanceSearchQuery, $contentType);
    }

    /**
     * Operation suspendProcessInstance
     *
     * Suspend process instance
     *
     * @param  string $processInstanceKey The key of the process instance to suspend. (required)
     * @param  \Camunda\Orchestration\Api\Model\SuspendProcessInstanceRequest|null $suspendProcessInstanceRequest suspendProcessInstanceRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['suspendProcessInstance'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function suspendProcessInstance(string $processInstanceKey, ?\Camunda\Orchestration\Api\Model\SuspendProcessInstanceRequest $suspendProcessInstanceRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['suspendProcessInstance'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->suspendProcessInstance($processInstanceKey, $suspendProcessInstanceRequest, $contentType);
    }

    /**
     * Operation suspendProcessInstancesBatchOperation
     *
     * Suspend process instances (batch)
     *
     * @param  \Camunda\Orchestration\Api\Model\ProcessInstanceSuspensionBatchOperationRequest $processInstanceSuspensionBatchOperationRequest processInstanceSuspensionBatchOperationRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['suspendProcessInstancesBatchOperation'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function suspendProcessInstancesBatchOperation(\Camunda\Orchestration\Api\Model\ProcessInstanceSuspensionBatchOperationRequest $processInstanceSuspensionBatchOperationRequest, string $contentType = \Camunda\Orchestration\Api\Api\ProcessInstanceApi::contentTypes['suspendProcessInstancesBatchOperation'][0]): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class)->suspendProcessInstancesBatchOperation($processInstanceSuspensionBatchOperationRequest, $contentType);
    }

    /**
     * Operation changeClusterMode
     *
     * Change cluster mode
     *
     * @param  \Camunda\Orchestration\Api\Model\Mode $mode The target cluster mode. (required)
     * @param  bool|null $dryRun If true, the requested change is only validated and the resulting plan is returned, without applying it to the cluster. (optional, default to false)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['changeClusterMode'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterModeChangeResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function changeClusterMode(\Camunda\Orchestration\Api\Model\Mode $mode, ?bool $dryRun = false, string $contentType = \Camunda\Orchestration\Api\Api\RecoveryApi::contentTypes['changeClusterMode'][0]): \Camunda\Orchestration\Api\Model\ClusterModeChangeResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RecoveryApi::class)->changeClusterMode($mode, $dryRun, $contentType);
    }

    /**
     * Operation changeClusterModeAsClusterAdmin
     *
     * Change the cluster mode of one or every physical tenant
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  \Camunda\Orchestration\Api\Model\Mode $mode The target cluster mode. (required)
     * @param  string|null $physicalTenantId The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. (optional)
     * @param  bool|null $dryRun If true, the requested change is only validated and the resulting plan is returned, without applying it to the cluster. (optional, default to false)
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['changeClusterModeAsClusterAdmin'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterModeChangeResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function changeClusterModeAsClusterAdmin(\Camunda\Orchestration\Api\Model\Mode $mode, ?string $physicalTenantId = null, ?bool $dryRun = false, ?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\RecoveryApi::contentTypes['changeClusterModeAsClusterAdmin'][0]): \Camunda\Orchestration\Api\Model\ClusterModeChangeResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RecoveryApi::class)->changeClusterModeAsClusterAdmin($mode, $physicalTenantId, $dryRun, $hostIndex, $variables, $contentType);
    }

    /**
     * Operation getRestoreStatus
     *
     * Get the status of the restore that is currently in progress
     *
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getRestoreStatus'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\RestoreStatusResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getRestoreStatus(string $contentType = \Camunda\Orchestration\Api\Api\RecoveryApi::contentTypes['getRestoreStatus'][0]): \Camunda\Orchestration\Api\Model\RestoreStatusResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RecoveryApi::class)->getRestoreStatus($contentType);
    }

    /**
     * Operation restore
     *
     * Restore from a backup
     *
     * @param  \Camunda\Orchestration\Api\Model\RestoreRequest $restoreRequest restoreRequest (required)
     * @param  bool|null $dryRun If true, the requested change is only validated and the resulting plan is returned, without applying it to the cluster. (optional, default to false)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['restore'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterRestoreResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function restore(\Camunda\Orchestration\Api\Model\RestoreRequest $restoreRequest, ?bool $dryRun = false, string $contentType = \Camunda\Orchestration\Api\Api\RecoveryApi::contentTypes['restore'][0]): \Camunda\Orchestration\Api\Model\ClusterRestoreResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RecoveryApi::class)->restore($restoreRequest, $dryRun, $contentType);
    }

    /**
     * Operation restoreAsClusterAdmin
     *
     * Restore one or every physical tenant from a backup
     *
     * This operation contains host(s) defined in the OpenAPI spec. Use 'hostIndex' to select the host.
     * if needed, use the 'variables' parameter to pass variables to the host.
     * URL: {schema}://{host}:{port}
     *  Variables:
     *    - host: The hostname of the Orchestration Cluster REST Gateway.
     *    - port: The port of the Orchestration Cluster REST API server.
     *    - schema: The schema of the Orchestration Cluster REST API server.
     *
     * @param  \Camunda\Orchestration\Api\Model\ClusterRestoreRequest $clusterRestoreRequest clusterRestoreRequest (required)
     * @param  string|null $physicalTenantId The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. (optional)
     * @param  bool|null $dryRun If true, the requested change is only validated and the resulting plan is returned, without applying it to the cluster. (optional, default to false)
     * @param  null|int $hostIndex Host index. Defaults to null. If null, then the library will use $this->hostIndex instead
     * @param  array<string, mixed> $variables Associative array of variables to pass to the host. Defaults to empty array.
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['restoreAsClusterAdmin'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ClusterRestoreResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function restoreAsClusterAdmin(\Camunda\Orchestration\Api\Model\ClusterRestoreRequest $clusterRestoreRequest, ?string $physicalTenantId = null, ?bool $dryRun = false, ?int $hostIndex = null, array $variables = [], string $contentType = \Camunda\Orchestration\Api\Api\RecoveryApi::contentTypes['restoreAsClusterAdmin'][0]): \Camunda\Orchestration\Api\Model\ClusterRestoreResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RecoveryApi::class)->restoreAsClusterAdmin($clusterRestoreRequest, $physicalTenantId, $dryRun, $hostIndex, $variables, $contentType);
    }

    /**
     * Operation createDeployment
     *
     * Deploy resources
     *
     * @param  \SplFileObject[] $resources The binary data to create the deployment resources. It is possible to have more than one form part with different form part names for the binary data to create a deployment. (required)
     * @param  string|null $tenantId The unique identifier of the tenant. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['createDeployment'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\DeploymentResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function createDeployment(array $resources, ?string $tenantId = null, string $contentType = \Camunda\Orchestration\Api\Api\ResourceApi::contentTypes['createDeployment'][0]): \Camunda\Orchestration\Api\Model\DeploymentResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ResourceApi::class)->createDeployment($resources, $tenantId, $contentType);
    }

    /**
     * Operation deleteResource
     *
     * Delete resource
     *
     * @param  \Camunda\Orchestration\Semantic\ResourceKey $resourceKey The key of the resource to delete. This can be the key of a process definition, the key of a decision requirements definition or the key of a form definition (required)
     * @param  \Camunda\Orchestration\Api\Model\DeleteResourceRequest|null $deleteResourceRequest deleteResourceRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteResource'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\DeleteResourceResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function deleteResource(\Camunda\Orchestration\Semantic\ResourceKey $resourceKey, ?\Camunda\Orchestration\Api\Model\DeleteResourceRequest $deleteResourceRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\ResourceApi::contentTypes['deleteResource'][0]): \Camunda\Orchestration\Api\Model\DeleteResourceResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ResourceApi::class)->deleteResource($resourceKey, $deleteResourceRequest, $contentType);
    }

    /**
     * Operation getResource
     *
     * Get resource
     *
     * @param  \Camunda\Orchestration\Semantic\ResourceKey $resourceKey The unique key identifying the resource. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getResource'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ResourceResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getResource(\Camunda\Orchestration\Semantic\ResourceKey $resourceKey, string $contentType = \Camunda\Orchestration\Api\Api\ResourceApi::contentTypes['getResource'][0]): \Camunda\Orchestration\Api\Model\ResourceResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ResourceApi::class)->getResource($resourceKey, $contentType);
    }

    /**
     * Operation getResourceContent
     *
     * Get RPA resource content (deprecated)
     *
     * @param  \Camunda\Orchestration\Semantic\ResourceKey $resourceKey The unique key identifying the RPA resource. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getResourceContent'] to see the possible values for this operation
     *
     * @return array<string,mixed>|\Camunda\Orchestration\Api\Model\ProblemDetail
     * @deprecated
     */
    public function getResourceContent(\Camunda\Orchestration\Semantic\ResourceKey $resourceKey, string $contentType = \Camunda\Orchestration\Api\Api\ResourceApi::contentTypes['getResourceContent'][0]): array|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ResourceApi::class)->getResourceContent($resourceKey, $contentType);
    }

    /**
     * Operation getResourceContentBinary
     *
     * Get resource content as binary
     *
     * @param  \Camunda\Orchestration\Semantic\ResourceKey $resourceKey The unique key identifying the resource. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getResourceContentBinary'] to see the possible values for this operation
     *
     * @return \SplFileObject|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getResourceContentBinary(\Camunda\Orchestration\Semantic\ResourceKey $resourceKey, string $contentType = \Camunda\Orchestration\Api\Api\ResourceApi::contentTypes['getResourceContentBinary'][0]): \SplFileObject|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ResourceApi::class)->getResourceContentBinary($resourceKey, $contentType);
    }

    /**
     * Operation searchResources
     *
     * Search resources
     *
     * @param  \Camunda\Orchestration\Api\Model\ResourceSearchQuery|null $resourceSearchQuery resourceSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchResources'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ResourceSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchResources(?\Camunda\Orchestration\Api\Model\ResourceSearchQuery $resourceSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\ResourceApi::contentTypes['searchResources'][0]): \Camunda\Orchestration\Api\Model\ResourceSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\ResourceApi::class)->searchResources($resourceSearchQuery, $contentType);
    }

    /**
     * Operation assignRoleToClient
     *
     * Assign a role to a client
     *
     * @param  string $roleId The role ID. (required)
     * @param  string $clientId The client ID. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['assignRoleToClient'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function assignRoleToClient(string $roleId, string $clientId, string $contentType = \Camunda\Orchestration\Api\Api\RoleApi::contentTypes['assignRoleToClient'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RoleApi::class)->assignRoleToClient($roleId, $clientId, $contentType);
    }

    /**
     * Operation assignRoleToGroup
     *
     * Assign a role to a group
     *
     * @param  string $roleId The role ID. (required)
     * @param  string $groupId The group ID. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['assignRoleToGroup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function assignRoleToGroup(string $roleId, string $groupId, string $contentType = \Camunda\Orchestration\Api\Api\RoleApi::contentTypes['assignRoleToGroup'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RoleApi::class)->assignRoleToGroup($roleId, $groupId, $contentType);
    }

    /**
     * Operation assignRoleToMappingRule
     *
     * Assign a role to a mapping rule
     *
     * @param  string $roleId The role ID. (required)
     * @param  string $mappingRuleId The mapping rule ID. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['assignRoleToMappingRule'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function assignRoleToMappingRule(string $roleId, string $mappingRuleId, string $contentType = \Camunda\Orchestration\Api\Api\RoleApi::contentTypes['assignRoleToMappingRule'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RoleApi::class)->assignRoleToMappingRule($roleId, $mappingRuleId, $contentType);
    }

    /**
     * Operation assignRoleToUser
     *
     * Assign a role to a user
     *
     * @param  string $roleId The role ID. (required)
     * @param  string $username The user username. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['assignRoleToUser'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function assignRoleToUser(string $roleId, string $username, string $contentType = \Camunda\Orchestration\Api\Api\RoleApi::contentTypes['assignRoleToUser'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RoleApi::class)->assignRoleToUser($roleId, $username, $contentType);
    }

    /**
     * Operation createRole
     *
     * Create role
     *
     * @param  \Camunda\Orchestration\Api\Model\RoleCreateRequest|null $roleCreateRequest roleCreateRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['createRole'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\RoleCreateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function createRole(?\Camunda\Orchestration\Api\Model\RoleCreateRequest $roleCreateRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\RoleApi::contentTypes['createRole'][0]): \Camunda\Orchestration\Api\Model\RoleCreateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RoleApi::class)->createRole($roleCreateRequest, $contentType);
    }

    /**
     * Operation deleteRole
     *
     * Delete role
     *
     * @param  string $roleId The role ID. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteRole'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function deleteRole(string $roleId, string $contentType = \Camunda\Orchestration\Api\Api\RoleApi::contentTypes['deleteRole'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RoleApi::class)->deleteRole($roleId, $contentType);
    }

    /**
     * Operation getRole
     *
     * Get role
     *
     * @param  string $roleId The role ID. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getRole'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\RoleResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getRole(string $roleId, string $contentType = \Camunda\Orchestration\Api\Api\RoleApi::contentTypes['getRole'][0]): \Camunda\Orchestration\Api\Model\RoleResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RoleApi::class)->getRole($roleId, $contentType);
    }

    /**
     * Operation searchClientsForRole
     *
     * Search role clients
     *
     * @param  string $roleId The role ID. (required)
     * @param  \Camunda\Orchestration\Api\Model\RoleClientSearchQueryRequest|null $roleClientSearchQueryRequest roleClientSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchClientsForRole'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\RoleClientSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchClientsForRole(string $roleId, ?\Camunda\Orchestration\Api\Model\RoleClientSearchQueryRequest $roleClientSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\RoleApi::contentTypes['searchClientsForRole'][0]): \Camunda\Orchestration\Api\Model\RoleClientSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RoleApi::class)->searchClientsForRole($roleId, $roleClientSearchQueryRequest, $contentType);
    }

    /**
     * Operation searchGroupsForRole
     *
     * Search role groups
     *
     * @param  string $roleId The role ID. (required)
     * @param  \Camunda\Orchestration\Api\Model\RoleGroupSearchQueryRequest|null $roleGroupSearchQueryRequest roleGroupSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchGroupsForRole'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\RoleGroupSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchGroupsForRole(string $roleId, ?\Camunda\Orchestration\Api\Model\RoleGroupSearchQueryRequest $roleGroupSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\RoleApi::contentTypes['searchGroupsForRole'][0]): \Camunda\Orchestration\Api\Model\RoleGroupSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RoleApi::class)->searchGroupsForRole($roleId, $roleGroupSearchQueryRequest, $contentType);
    }

    /**
     * Operation searchMappingRulesForRole
     *
     * Search role mapping rules
     *
     * @param  string $roleId The role ID. (required)
     * @param  \Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest|null $mappingRuleSearchQueryRequest mappingRuleSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchMappingRulesForRole'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\RoleMappingRuleSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchMappingRulesForRole(string $roleId, ?\Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest $mappingRuleSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\RoleApi::contentTypes['searchMappingRulesForRole'][0]): \Camunda\Orchestration\Api\Model\RoleMappingRuleSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RoleApi::class)->searchMappingRulesForRole($roleId, $mappingRuleSearchQueryRequest, $contentType);
    }

    /**
     * Operation searchRoles
     *
     * Search roles
     *
     * @param  \Camunda\Orchestration\Api\Model\RoleSearchQueryRequest|null $roleSearchQueryRequest roleSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchRoles'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\RoleSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchRoles(?\Camunda\Orchestration\Api\Model\RoleSearchQueryRequest $roleSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\RoleApi::contentTypes['searchRoles'][0]): \Camunda\Orchestration\Api\Model\RoleSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RoleApi::class)->searchRoles($roleSearchQueryRequest, $contentType);
    }

    /**
     * Operation searchUsersForRole
     *
     * Search role users
     *
     * @param  string $roleId The role ID. (required)
     * @param  \Camunda\Orchestration\Api\Model\RoleUserSearchQueryRequest|null $roleUserSearchQueryRequest roleUserSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchUsersForRole'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\RoleUserSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchUsersForRole(string $roleId, ?\Camunda\Orchestration\Api\Model\RoleUserSearchQueryRequest $roleUserSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\RoleApi::contentTypes['searchUsersForRole'][0]): \Camunda\Orchestration\Api\Model\RoleUserSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RoleApi::class)->searchUsersForRole($roleId, $roleUserSearchQueryRequest, $contentType);
    }

    /**
     * Operation unassignRoleFromClient
     *
     * Unassign a role from a client
     *
     * @param  string $roleId The role ID. (required)
     * @param  string $clientId The client ID. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['unassignRoleFromClient'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function unassignRoleFromClient(string $roleId, string $clientId, string $contentType = \Camunda\Orchestration\Api\Api\RoleApi::contentTypes['unassignRoleFromClient'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RoleApi::class)->unassignRoleFromClient($roleId, $clientId, $contentType);
    }

    /**
     * Operation unassignRoleFromGroup
     *
     * Unassign a role from a group
     *
     * @param  string $roleId The role ID. (required)
     * @param  string $groupId The group ID. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['unassignRoleFromGroup'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function unassignRoleFromGroup(string $roleId, string $groupId, string $contentType = \Camunda\Orchestration\Api\Api\RoleApi::contentTypes['unassignRoleFromGroup'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RoleApi::class)->unassignRoleFromGroup($roleId, $groupId, $contentType);
    }

    /**
     * Operation unassignRoleFromMappingRule
     *
     * Unassign a role from a mapping rule
     *
     * @param  string $roleId The role ID. (required)
     * @param  string $mappingRuleId The mapping rule ID. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['unassignRoleFromMappingRule'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function unassignRoleFromMappingRule(string $roleId, string $mappingRuleId, string $contentType = \Camunda\Orchestration\Api\Api\RoleApi::contentTypes['unassignRoleFromMappingRule'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RoleApi::class)->unassignRoleFromMappingRule($roleId, $mappingRuleId, $contentType);
    }

    /**
     * Operation unassignRoleFromUser
     *
     * Unassign a role from a user
     *
     * @param  string $roleId The role ID. (required)
     * @param  string $username The user username. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['unassignRoleFromUser'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function unassignRoleFromUser(string $roleId, string $username, string $contentType = \Camunda\Orchestration\Api\Api\RoleApi::contentTypes['unassignRoleFromUser'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RoleApi::class)->unassignRoleFromUser($roleId, $username, $contentType);
    }

    /**
     * Operation updateRole
     *
     * Update role
     *
     * @param  string $roleId The role ID. (required)
     * @param  \Camunda\Orchestration\Api\Model\RoleUpdateRequest $roleUpdateRequest roleUpdateRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['updateRole'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\RoleUpdateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function updateRole(string $roleId, \Camunda\Orchestration\Api\Model\RoleUpdateRequest $roleUpdateRequest, string $contentType = \Camunda\Orchestration\Api\Api\RoleApi::contentTypes['updateRole'][0]): \Camunda\Orchestration\Api\Model\RoleUpdateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\RoleApi::class)->updateRole($roleId, $roleUpdateRequest, $contentType);
    }

    /**
     * Operation listSecrets
     *
     * List secrets (alpha)
     *
     * @param  array<string, mixed>|null $body body (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['listSecrets'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\SecretListResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function listSecrets(?array $body = null, string $contentType = \Camunda\Orchestration\Api\Api\SecretApi::contentTypes['listSecrets'][0]): \Camunda\Orchestration\Api\Model\SecretListResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\SecretApi::class)->listSecrets($body, $contentType);
    }

    /**
     * Operation resolveSecrets
     *
     * Resolve secrets (alpha)
     *
     * @param  \Camunda\Orchestration\Api\Model\SecretResolveRequest $secretResolveRequest secretResolveRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['resolveSecrets'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\SecretResolveResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function resolveSecrets(\Camunda\Orchestration\Api\Model\SecretResolveRequest $secretResolveRequest, string $contentType = \Camunda\Orchestration\Api\Api\SecretApi::contentTypes['resolveSecrets'][0]): \Camunda\Orchestration\Api\Model\SecretResolveResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\SecretApi::class)->resolveSecrets($secretResolveRequest, $contentType);
    }

    /**
     * Operation createAdminUser
     *
     * Create admin user
     *
     * @param  \Camunda\Orchestration\Api\Model\UserRequest $userRequest userRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['createAdminUser'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\UserCreateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function createAdminUser(\Camunda\Orchestration\Api\Model\UserRequest $userRequest, string $contentType = \Camunda\Orchestration\Api\Api\SetupApi::contentTypes['createAdminUser'][0]): \Camunda\Orchestration\Api\Model\UserCreateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\SetupApi::class)->createAdminUser($userRequest, $contentType);
    }

    /**
     * Operation broadcastSignal
     *
     * Broadcast signal
     *
     * @param  \Camunda\Orchestration\Api\Model\SignalBroadcastRequest $signalBroadcastRequest signalBroadcastRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['broadcastSignal'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\SignalBroadcastResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function broadcastSignal(\Camunda\Orchestration\Api\Model\SignalBroadcastRequest $signalBroadcastRequest, string $contentType = \Camunda\Orchestration\Api\Api\SignalApi::contentTypes['broadcastSignal'][0]): \Camunda\Orchestration\Api\Model\SignalBroadcastResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\SignalApi::class)->broadcastSignal($signalBroadcastRequest, $contentType);
    }

    /**
     * Operation getSystemConfiguration
     *
     * System configuration (alpha)
     *
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getSystemConfiguration'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\SystemConfigurationResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getSystemConfiguration(string $contentType = \Camunda\Orchestration\Api\Api\SystemApi::contentTypes['getSystemConfiguration'][0]): \Camunda\Orchestration\Api\Model\SystemConfigurationResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\SystemApi::class)->getSystemConfiguration($contentType);
    }

    /**
     * Operation getUsageMetrics
     *
     * Get usage metrics
     *
     * @param  \DateTime $startTime The start date for usage metrics, including this date. Value in ISO 8601 format. (required)
     * @param  \DateTime $endTime The end date for usage metrics, including this date. Value in ISO 8601 format. (required)
     * @param  string|null $tenantId Restrict results to a specific tenant ID. If not provided, results for all tenants are returned. (optional)
     * @param  bool|null $withTenants Whether to return tenant metrics in addition to the total metrics or not. Default false. (optional, default to false)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getUsageMetrics'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\UsageMetricsResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getUsageMetrics(\DateTime $startTime, \DateTime $endTime, ?string $tenantId = null, ?bool $withTenants = false, string $contentType = \Camunda\Orchestration\Api\Api\SystemApi::contentTypes['getUsageMetrics'][0]): \Camunda\Orchestration\Api\Model\UsageMetricsResponse|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\SystemApi::class)->getUsageMetrics($startTime, $endTime, $tenantId, $withTenants, $contentType);
    }

    /**
     * Operation assignClientToTenant
     *
     * Assign a client to a tenant
     *
     * @param  string $tenantId The unique identifier of the tenant. (required)
     * @param  string $clientId The unique identifier of the application. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['assignClientToTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function assignClientToTenant(string $tenantId, string $clientId, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['assignClientToTenant'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->assignClientToTenant($tenantId, $clientId, $contentType);
    }

    /**
     * Operation assignGroupToTenant
     *
     * Assign a group to a tenant
     *
     * @param  string $tenantId The unique identifier of the tenant. (required)
     * @param  string $groupId The unique identifier of the group. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['assignGroupToTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function assignGroupToTenant(string $tenantId, string $groupId, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['assignGroupToTenant'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->assignGroupToTenant($tenantId, $groupId, $contentType);
    }

    /**
     * Operation assignMappingRuleToTenant
     *
     * Assign a mapping rule to a tenant
     *
     * @param  string $tenantId The unique identifier of the tenant. (required)
     * @param  string $mappingRuleId The unique identifier of the mapping rule. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['assignMappingRuleToTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function assignMappingRuleToTenant(string $tenantId, string $mappingRuleId, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['assignMappingRuleToTenant'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->assignMappingRuleToTenant($tenantId, $mappingRuleId, $contentType);
    }

    /**
     * Operation assignRoleToTenant
     *
     * Assign a role to a tenant
     *
     * @param  string $tenantId The unique identifier of the tenant. (required)
     * @param  string $roleId The unique identifier of the role. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['assignRoleToTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function assignRoleToTenant(string $tenantId, string $roleId, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['assignRoleToTenant'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->assignRoleToTenant($tenantId, $roleId, $contentType);
    }

    /**
     * Operation assignUserToTenant
     *
     * Assign a user to a tenant
     *
     * @param  string $tenantId The unique identifier of the tenant. (required)
     * @param  string $username The unique identifier of the user. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['assignUserToTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function assignUserToTenant(string $tenantId, string $username, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['assignUserToTenant'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->assignUserToTenant($tenantId, $username, $contentType);
    }

    /**
     * Operation createTenant
     *
     * Create tenant
     *
     * @param  \Camunda\Orchestration\Api\Model\TenantCreateRequest $tenantCreateRequest tenantCreateRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['createTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\TenantCreateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function createTenant(\Camunda\Orchestration\Api\Model\TenantCreateRequest $tenantCreateRequest, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['createTenant'][0]): \Camunda\Orchestration\Api\Model\TenantCreateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->createTenant($tenantCreateRequest, $contentType);
    }

    /**
     * Operation deleteTenant
     *
     * Delete tenant
     *
     * @param  string $tenantId The unique identifier of the tenant. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function deleteTenant(string $tenantId, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['deleteTenant'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->deleteTenant($tenantId, $contentType);
    }

    /**
     * Operation getTenant
     *
     * Get tenant
     *
     * @param  string $tenantId The unique identifier of the tenant. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\TenantResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getTenant(string $tenantId, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['getTenant'][0]): \Camunda\Orchestration\Api\Model\TenantResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->getTenant($tenantId, $contentType);
    }

    /**
     * Operation searchClientsForTenant
     *
     * Search clients for tenant
     *
     * @param  string $tenantId The unique identifier of the tenant. (required)
     * @param  \Camunda\Orchestration\Api\Model\TenantClientSearchQueryRequest|null $tenantClientSearchQueryRequest tenantClientSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchClientsForTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\TenantClientSearchResult
     */
    public function searchClientsForTenant(string $tenantId, ?\Camunda\Orchestration\Api\Model\TenantClientSearchQueryRequest $tenantClientSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['searchClientsForTenant'][0]): \Camunda\Orchestration\Api\Model\TenantClientSearchResult
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->searchClientsForTenant($tenantId, $tenantClientSearchQueryRequest, $contentType);
    }

    /**
     * Operation searchGroupIdsForTenant
     *
     * Search groups for tenant
     *
     * @param  string $tenantId The unique identifier of the tenant. (required)
     * @param  \Camunda\Orchestration\Api\Model\TenantGroupSearchQueryRequest|null $tenantGroupSearchQueryRequest tenantGroupSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchGroupIdsForTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\TenantGroupSearchResult
     */
    public function searchGroupIdsForTenant(string $tenantId, ?\Camunda\Orchestration\Api\Model\TenantGroupSearchQueryRequest $tenantGroupSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['searchGroupIdsForTenant'][0]): \Camunda\Orchestration\Api\Model\TenantGroupSearchResult
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->searchGroupIdsForTenant($tenantId, $tenantGroupSearchQueryRequest, $contentType);
    }

    /**
     * Operation searchMappingRulesForTenant
     *
     * Search mapping rules for tenant
     *
     * @param  string $tenantId The unique identifier of the tenant. (required)
     * @param  \Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest|null $mappingRuleSearchQueryRequest mappingRuleSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchMappingRulesForTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\TenantMappingRuleSearchResult
     */
    public function searchMappingRulesForTenant(string $tenantId, ?\Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest $mappingRuleSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['searchMappingRulesForTenant'][0]): \Camunda\Orchestration\Api\Model\TenantMappingRuleSearchResult
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->searchMappingRulesForTenant($tenantId, $mappingRuleSearchQueryRequest, $contentType);
    }

    /**
     * Operation searchRolesForTenant
     *
     * Search roles for tenant
     *
     * @param  string $tenantId The unique identifier of the tenant. (required)
     * @param  \Camunda\Orchestration\Api\Model\RoleSearchQueryRequest|null $roleSearchQueryRequest roleSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchRolesForTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\TenantRoleSearchResult
     */
    public function searchRolesForTenant(string $tenantId, ?\Camunda\Orchestration\Api\Model\RoleSearchQueryRequest $roleSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['searchRolesForTenant'][0]): \Camunda\Orchestration\Api\Model\TenantRoleSearchResult
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->searchRolesForTenant($tenantId, $roleSearchQueryRequest, $contentType);
    }

    /**
     * Operation searchTenants
     *
     * Search tenants
     *
     * @param  \Camunda\Orchestration\Api\Model\TenantSearchQueryRequest|null $tenantSearchQueryRequest tenantSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchTenants'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\TenantSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchTenants(?\Camunda\Orchestration\Api\Model\TenantSearchQueryRequest $tenantSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['searchTenants'][0]): \Camunda\Orchestration\Api\Model\TenantSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->searchTenants($tenantSearchQueryRequest, $contentType);
    }

    /**
     * Operation searchUsersForTenant
     *
     * Search users for tenant
     *
     * @param  string $tenantId The unique identifier of the tenant. (required)
     * @param  \Camunda\Orchestration\Api\Model\TenantUserSearchQueryRequest|null $tenantUserSearchQueryRequest tenantUserSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchUsersForTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\TenantUserSearchResult
     */
    public function searchUsersForTenant(string $tenantId, ?\Camunda\Orchestration\Api\Model\TenantUserSearchQueryRequest $tenantUserSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['searchUsersForTenant'][0]): \Camunda\Orchestration\Api\Model\TenantUserSearchResult
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->searchUsersForTenant($tenantId, $tenantUserSearchQueryRequest, $contentType);
    }

    /**
     * Operation unassignClientFromTenant
     *
     * Unassign a client from a tenant
     *
     * @param  string $tenantId The unique identifier of the tenant. (required)
     * @param  string $clientId The unique identifier of the application. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['unassignClientFromTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function unassignClientFromTenant(string $tenantId, string $clientId, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['unassignClientFromTenant'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->unassignClientFromTenant($tenantId, $clientId, $contentType);
    }

    /**
     * Operation unassignGroupFromTenant
     *
     * Unassign a group from a tenant
     *
     * @param  string $tenantId The unique identifier of the tenant. (required)
     * @param  string $groupId The unique identifier of the group. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['unassignGroupFromTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function unassignGroupFromTenant(string $tenantId, string $groupId, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['unassignGroupFromTenant'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->unassignGroupFromTenant($tenantId, $groupId, $contentType);
    }

    /**
     * Operation unassignMappingRuleFromTenant
     *
     * Unassign a mapping rule from a tenant
     *
     * @param  string $tenantId The unique identifier of the tenant. (required)
     * @param  string $mappingRuleId The unique identifier of the mapping rule. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['unassignMappingRuleFromTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function unassignMappingRuleFromTenant(string $tenantId, string $mappingRuleId, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['unassignMappingRuleFromTenant'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->unassignMappingRuleFromTenant($tenantId, $mappingRuleId, $contentType);
    }

    /**
     * Operation unassignRoleFromTenant
     *
     * Unassign a role from a tenant
     *
     * @param  string $tenantId The unique identifier of the tenant. (required)
     * @param  string $roleId The unique identifier of the role. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['unassignRoleFromTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function unassignRoleFromTenant(string $tenantId, string $roleId, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['unassignRoleFromTenant'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->unassignRoleFromTenant($tenantId, $roleId, $contentType);
    }

    /**
     * Operation unassignUserFromTenant
     *
     * Unassign a user from a tenant
     *
     * @param  string $tenantId The unique identifier of the tenant. (required)
     * @param  string $username The unique identifier of the user. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['unassignUserFromTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function unassignUserFromTenant(string $tenantId, string $username, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['unassignUserFromTenant'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->unassignUserFromTenant($tenantId, $username, $contentType);
    }

    /**
     * Operation updateTenant
     *
     * Update tenant
     *
     * @param  string $tenantId The unique identifier of the tenant. (required)
     * @param  \Camunda\Orchestration\Api\Model\TenantUpdateRequest $tenantUpdateRequest tenantUpdateRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['updateTenant'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\TenantUpdateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function updateTenant(string $tenantId, \Camunda\Orchestration\Api\Model\TenantUpdateRequest $tenantUpdateRequest, string $contentType = \Camunda\Orchestration\Api\Api\TenantApi::contentTypes['updateTenant'][0]): \Camunda\Orchestration\Api\Model\TenantUpdateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class)->updateTenant($tenantId, $tenantUpdateRequest, $contentType);
    }

    /**
     * Operation createUser
     *
     * Create user
     *
     * @param  \Camunda\Orchestration\Api\Model\UserRequest $userRequest userRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['createUser'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\UserCreateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function createUser(\Camunda\Orchestration\Api\Model\UserRequest $userRequest, string $contentType = \Camunda\Orchestration\Api\Api\UserApi::contentTypes['createUser'][0]): \Camunda\Orchestration\Api\Model\UserCreateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\UserApi::class)->createUser($userRequest, $contentType);
    }

    /**
     * Operation deleteUser
     *
     * Delete user
     *
     * @param  string $username The username of the user to delete. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['deleteUser'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function deleteUser(string $username, string $contentType = \Camunda\Orchestration\Api\Api\UserApi::contentTypes['deleteUser'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\UserApi::class)->deleteUser($username, $contentType);
    }

    /**
     * Operation getUser
     *
     * Get user
     *
     * @param  string $username The username of the user. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getUser'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\UserResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getUser(string $username, string $contentType = \Camunda\Orchestration\Api\Api\UserApi::contentTypes['getUser'][0]): \Camunda\Orchestration\Api\Model\UserResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\UserApi::class)->getUser($username, $contentType);
    }

    /**
     * Operation searchUsers
     *
     * Search users
     *
     * @param  \Camunda\Orchestration\Api\Model\UserSearchQueryRequest|null $userSearchQueryRequest userSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchUsers'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\UserSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchUsers(?\Camunda\Orchestration\Api\Model\UserSearchQueryRequest $userSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\UserApi::contentTypes['searchUsers'][0]): \Camunda\Orchestration\Api\Model\UserSearchResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\UserApi::class)->searchUsers($userSearchQueryRequest, $contentType);
    }

    /**
     * Operation updateUser
     *
     * Update user
     *
     * @param  string $username The username of the user to update. (required)
     * @param  \Camunda\Orchestration\Api\Model\UserUpdateRequest $userUpdateRequest userUpdateRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['updateUser'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\UserUpdateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function updateUser(string $username, \Camunda\Orchestration\Api\Model\UserUpdateRequest $userUpdateRequest, string $contentType = \Camunda\Orchestration\Api\Api\UserApi::contentTypes['updateUser'][0]): \Camunda\Orchestration\Api\Model\UserUpdateResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\UserApi::class)->updateUser($username, $userUpdateRequest, $contentType);
    }

    /**
     * Operation assignUserTask
     *
     * Assign user task
     *
     * @param  string $userTaskKey The key of the user task to assign. (required)
     * @param  \Camunda\Orchestration\Api\Model\UserTaskAssignmentRequest $userTaskAssignmentRequest userTaskAssignmentRequest (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['assignUserTask'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function assignUserTask(string $userTaskKey, \Camunda\Orchestration\Api\Model\UserTaskAssignmentRequest $userTaskAssignmentRequest, string $contentType = \Camunda\Orchestration\Api\Api\UserTaskApi::contentTypes['assignUserTask'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\UserTaskApi::class)->assignUserTask($userTaskKey, $userTaskAssignmentRequest, $contentType);
    }

    /**
     * Operation completeUserTask
     *
     * Complete user task
     *
     * @param  string $userTaskKey The key of the user task to complete. (required)
     * @param  \Camunda\Orchestration\Api\Model\UserTaskCompletionRequest|null $userTaskCompletionRequest userTaskCompletionRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['completeUserTask'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function completeUserTask(string $userTaskKey, ?\Camunda\Orchestration\Api\Model\UserTaskCompletionRequest $userTaskCompletionRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\UserTaskApi::contentTypes['completeUserTask'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\UserTaskApi::class)->completeUserTask($userTaskKey, $userTaskCompletionRequest, $contentType);
    }

    /**
     * Operation getUserTask
     *
     * Get user task
     *
     * @param  string $userTaskKey The user task key. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getUserTask'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\UserTaskResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getUserTask(string $userTaskKey, string $contentType = \Camunda\Orchestration\Api\Api\UserTaskApi::contentTypes['getUserTask'][0]): \Camunda\Orchestration\Api\Model\UserTaskResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\UserTaskApi::class)->getUserTask($userTaskKey, $contentType);
    }

    /**
     * Operation getUserTaskForm
     *
     * Get user task form
     *
     * @param  string $userTaskKey The user task key. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getUserTaskForm'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\FormResult|\Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function getUserTaskForm(string $userTaskKey, string $contentType = \Camunda\Orchestration\Api\Api\UserTaskApi::contentTypes['getUserTaskForm'][0]): \Camunda\Orchestration\Api\Model\FormResult|\Camunda\Orchestration\Api\Model\ProblemDetail|null
    {
        return $this->api(\Camunda\Orchestration\Api\Api\UserTaskApi::class)->getUserTaskForm($userTaskKey, $contentType);
    }

    /**
     * Operation searchUserTaskAuditLogs
     *
     * Search user task audit logs
     *
     * @param  string $userTaskKey The key of the user task. (required)
     * @param  \Camunda\Orchestration\Api\Model\UserTaskAuditLogSearchQueryRequest|null $userTaskAuditLogSearchQueryRequest userTaskAuditLogSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchUserTaskAuditLogs'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\AuditLogSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchUserTaskAuditLogs(string $userTaskKey, ?\Camunda\Orchestration\Api\Model\UserTaskAuditLogSearchQueryRequest $userTaskAuditLogSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\UserTaskApi::contentTypes['searchUserTaskAuditLogs'][0]): \Camunda\Orchestration\Api\Model\AuditLogSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\UserTaskApi::class)->searchUserTaskAuditLogs($userTaskKey, $userTaskAuditLogSearchQueryRequest, $contentType);
    }

    /**
     * Operation searchUserTaskEffectiveVariables
     *
     * Search user task effective variables
     *
     * @param  string $userTaskKey The key of the user task. (required)
     * @param  bool|null $truncateValues When true (default), long variable values in the response are truncated. When false, full variable values are returned. (optional)
     * @param  \Camunda\Orchestration\Api\Model\UserTaskEffectiveVariableSearchQueryRequest|null $userTaskEffectiveVariableSearchQueryRequest userTaskEffectiveVariableSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchUserTaskEffectiveVariables'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\VariableSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchUserTaskEffectiveVariables(string $userTaskKey, ?bool $truncateValues = null, ?\Camunda\Orchestration\Api\Model\UserTaskEffectiveVariableSearchQueryRequest $userTaskEffectiveVariableSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\UserTaskApi::contentTypes['searchUserTaskEffectiveVariables'][0]): \Camunda\Orchestration\Api\Model\VariableSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\UserTaskApi::class)->searchUserTaskEffectiveVariables($userTaskKey, $truncateValues, $userTaskEffectiveVariableSearchQueryRequest, $contentType);
    }

    /**
     * Operation searchUserTaskVariables
     *
     * Search user task variables
     *
     * @param  string $userTaskKey The key of the user task. (required)
     * @param  bool|null $truncateValues When true (default), long variable values in the response are truncated. When false, full variable values are returned. (optional)
     * @param  \Camunda\Orchestration\Api\Model\UserTaskVariableSearchQueryRequest|null $userTaskVariableSearchQueryRequest userTaskVariableSearchQueryRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchUserTaskVariables'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\VariableSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchUserTaskVariables(string $userTaskKey, ?bool $truncateValues = null, ?\Camunda\Orchestration\Api\Model\UserTaskVariableSearchQueryRequest $userTaskVariableSearchQueryRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\UserTaskApi::contentTypes['searchUserTaskVariables'][0]): \Camunda\Orchestration\Api\Model\VariableSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\UserTaskApi::class)->searchUserTaskVariables($userTaskKey, $truncateValues, $userTaskVariableSearchQueryRequest, $contentType);
    }

    /**
     * Operation searchUserTasks
     *
     * Search user tasks
     *
     * @param  \Camunda\Orchestration\Api\Model\UserTaskSearchQuery|null $userTaskSearchQuery userTaskSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchUserTasks'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\UserTaskSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchUserTasks(?\Camunda\Orchestration\Api\Model\UserTaskSearchQuery $userTaskSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\UserTaskApi::contentTypes['searchUserTasks'][0]): \Camunda\Orchestration\Api\Model\UserTaskSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\UserTaskApi::class)->searchUserTasks($userTaskSearchQuery, $contentType);
    }

    /**
     * Operation unassignUserTask
     *
     * Unassign user task
     *
     * @param  string $userTaskKey The key of the user task. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['unassignUserTask'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function unassignUserTask(string $userTaskKey, string $contentType = \Camunda\Orchestration\Api\Api\UserTaskApi::contentTypes['unassignUserTask'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\UserTaskApi::class)->unassignUserTask($userTaskKey, $contentType);
    }

    /**
     * Operation updateUserTask
     *
     * Update user task
     *
     * @param  string $userTaskKey The key of the user task to update. (required)
     * @param  \Camunda\Orchestration\Api\Model\UserTaskUpdateRequest|null $userTaskUpdateRequest userTaskUpdateRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['updateUserTask'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\ProblemDetail|null
     */
    public function updateUserTask(string $userTaskKey, ?\Camunda\Orchestration\Api\Model\UserTaskUpdateRequest $userTaskUpdateRequest = null, string $contentType = \Camunda\Orchestration\Api\Api\UserTaskApi::contentTypes['updateUserTask'][0]): ?\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\UserTaskApi::class)->updateUserTask($userTaskKey, $userTaskUpdateRequest, $contentType);
    }

    /**
     * Operation getVariable
     *
     * Get variable
     *
     * @param  string $variableKey The variable key. (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getVariable'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\VariableResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function getVariable(string $variableKey, string $contentType = \Camunda\Orchestration\Api\Api\VariableApi::contentTypes['getVariable'][0]): \Camunda\Orchestration\Api\Model\VariableResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\VariableApi::class)->getVariable($variableKey, $contentType);
    }

    /**
     * Operation searchVariables
     *
     * Search variables
     *
     * @param  bool|null $truncateValues When true (default), long variable values in the response are truncated. When false, full variable values are returned. (optional)
     * @param  \Camunda\Orchestration\Api\Model\VariableSearchQuery|null $variableSearchQuery variableSearchQuery (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['searchVariables'] to see the possible values for this operation
     *
     * @return \Camunda\Orchestration\Api\Model\VariableSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
     */
    public function searchVariables(?bool $truncateValues = null, ?\Camunda\Orchestration\Api\Model\VariableSearchQuery $variableSearchQuery = null, string $contentType = \Camunda\Orchestration\Api\Api\VariableApi::contentTypes['searchVariables'][0]): \Camunda\Orchestration\Api\Model\VariableSearchQueryResult|\Camunda\Orchestration\Api\Model\ProblemDetail
    {
        return $this->api(\Camunda\Orchestration\Api\Api\VariableApi::class)->searchVariables($truncateValues, $variableSearchQuery, $contentType);
    }

}
