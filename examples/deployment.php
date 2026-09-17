<?php

/**
 * Compilable usage examples for deployment operations.
 */

declare(strict_types=1);

namespace Camunda\Orchestration\Examples;

use Camunda\Orchestration\Api\Model\DeploymentResult;
use Camunda\Orchestration\CamundaClient;

// region DeployResources
function deploy_resources(CamundaClient $client): void
{
    $result = $client->deployResourcesFromFiles('order-process.bpmn', 'pricing.dmn');

    if ($result instanceof DeploymentResult) {
        echo 'Deployment key: ', (string) $result->getDeploymentKey(), "\n";
        foreach ($result->getDeployments() as $deployment) {
            $process = $deployment->getProcessDefinition();
            if ($process !== null) {
                echo '  Process: ', (string) $process->getProcessDefinitionId(), "\n";
            }
        }
    }
}
// endregion DeployResources

// region DeploySingleResource
function deploy_single_resource(CamundaClient $client): void
{
    $result = $client->deployResourcesFromFiles(__DIR__ . '/order-process.bpmn');
    // $result is a DeploymentResult (or ProblemDetail on a handled error).
}
// endregion DeploySingleResource
