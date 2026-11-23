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
        self::assertFalse($timedOut, 'drainOutputBuffers() did not return within 10s');
        self::assertSame('level=1', $stderr);
        self::assertSame('keptinner', $stdout);
    }
}
