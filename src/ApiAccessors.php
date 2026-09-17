<?php

declare(strict_types=1);

namespace Camunda\Orchestration;

/**
 * Typed accessors for every generated Camunda Orchestration Cluster API group.
 *
 * Each accessor returns a lazily-constructed, shared API instance wired with the
 * client's configuration, authentication and HTTP stack. This trait is regenerated
 * from the generated API classes by hooks/post_gen/0400_api_accessors.php; do not edit
 * by hand.
 *
 * @internal
 */
trait ApiAccessors
{
    public function adHocSubProcess(): \Camunda\Orchestration\Api\Api\AdHocSubProcessApi
    {
        /** @var \Camunda\Orchestration\Api\Api\AdHocSubProcessApi */
        return $this->api(\Camunda\Orchestration\Api\Api\AdHocSubProcessApi::class);
    }

    public function agentDefinition(): \Camunda\Orchestration\Api\Api\AgentDefinitionApi
    {
        /** @var \Camunda\Orchestration\Api\Api\AgentDefinitionApi */
        return $this->api(\Camunda\Orchestration\Api\Api\AgentDefinitionApi::class);
    }

    public function agentInstance(): \Camunda\Orchestration\Api\Api\AgentInstanceApi
    {
        /** @var \Camunda\Orchestration\Api\Api\AgentInstanceApi */
        return $this->api(\Camunda\Orchestration\Api\Api\AgentInstanceApi::class);
    }

    public function auditLog(): \Camunda\Orchestration\Api\Api\AuditLogApi
    {
        /** @var \Camunda\Orchestration\Api\Api\AuditLogApi */
        return $this->api(\Camunda\Orchestration\Api\Api\AuditLogApi::class);
    }

    public function authentication(): \Camunda\Orchestration\Api\Api\AuthenticationApi
    {
        /** @var \Camunda\Orchestration\Api\Api\AuthenticationApi */
        return $this->api(\Camunda\Orchestration\Api\Api\AuthenticationApi::class);
    }

    public function authorization(): \Camunda\Orchestration\Api\Api\AuthorizationApi
    {
        /** @var \Camunda\Orchestration\Api\Api\AuthorizationApi */
        return $this->api(\Camunda\Orchestration\Api\Api\AuthorizationApi::class);
    }

    public function backup(): \Camunda\Orchestration\Api\Api\BackupApi
    {
        /** @var \Camunda\Orchestration\Api\Api\BackupApi */
        return $this->api(\Camunda\Orchestration\Api\Api\BackupApi::class);
    }

    public function batchOperation(): \Camunda\Orchestration\Api\Api\BatchOperationApi
    {
        /** @var \Camunda\Orchestration\Api\Api\BatchOperationApi */
        return $this->api(\Camunda\Orchestration\Api\Api\BatchOperationApi::class);
    }

    public function clock(): \Camunda\Orchestration\Api\Api\ClockApi
    {
        /** @var \Camunda\Orchestration\Api\Api\ClockApi */
        return $this->api(\Camunda\Orchestration\Api\Api\ClockApi::class);
    }

    public function cluster(): \Camunda\Orchestration\Api\Api\ClusterApi
    {
        /** @var \Camunda\Orchestration\Api\Api\ClusterApi */
        return $this->api(\Camunda\Orchestration\Api\Api\ClusterApi::class);
    }

    public function clusterVariable(): \Camunda\Orchestration\Api\Api\ClusterVariableApi
    {
        /** @var \Camunda\Orchestration\Api\Api\ClusterVariableApi */
        return $this->api(\Camunda\Orchestration\Api\Api\ClusterVariableApi::class);
    }

    public function conditional(): \Camunda\Orchestration\Api\Api\ConditionalApi
    {
        /** @var \Camunda\Orchestration\Api\Api\ConditionalApi */
        return $this->api(\Camunda\Orchestration\Api\Api\ConditionalApi::class);
    }

