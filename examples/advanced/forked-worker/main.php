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
        $handledByPid = waitForHandledByPid($handledByPidFile);
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
        $cleanupErrors = array_values(array_filter([
            cleanupFile($handledByPidFile, 'worker PID marker'),
            cleanupFile($resource, 'BPMN resource'),
        ]));
        if ($cleanupErrors !== []) {
            $cleanupMessage = implode(' ', $cleanupErrors);
            if ($completed) {
                throw new \RuntimeException($cleanupMessage);
            }

            fwrite(STDERR, "Forked-worker cleanup warning: $cleanupMessage\n");
        }
    }
}

function cleanupFile(string $path, string $label): ?string
{
    if (is_file($path) && !unlink($path)) {
        return "Cannot remove temporary $label file: $path";
    }

    return null;
}

function waitForHandledByPid(string $path, int $timeoutSeconds = 5): string
{
    $deadline = microtime(true) + $timeoutSeconds;
    $lastState = 'PID marker not yet recorded';

    do {
        $handledByPid = file_get_contents($path);
        if ($handledByPid !== false) {
            $handledByPid = trim($handledByPid);
            if ($handledByPid !== '') {
                return $handledByPid;
            }

            $lastState = 'PID marker is still empty';
        } else {
            $lastState = 'PID marker is not readable';
        }

        usleep(200_000);
    } while (microtime(true) < $deadline);

    throw new \RuntimeException(
        "The forked worker did not record a handler PID within $timeoutSeconds seconds ($lastState).",
    );
}

try {
    run();
} catch (\Throwable $error) {
    fwrite(STDERR, "Forked-worker example failed: {$error->getMessage()}\n");
    exit(1);
}
