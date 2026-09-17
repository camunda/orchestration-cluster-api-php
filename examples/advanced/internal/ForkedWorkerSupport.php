<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Examples\Advanced\Internal;

use JsonException;
use RuntimeException;

final class ForkedWorkerSupport
{
    public static function encodePidMarker(string $handledByPid, int $parentPid, string $runId): string
    {
        try {
            return json_encode([
                'handledByPid' => $handledByPid,
                'handledByParentPid' => (string) $parentPid,
                'runId' => $runId,
            ], JSON_THROW_ON_ERROR);
        } catch (JsonException $error) {
            throw new RuntimeException("Cannot encode the worker PID marker: {$error->getMessage()}", 0, $error);
        }
    }

    /**
     * @return array{handledByPid?: string, handledByParentPid?: string, runId?: string}
     */
    public static function waitForHandledByPidMarker(string $path, int $timeoutSeconds = 5): array
    {
        $deadline = microtime(true) + $timeoutSeconds;
        $lastState = 'PID marker not yet recorded';

        do {
            $marker = file_get_contents($path);
            if ($marker !== false) {
                $marker = trim($marker);
                if ($marker !== '') {
                    try {
                        $decoded = json_decode($marker, true, flags: JSON_THROW_ON_ERROR);
                    } catch (JsonException) {
                        $lastState = 'PID marker is invalid';
                        usleep(200_000);
                        continue;
                    }
                    if (is_array($decoded)) {
                        return $decoded;
                    }

                    $lastState = 'PID marker payload is not an object';
                    usleep(200_000);
                    continue;
                }

                $lastState = 'PID marker is still empty';
            } else {
                $lastState = 'PID marker is not readable';
            }

            usleep(200_000);
        } while (microtime(true) < $deadline);

        throw new RuntimeException(
            "The forked worker did not record a handler PID within $timeoutSeconds seconds ($lastState).",
        );
    }

    /**
     * @param array{handledByPid?: string, handledByParentPid?: string, runId?: string} $marker
     */
    public static function validateHandledByPidMarker(array $marker, string $runId, int $parentPid): string
    {
        if (($marker['runId'] ?? null) !== $runId) {
            throw new RuntimeException('The forked worker PID marker does not belong to this run.');
        }
        if (($marker['handledByParentPid'] ?? null) !== (string) $parentPid) {
            throw new RuntimeException(
                sprintf(
                    'Expected the forked worker to report parent PID %d, got %s.',
                    $parentPid,
                    (string) ($marker['handledByParentPid'] ?? 'unknown'),
                ),
            );
        }

        $handledByPid = (string) ($marker['handledByPid'] ?? '');
        if ($handledByPid === '') {
            throw new RuntimeException('The forked worker PID marker has no handledByPid value.');
        }
        if ($handledByPid === (string) $parentPid) {
            throw new RuntimeException(
                sprintf('Expected a forked worker child process, but job ran in parent PID %d.', $parentPid),
            );
        }

        return $handledByPid;
    }

    public static function cleanupFile(string $path, string $label): ?string
    {
        if (is_file($path) && !unlink($path)) {
            return "Cannot remove temporary $label file: $path";
        }

        return null;
    }
}