    public function decisionDefinition(): \Camunda\Orchestration\Api\Api\DecisionDefinitionApi
    {
        /** @var \Camunda\Orchestration\Api\Api\DecisionDefinitionApi */
        return $this->api(\Camunda\Orchestration\Api\Api\DecisionDefinitionApi::class);
    }

    public function decisionInstance(): \Camunda\Orchestration\Api\Api\DecisionInstanceApi
    {
        /** @var \Camunda\Orchestration\Api\Api\DecisionInstanceApi */
        return $this->api(\Camunda\Orchestration\Api\Api\DecisionInstanceApi::class);
    }

    public function decisionRequirements(): \Camunda\Orchestration\Api\Api\DecisionRequirementsApi
    {
        /** @var \Camunda\Orchestration\Api\Api\DecisionRequirementsApi */
        return $this->api(\Camunda\Orchestration\Api\Api\DecisionRequirementsApi::class);
    }

    public function document(): \Camunda\Orchestration\Api\Api\DocumentApi
    {
        /** @var \Camunda\Orchestration\Api\Api\DocumentApi */
        return $this->api(\Camunda\Orchestration\Api\Api\DocumentApi::class);
    }

    public function elementInstance(): \Camunda\Orchestration\Api\Api\ElementInstanceApi
    {
        /** @var \Camunda\Orchestration\Api\Api\ElementInstanceApi */
        return $this->api(\Camunda\Orchestration\Api\Api\ElementInstanceApi::class);
    }

    public function exporting(): \Camunda\Orchestration\Api\Api\ExportingApi
    {
        /** @var \Camunda\Orchestration\Api\Api\ExportingApi */
        return $this->api(\Camunda\Orchestration\Api\Api\ExportingApi::class);
    }

    public function expression(): \Camunda\Orchestration\Api\Api\ExpressionApi
    {
        /** @var \Camunda\Orchestration\Api\Api\ExpressionApi */
        return $this->api(\Camunda\Orchestration\Api\Api\ExpressionApi::class);
    }

    public function form(): \Camunda\Orchestration\Api\Api\FormApi
    {
        /** @var \Camunda\Orchestration\Api\Api\FormApi */
        return $this->api(\Camunda\Orchestration\Api\Api\FormApi::class);
    }

    public function globalListener(): \Camunda\Orchestration\Api\Api\GlobalListenerApi
    {
        /** @var \Camunda\Orchestration\Api\Api\GlobalListenerApi */
        return $this->api(\Camunda\Orchestration\Api\Api\GlobalListenerApi::class);
    }

    public function group(): \Camunda\Orchestration\Api\Api\GroupApi
    {
        /** @var \Camunda\Orchestration\Api\Api\GroupApi */
        return $this->api(\Camunda\Orchestration\Api\Api\GroupApi::class);
    }

    public function incident(): \Camunda\Orchestration\Api\Api\IncidentApi
    {
        /** @var \Camunda\Orchestration\Api\Api\IncidentApi */
        return $this->api(\Camunda\Orchestration\Api\Api\IncidentApi::class);
    }

    public function job(): \Camunda\Orchestration\Api\Api\JobApi
    {
        /** @var \Camunda\Orchestration\Api\Api\JobApi */
        return $this->api(\Camunda\Orchestration\Api\Api\JobApi::class);
    }

    public function license(): \Camunda\Orchestration\Api\Api\LicenseApi
    {
        /** @var \Camunda\Orchestration\Api\Api\LicenseApi */
        return $this->api(\Camunda\Orchestration\Api\Api\LicenseApi::class);
    }

    public function mappingRule(): \Camunda\Orchestration\Api\Api\MappingRuleApi
    {
        /** @var \Camunda\Orchestration\Api\Api\MappingRuleApi */
        return $this->api(\Camunda\Orchestration\Api\Api\MappingRuleApi::class);
    }

