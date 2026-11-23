<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance\Runtime;

use PHPUnit\Framework\TestCase;

/**
 * A forked child drains output buffers before it kills itself; a
 * non-removable buffer must not trap it in an endless loop.
 */
final class JobWorkerOutputBufferTest extends TestCase
{
    public function testDrainStopsAtNonRemovableBuffer(): void
    {
        $autoload = \dirname(__DIR__, 3) . '/vendor/autoload.php';
        $code = 'require ' . var_export($autoload, true) . ';'
            . 'ob_start(null, 0, PHP_OUTPUT_HANDLER_CLEANABLE | PHP_OUTPUT_HANDLER_FLUSHABLE);'
            . 'echo "kept";'
            . 'ob_start(); echo "inner";'
            . '\Camunda\Orchestration\Worker\JobWorker::drainOutputBuffers();'
            . 'fwrite(STDERR, "level=" . ob_get_level());';
        [$stdout, $stderr, $timedOut] = self::runPhp($code);
        self::assertFalse($timedOut, 'drainOutputBuffers() did not return within 10s');
        self::assertSame('level=1', $stderr);
        self::assertSame('keptinner', $stdout);
    }

    public function testTerminateSkipsDestructorsAndShutdownFunctions(): void
    {
        self::requirePosix();
        $code = self::prelude()
            . 'register_shutdown_function(function () { echo "shutdown"; });'
            . '$o = new class { public function __destruct() { echo "destruct"; } };'
            . 'echo "before";'
            . '\Camunda\Orchestration\Worker\JobWorker::terminateChild(0);'
            . 'echo "after";';
        [$stdout, , $timedOut] = self::runPhp($code);
        self::assertFalse($timedOut);
        self::assertSame('before', $stdout);
    }

    public function testTerminateSurvivesThrowingOutputHandler(): void
    {
        self::requirePosix();
        $code = self::prelude()
            . 'register_shutdown_function(function () { fwrite(STDERR, "shutdown"); });'
            . 'ob_start(function () { throw new \RuntimeException("boom"); });'
            . 'echo "x";'
            . 'try { \Camunda\Orchestration\Worker\JobWorker::terminateChild(0); }'
            . ' catch (\Throwable $e) { fwrite(STDERR, "escaped"); }'
            . 'fwrite(STDERR, "after");';
        [, $stderr, $timedOut] = self::runPhp($code);
        self::assertFalse($timedOut);
        self::assertStringNotContainsString('escaped', $stderr);
        self::assertStringNotContainsString('after', $stderr);
        self::assertStringNotContainsString('shutdown', $stderr);
    }

    private static function requirePosix(): void
    {
        if (!\function_exists('posix_kill') || !\defined('SIGKILL')) {
            self::markTestSkipped('ext-posix and ext-pcntl required');
        }
    }

    private static function prelude(): string
    {
        return 'require ' . var_export(\dirname(__DIR__, 3) . '/vendor/autoload.php', true) . ';';
    }

    /**
     * @return array{string, string, bool}
     */
    private static function runPhp(string $code): array
    {
        $proc = proc_open([\PHP_BINARY, '-r', $code], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        self::assertIsResource($proc);
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);
        $stdout = '';
        $stderr = '';
        $deadline = microtime(true) + 10;
        $timedOut = false;
        while (!feof($pipes[1]) || !feof($pipes[2])) {
            if (microtime(true) > $deadline) {
                $timedOut = true;
                proc_terminate($proc, 9);
                break;
            }
            $read = [];
            foreach ([1, 2] as $i) {
                if (!feof($pipes[$i])) {
                    $read[] = $pipes[$i];
                }
            }
            $write = null;
            $except = null;
            if (stream_select($read, $write, $except, 0, 100000) === false) {
                break;
            }
            $stdout .= (string) stream_get_contents($pipes[1]);
            $stderr .= (string) stream_get_contents($pipes[2]);
        }
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($proc);

        return [$stdout, $stderr, $timedOut];
    }
}
