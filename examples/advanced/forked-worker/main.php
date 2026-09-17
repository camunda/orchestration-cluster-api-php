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
    $parentPid = getmypid();
    $handledByPidFile = tempnam(sys_get_temp_dir(), 'camunda-php-forked-worker-');
    if ($handledByPidFile === false) {
        throw new \RuntimeException('Cannot allocate a temporary PID marker file.');
    }
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

        $deadline = microtime(true) + 30;
        do {
            $processed = $worker->pollOnce(
                static function (ActivatedJobResult $job, JobActionClient $action) use ($handledByPidFile): array {
                    $handledByPid = (string) getmypid();
                    if (file_put_contents($handledByPidFile, $handledByPid) === false) {
                        throw new \RuntimeException('Cannot persist the worker PID marker.');
                    }

                    return [
                        'handledByPid' => $handledByPid,
                    ];
                },
            );
            if ($processed === 0) {
                usleep(200_000);
            }
        } while ($processed === 0 && microtime(true) < $deadline);

        if ($processed === 0) {
            throw new \RuntimeException('The forked worker did not receive its job.');
        }

        $result = ExampleSupport::waitForCompletion($client, $instance);
        $handledByPid = file_get_contents($handledByPidFile);
        if ($handledByPid === false) {
            throw new \RuntimeException('Cannot read the worker PID marker.');
        }
        $handledByPid = trim($handledByPid);
        if ($handledByPid === '') {
            throw new \RuntimeException('The forked worker did not record a handler PID.');
        }
        if ($handledByPid === (string) $parentPid) {
            throw new \RuntimeException(
                sprintf('Expected a forked worker child process, but job ran in parent PID %d.', $parentPid),
            );
        }
        $completed = true;
        printf("Forked worker completed process instance %s (%s).\n", $instance, $result->getState()->value);
    } finally {
        if (!$completed && $instance instanceof ProcessInstanceKey) {
            ExampleSupport::cancelIfActive($client, $instance);
        }
        if (is_file($handledByPidFile)) {
            unlink($handledByPidFile);
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
