<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance\Runtime;

use Camunda\Orchestration\Api\Model\ActivatedJobResult;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Config\ConfigResolver;
use Camunda\Orchestration\Tests\Support\JobFixtures;
use Camunda\Orchestration\Worker\JobActionClient;
use Camunda\Orchestration\Worker\JobWorkerOptions;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\RequiresFunction;
use PHPUnit\Framework\TestCase;

/**
 * Issue #190: a CamundaClient used in a process other than the one that built it (a forked
 * child using the parent's client) raises exactly one E_USER_WARNING per process, and the
 * request still goes through.
 */
#[RequiresFunction('pcntl_fork')]
#[RequiresFunction('posix_kill')]
final class ForeignProcessWarningTest extends TestCase
{
    private string $log = '';

    protected function setUp(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'orchestration-foreign-process-');
        self::assertIsString($path);
        $this->log = $path;
    }

    protected function tearDown(): void
    {
        if (is_file($this->log)) {
            unlink($this->log);
        }
    }

    public function testTheParentClientWarnsOnceInAForkedChildAndStillSends(): void
    {
        $client = self::client([new Response(204), new Response(204)]);
        $log = $this->log;

        $this->inChild(static function () use ($client, $log): void {
            $warnings = [];
            set_error_handler(static function (int $level, string $message) use (&$warnings): bool {
                $warnings[] = [$level, $message];
                return true;
            });
            $first = $client->completeJob('1');
            $second = $client->completeJob('2');
            restore_error_handler();
            file_put_contents($log, json_encode([
                'warnings' => $warnings,
                'sent' => $first === null && $second === null,
            ], \JSON_THROW_ON_ERROR));
        });

        $result = json_decode((string) file_get_contents($this->log), true, 512, \JSON_THROW_ON_ERROR);
        self::assertIsArray($result);
        self::assertTrue($result['sent'], 'both requests went through');
        self::assertIsArray($result['warnings']);
        self::assertCount(1, $result['warnings'], 'exactly one warning per client per process');
        self::assertIsArray($result['warnings'][0]);
        [$level, $message] = $result['warnings'][0];
        self::assertSame(\E_USER_WARNING, $level);
        self::assertIsString($message);
        self::assertStringContainsString('$action->client()', $message);
        self::assertStringContainsString('forChildProcess()', $message);
    }

    public function testNoWarningForForChildProcessInTheChild(): void
    {
        $client = self::client([new Response(204)]);
        $log = $this->log;

        $this->inChild(static function () use ($client, $log): void {
            $warnings = 0;
            set_error_handler(static function () use (&$warnings): bool {
                $warnings++;
                return true;
            });
            $client->forChildProcess()->completeJob('1');
            restore_error_handler();
            file_put_contents($log, (string) $warnings);
        });

        self::assertSame('0', file_get_contents($this->log));
    }

    public function testNoWarningForActionClientInAForkedWorker(): void
    {
        $log = $this->log;
        $client = self::client([JobFixtures::activation('1')]);
        $worker = $client->createJobWorker(new JobWorkerOptions(type: 'greet', maxJobs: 1, forked: true));

        $processed = $worker->pollOnce(static function (ActivatedJobResult $job, JobActionClient $action) use ($log): null {
            set_error_handler(static function (int $level, string $message) use ($log): bool {
                file_put_contents($log, $message, FILE_APPEND);
                return true;
            });
            $action->client()->completeJob('9');

            return null;
        });

        self::assertSame(1, $processed);
        self::assertSame('', file_get_contents($this->log));
    }

    public function testNoWarningInTheOwningProcess(): void
    {
        $client = self::client([new Response(204), new Response(204)]);
        $warnings = 0;
        set_error_handler(static function () use (&$warnings): bool {
            $warnings++;
            return true;
        });
        try {
            $client->completeJob('1');
            $client->forChildProcess()->completeJob('2');
        } finally {
            restore_error_handler();
        }

        self::assertSame(0, $warnings);
    }

    /**
     * @param list<\Psr\Http\Message\ResponseInterface> $queue
     */
    private static function client(array $queue): CamundaClient
    {
        return new CamundaClient(
            ConfigResolver::resolve(overrides: ['CAMUNDA_AUTH_STRATEGY' => 'NONE'], environment: []),
            self::guzzle($queue),
            static fn (): GuzzleClient => self::guzzle([new Response(204), new Response(204)]),
        );
    }

    /**
     * @param list<\Psr\Http\Message\ResponseInterface> $queue
     */
    private static function guzzle(array $queue): GuzzleClient
    {
        return new GuzzleClient(['handler' => HandlerStack::create(new MockHandler($queue)), 'http_errors' => false]);
    }

    /**
     * Run $body in a forked child, which then ends without running destructors.
     *
     * @param \Closure(): void $body
     */
    private function inChild(\Closure $body): void
    {
        $pid = pcntl_fork();
        self::assertNotSame(-1, $pid, 'fork failed');
        if ($pid === 0) {
            try {
                $body();
            } finally {
                posix_kill((int) getmypid(), \SIGKILL);
            }
        }
        pcntl_waitpid($pid, $status);
    }
}
