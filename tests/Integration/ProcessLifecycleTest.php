<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Integration;

use Camunda\Orchestration\Api\Api\ProcessInstanceApi;
use Camunda\Orchestration\Api\Model\ActivatedJobResult;
use Camunda\Orchestration\Api\Model\CreateProcessInstanceResult;
use Camunda\Orchestration\Api\Model\DeploymentResult;
use Camunda\Orchestration\Api\Model\ProcessInstanceCreationInstructionById;
use Camunda\Orchestration\Worker\JobActionClient;
use Camunda\Orchestration\Worker\JobWorkerOptions;

final class ProcessLifecycleTest extends IntegrationTestCase
{
    public function testDeployCreateAndProcessJob(): void
    {
        // 1. Deploy the process.
        $deployment = $this->client->deployResourcesFromFiles(
            __DIR__ . '/resources/greet-process.bpmn',
        );
        self::assertInstanceOf(DeploymentResult::class, $deployment);

        // 2. Start an instance.
        $processInstances = $this->client->api(ProcessInstanceApi::class);
        $instruction = new ProcessInstanceCreationInstructionById([
            'processDefinitionId' => 'greet-process',
            'variables' => ['name' => 'Ada'],
        ]);
        $created = $processInstances->createProcessInstance($instruction);
        self::assertInstanceOf(CreateProcessInstanceResult::class, $created);

        // 3. Run a worker until the single job is completed.
        $worker = $this->client->createJobWorker(new JobWorkerOptions(
            type: 'greet',
            maxJobs: 1,
            requestTimeoutMs: 5_000,
        ));

        $handled = false;
        $deadline = microtime(true) + 30.0;
        while (!$handled && microtime(true) < $deadline) {
            $processed = $worker->pollOnce(
                function (ActivatedJobResult $job, JobActionClient $action) use (&$handled): array {
                    $handled = true;
                    return ['greeting' => 'Hello, Ada'];
                },
            );
            if ($processed === 0) {
                usleep(200_000);
            }
        }

        self::assertTrue($handled, 'Expected the greet job to be activated and completed.');
    }
}
