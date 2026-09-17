<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Camunda\Orchestration\Worker\ForkedWorkerSupport;

final class ForkedWorkerSupportTest extends TestCase
{
    public function testWaitForHandledByPidMarkerReturnsValidMarker(): void
    {
        $path = $this->createTempFile();
        file_put_contents($path, ForkedWorkerSupport::encodePidMarker('4242', 1010, 'run-1'));

        try {
            $marker = ForkedWorkerSupport::waitForHandledByPidMarker($path, 0);

            self::assertSame(
                ['handledByPid' => '4242', 'handledByParentPid' => '1010', 'runId' => 'run-1'],
                $marker,
            );
        } finally {
            unlink($path);
        }
    }

    public function testWaitForHandledByPidMarkerRejectsInvalidJson(): void
    {
        $path = $this->createTempFile();
        file_put_contents($path, '{invalid');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('PID marker is invalid');

        try {
            ForkedWorkerSupport::waitForHandledByPidMarker($path, 0);
        } finally {
            unlink($path);
        }
    }

    public function testValidateHandledByPidMarkerRejectsParentPidMismatch(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Expected the forked worker to report parent PID 1010, got 2020.');

        ForkedWorkerSupport::validateHandledByPidMarker([
            'handledByPid' => '4242',
            'handledByParentPid' => '2020',
            'runId' => 'run-1',
        ], 'run-1', 1010);
    }

    public function testEncodePidMarkerWrapsJsonEncodingFailures(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot encode the worker PID marker');

        ForkedWorkerSupport::encodePidMarker("\xB1\x31", 1010, 'run-1');
    }

    private function createTempFile(): string
    {
        $path = tempnam(sys_get_temp_dir(), 'forked-worker-support-');
        self::assertIsString($path);

        return $path;
    }
}
