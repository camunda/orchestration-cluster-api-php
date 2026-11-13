<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance\Runtime;

use Camunda\Orchestration\Worker\ForkedWorkerSupport;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresFunction;
use PHPUnit\Framework\TestCase;

/**
 * Failure and boundary paths of the PID-marker protocol used to prove that forked workers really
 * ran jobs in a child process.
 */
final class ForkedWorkerSupportEdgeCasesTest extends TestCase
{
    /** @var list<string> */
    private array $cleanup = [];

    protected function tearDown(): void
    {
        foreach (array_reverse($this->cleanup) as $path) {
            if (is_dir($path)) {
                @chmod($path, 0700);
                @rmdir($path);
            } elseif (file_exists($path)) {
                @unlink($path);
            }
        }
    }

    public function testEncodePidMarkerProducesTheDocumentedShape(): void
    {
        $decoded = json_decode(ForkedWorkerSupport::encodePidMarker('4242', 1010, 'run-1'), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame(['handledByPid' => '4242', 'handledByParentPid' => '1010', 'runId' => 'run-1'], $decoded);
    }

    public function testMissingMarkerFileTimesOutWithAnExplanation(): void
    {
        $missing = sys_get_temp_dir() . '/orchestration-no-such-marker-' . bin2hex(random_bytes(6));
        $warnings = [];
        set_error_handler(static function (int $severity, string $message) use (&$warnings): bool {
            $warnings[] = $message;

            return true;
        });

        try {
            ForkedWorkerSupport::waitForHandledByPidMarker($missing, 0);
            self::fail('Expected a RuntimeException.');
        } catch (\RuntimeException $e) {
            self::assertStringContainsString('did not record a handler PID within 0 seconds', $e->getMessage());
            self::assertStringContainsString('PID marker is not readable', $e->getMessage());
        } finally {
            restore_error_handler();
        }

        self::assertNotSame([], $warnings, 'reading a missing file is reported by PHP as a warning');
    }

    public function testEmptyMarkerFileTimesOutAsStillEmpty(): void
    {
        $path = $this->tempFile('');

        try {
            ForkedWorkerSupport::waitForHandledByPidMarker($path, 0);
            self::fail('Expected a RuntimeException.');
        } catch (\RuntimeException $e) {
            self::assertStringContainsString('PID marker is still empty', $e->getMessage());
        }
    }

    public function testWhitespaceOnlyMarkerFileCountsAsEmpty(): void
    {
        $path = $this->tempFile("  \n\t\n");

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('PID marker is still empty');

        ForkedWorkerSupport::waitForHandledByPidMarker($path, 0);
    }

    #[DataProvider('malformedPayloads')]
    public function testMalformedMarkerPayloadsAreRejected(string $payload, string $expectedMessage): void
    {
        $path = $this->tempFile($payload);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage($expectedMessage);

        ForkedWorkerSupport::waitForHandledByPidMarker($path, 0);
    }

    /** @return iterable<string, array{string, string}> */
    public static function malformedPayloads(): iterable
    {
        yield 'scalar JSON' => ['"just a string"', 'payload is not an object'];
        yield 'numeric JSON' => ['42', 'payload is not an object'];
        yield 'JSON null' => ['null', 'payload is not an object'];
        yield 'list instead of object' => ['[1,2,3]', 'PID marker has invalid fields'];
        yield 'missing runId' => ['{"handledByPid":"1","handledByParentPid":"2"}', 'PID marker has invalid fields'];
        yield 'missing handledByPid' => ['{"handledByParentPid":"2","runId":"r"}', 'PID marker has invalid fields'];
        yield 'numeric parent pid' => ['{"handledByPid":"1","handledByParentPid":2,"runId":"r"}', 'PID marker has invalid fields'];
        yield 'numeric run id' => ['{"handledByPid":"1","handledByParentPid":"2","runId":7}', 'PID marker has invalid fields'];
        yield 'truncated JSON' => ['{"handledByPid":"1"', 'PID marker is invalid'];
    }

    #[RequiresFunction('pcntl_fork')]
    public function testMarkerWrittenWhilePollingIsPickedUp(): void
    {
        $path = $this->tempFile('');
        $pid = pcntl_fork();
        self::assertNotSame(-1, $pid);

        if ($pid === 0) {
            usleep(300_000);
            file_put_contents($path, ForkedWorkerSupport::encodePidMarker('555', 444, 'late-run'));
            exit(0);
        }

        try {
            $marker = ForkedWorkerSupport::waitForHandledByPidMarker($path, 5);
        } finally {
            pcntl_waitpid($pid, $status);
        }

        self::assertSame(['handledByPid' => '555', 'handledByParentPid' => '444', 'runId' => 'late-run'], $marker);
    }

    public function testValidationAcceptsAChildMarkerAndReturnsItsPid(): void
    {
        $pid = ForkedWorkerSupport::validateHandledByPidMarker(
            ['handledByPid' => '4242', 'handledByParentPid' => '1010', 'runId' => 'run-1'],
            'run-1',
            1010,
        );

        self::assertSame('4242', $pid);
    }

    public function testValidationRejectsAMarkerFromAnotherRun(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('does not belong to this run');

        ForkedWorkerSupport::validateHandledByPidMarker(
            ['handledByPid' => '4242', 'handledByParentPid' => '1010', 'runId' => 'stale-run'],
            'run-1',
            1010,
        );
    }

    public function testValidationRejectsAnEmptyHandlerPid(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('has no handledByPid value');

        ForkedWorkerSupport::validateHandledByPidMarker(
            ['handledByPid' => '', 'handledByParentPid' => '1010', 'runId' => 'run-1'],
            'run-1',
            1010,
        );
    }

    public function testValidationRejectsAJobThatRanInTheParentProcess(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('job ran in parent PID 1010');

        ForkedWorkerSupport::validateHandledByPidMarker(
            ['handledByPid' => '1010', 'handledByParentPid' => '1010', 'runId' => 'run-1'],
            'run-1',
            1010,
        );
    }

    public function testCleanupRemovesAnExistingFile(): void
    {
        $path = $this->tempFile('x');

        self::assertNull(ForkedWorkerSupport::cleanupFile($path, 'marker'));
        self::assertFileDoesNotExist($path);
    }

    public function testCleanupOfAMissingFileIsANoOp(): void
    {
        self::assertNull(ForkedWorkerSupport::cleanupFile('/no/such/dir/marker-file', 'marker'));
    }

    public function testCleanupReportsAFileThatCannotBeRemoved(): void
    {
        if (function_exists('posix_geteuid') && posix_geteuid() === 0) {
            self::markTestSkipped('root can unlink files in read-only directories, so the failure cannot be provoked.');
        }

        $dir = sys_get_temp_dir() . '/orchestration-ro-' . bin2hex(random_bytes(6));
        self::assertTrue(mkdir($dir, 0700));
        $this->cleanup[] = $dir;
        $file = "$dir/marker";
        file_put_contents($file, 'x');
        $this->cleanup[] = $file;
        chmod($dir, 0500);

        $previous = set_error_handler(static fn (): bool => true);
        try {
            $message = ForkedWorkerSupport::cleanupFile($file, 'PID marker');
        } finally {
            restore_error_handler();
            unset($previous);
        }

        self::assertSame("Cannot remove temporary PID marker file: $file", $message);
    }

    private function tempFile(string $contents): string
    {
        $path = tempnam(sys_get_temp_dir(), 'orchestration-marker-');
        self::assertIsString($path);
        file_put_contents($path, $contents);
        $this->cleanup[] = $path;

        return $path;
    }
}