    public function message(): \Camunda\Orchestration\Api\Api\MessageApi
    {
        /** @var \Camunda\Orchestration\Api\Api\MessageApi */
        return $this->api(\Camunda\Orchestration\Api\Api\MessageApi::class);
    }

    public function messageSubscription(): \Camunda\Orchestration\Api\Api\MessageSubscriptionApi
    {
        /** @var \Camunda\Orchestration\Api\Api\MessageSubscriptionApi */
        return $this->api(\Camunda\Orchestration\Api\Api\MessageSubscriptionApi::class);
    }

    public function processDefinition(): \Camunda\Orchestration\Api\Api\ProcessDefinitionApi
    {
        /** @var \Camunda\Orchestration\Api\Api\ProcessDefinitionApi */
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessDefinitionApi::class);
    }

    public function processInstance(): \Camunda\Orchestration\Api\Api\ProcessInstanceApi
    {
        /** @var \Camunda\Orchestration\Api\Api\ProcessInstanceApi */
        return $this->api(\Camunda\Orchestration\Api\Api\ProcessInstanceApi::class);
    }

    public function recovery(): \Camunda\Orchestration\Api\Api\RecoveryApi
    {
        /** @var \Camunda\Orchestration\Api\Api\RecoveryApi */
        return $this->api(\Camunda\Orchestration\Api\Api\RecoveryApi::class);
    }

    public function resource(): \Camunda\Orchestration\Api\Api\ResourceApi
    {
        /** @var \Camunda\Orchestration\Api\Api\ResourceApi */
        return $this->api(\Camunda\Orchestration\Api\Api\ResourceApi::class);
    }

    public function role(): \Camunda\Orchestration\Api\Api\RoleApi
    {
        /** @var \Camunda\Orchestration\Api\Api\RoleApi */
        return $this->api(\Camunda\Orchestration\Api\Api\RoleApi::class);
    }

    public function secret(): \Camunda\Orchestration\Api\Api\SecretApi
    {
        /** @var \Camunda\Orchestration\Api\Api\SecretApi */
        return $this->api(\Camunda\Orchestration\Api\Api\SecretApi::class);
    }

    public function setup(): \Camunda\Orchestration\Api\Api\SetupApi
    {
        /** @var \Camunda\Orchestration\Api\Api\SetupApi */
        return $this->api(\Camunda\Orchestration\Api\Api\SetupApi::class);
    }

    public function signal(): \Camunda\Orchestration\Api\Api\SignalApi
    {
        /** @var \Camunda\Orchestration\Api\Api\SignalApi */
        return $this->api(\Camunda\Orchestration\Api\Api\SignalApi::class);
    }

    public function system(): \Camunda\Orchestration\Api\Api\SystemApi
    {
        /** @var \Camunda\Orchestration\Api\Api\SystemApi */
        return $this->api(\Camunda\Orchestration\Api\Api\SystemApi::class);
    }

    public function tenant(): \Camunda\Orchestration\Api\Api\TenantApi
    {
        /** @var \Camunda\Orchestration\Api\Api\TenantApi */
        return $this->api(\Camunda\Orchestration\Api\Api\TenantApi::class);
    }

    public function user(): \Camunda\Orchestration\Api\Api\UserApi
    {
        /** @var \Camunda\Orchestration\Api\Api\UserApi */
        return $this->api(\Camunda\Orchestration\Api\Api\UserApi::class);
    }

    public function userTask(): \Camunda\Orchestration\Api\Api\UserTaskApi
    {
        /** @var \Camunda\Orchestration\Api\Api\UserTaskApi */
        return $this->api(\Camunda\Orchestration\Api\Api\UserTaskApi::class);
    }

    public function variable(): \Camunda\Orchestration\Api\Api\VariableApi
    {
        /** @var \Camunda\Orchestration\Api\Api\VariableApi */
        return $this->api(\Camunda\Orchestration\Api\Api\VariableApi::class);
    }

}
