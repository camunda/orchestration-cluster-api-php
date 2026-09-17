<?php

/**
 * Compilable usage examples that back the snippets in README.md.
 *
 * Every example is static-analysed at `max` level during CI, so the snippets in
 * the documentation are guaranteed to reflect the real, current API surface.
 */

declare(strict_types=1);

namespace Camunda\Orchestration\Examples;

use Camunda\Orchestration\Api\Api\ProcessInstanceApi;
use Camunda\Orchestration\Api\Model\ActivatedJobResult;
use Camunda\Orchestration\Api\Model\ProcessInstanceCreationInstructionById;
use Camunda\Orchestration\CamundaAsyncClient;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Config\CamundaConfiguration;
use Camunda\Orchestration\Semantic\ProcessDefinitionId;
use Camunda\Orchestration\Worker\JobActionClient;
use Camunda\Orchestration\Worker\JobWorkerOptions;

// region ReadmeSyncClient
function readme_sync_client(): void
{
    $client = CamundaClient::fromEnvironment();

    $result = $client->deployResourcesFromFiles('order-process.bpmn');
    // ...
}
// endregion ReadmeSyncClient

// region ReadmeAsyncClient
function readme_async_client(): void
{
    $client = CamundaAsyncClient::fromEnvironment();

    $client->deployResourcesFromFilesAsync('order-process.bpmn')
        ->then(static function ($result): void {
            // handle the DeploymentResult once the request resolves
        })
        ->wait();
}
// endregion ReadmeAsyncClient

// region ReadmeSemanticTypes
function readme_semantic_types(): void
{
    // Identifiers are distinct value objects — you cannot accidentally pass a
    // process-definition id where a tenant id is expected.
    $instruction = (new ProcessInstanceCreationInstructionById())
        ->setProcessDefinitionId(ProcessDefinitionId::of('order-process'))
        ->setVariables(['orderId' => 'ORD-42']);

    // Value objects validate their format on construction and stringify cleanly.
    $definitionId = new ProcessDefinitionId('order-process');
    echo (string) $definitionId, "\n";
}
// endregion ReadmeSemanticTypes

// region ReadmeZeroConfig
function readme_zero_config(): void
{
    // Reads CAMUNDA_REST_ADDRESS and auto-detects the auth strategy from the
    // ambient environment (NONE / BASIC / OAUTH).
    $client = CamundaClient::fromEnvironment();
}
// endregion ReadmeZeroConfig

// region ReadmeProgrammaticConfig
function readme_programmatic_config(): void
{
    $config = new CamundaConfiguration(
        restAddress: 'https://my-cluster.example.com/v2',
        authStrategy: 'OAUTH',
        clientId: 'my-client-id',
        clientSecret: 'my-client-secret',
    );

    $client = CamundaClient::fromConfiguration($config);
}
// endregion ReadmeProgrammaticConfig

// region ReadmeBasicAuth
function readme_basic_auth(): void
{
    $client = CamundaClient::fromEnvironment([
        'CAMUNDA_AUTH_STRATEGY' => 'BASIC',
        'CAMUNDA_BASIC_AUTH_USERNAME' => 'demo',
        'CAMUNDA_BASIC_AUTH_PASSWORD' => 'demo',
    ]);
}
// endregion ReadmeBasicAuth

// region ReadmeDeployResources
function readme_deploy_resources(): void
{
    $client = CamundaClient::fromEnvironment();

    $result = $client->deployResourcesFromFiles('order-process.bpmn', 'pricing.dmn');
    // $result is a DeploymentResult (or ProblemDetail on a handled error).
}
// endregion ReadmeDeployResources

// region ReadmeJobWorker
function readme_job_worker(): void
{
    $client = CamundaClient::fromEnvironment();

    $worker = $client->createJobWorker(new JobWorkerOptions(
        type: 'payment-processing',
        maxJobs: 5,
        timeoutMs: 30_000,
    ));

    $worker->run(function (ActivatedJobResult $job, JobActionClient $action): array {
        // ... perform the work ...
        return ['status' => 'paid'];
    });
}
// endregion ReadmeJobWorker

// region ReadmeFlatFacade
function readme_flat_facade(): void
{
    $client = CamundaClient::fromEnvironment();
    $instruction = new ProcessInstanceCreationInstructionById([
        'processDefinitionId' => 'order-process',
    ]);

    $topology = $client->getTopology();
    $result = $client->createProcessInstance($instruction);

    $async = CamundaAsyncClient::fromEnvironment();
    $async->getTopology()
        ->then(static function ($asyncTopology): void {
            // handle the asynchronous topology response
        })
        ->wait();
}
// endregion ReadmeFlatFacade

// region ReadmeApiAccessor
function readme_api_accessor(): void
{
    $client = CamundaClient::fromEnvironment();
    $instruction = new ProcessInstanceCreationInstructionById([
        'processDefinitionId' => 'order-process',
    ]);

    $processInstances = $client->api(ProcessInstanceApi::class);
    $result = $processInstances->createProcessInstance($instruction);
}
// endregion ReadmeApiAccessor
