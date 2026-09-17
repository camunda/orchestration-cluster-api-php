<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Examples\Advanced\ForkedWorker;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/internal/ExampleSupport.php';

use Camunda\Orchestration\Api\Model\ActivatedJobResult;
use Camunda\Orchestration\Examples\Advanced\Internal\ExampleSupport;
use Camunda\Orchestration\Semantic\ProcessInstanceKey;
use Camunda\Orchestration\Worker\JobActionClient;
use Camunda\Orchestration\Worker\JobWorkerOptions;

function run(): void
{
    if (!function_exists('pcntl_fork')) {
        fwrite(STDOUT, "Skipping forked-worker example: ext-pcntl is not available in this PHP runtime.\n");
        return;
    }

    $client = ExampleSupport::client();
    $runId = ExampleSupport::runId();
    $processId = 'php-sdk-forked-worker-' . $runId;
    $jobType = 'php-sdk-forked-job-' . $runId;
    $resource = ExampleSupport::renderBpmn(__DIR__ . '/forked-worker.bpmn', [
        '__PROCESS_ID__' => $processId,
        '__JOB_TYPE__' => $jobType,
    ]);
    $instance = null;
    $completed = false;

    try {
        ExampleSupport::deploy($client, $resource);
        $instance = ExampleSupport::startProcess($client, $processId, ['input' => 'forked']);
        $worker = $client->createJobWorker(new JobWorkerOptions(
            type: $jobType,
            maxJobs: 1,
            timeoutMs: 15_000,
            requestTimeoutMs: 1_000,
            workerName: 'php-sdk-forked-worker',
            forked: true,
        ));

        $handled = false;
        $deadline = microtime(true) + 30;

        while (!$handled && microtime(true) < $deadline) {
            $processed = $worker->pollOnce(
                static function (ActivatedJobResult $job, JobActionClient $action) use (&$handled): array {
                    $handled = true;

                    return [
                        'handledByPid' => getmypid(),
                    ];
                },
            );
            if ($processed === 0) {
                usleep(200_000);
            }
        }

        if (!$handled) {
            throw new \RuntimeException('The forked worker did not receive its job.');
        }

        $result = ExampleSupport::waitForCompletion($client, $instance);
        $completed = true;
        printf("Forked worker completed process instance %s (%s).\n", $instance, $result->getState()->value);
    } finally {
        if (!$completed && $instance instanceof ProcessInstanceKey) {
            ExampleSupport::cancelIfActive($client, $instance);
        }
        if (is_file($resource)) {
            unlink($resource);
        }
    }
}

try {
    run();
} catch (\Throwable $error) {
    fwrite(STDERR, "Forked-worker example failed: {$error->getMessage()}\n");
    exit(1);
}
