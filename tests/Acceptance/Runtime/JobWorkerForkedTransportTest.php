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
use Psr\Http\Message\RequestInterface;

/**
 * Issue #187: forked children must not use the parent's HTTP client (and its keep-alive
 * connections). Each child builds its own client, sends its completion through it, and ends
 * without hanging the parent.
 */
#[RequiresFunction('pcntl_fork')]
final class JobWorkerForkedTransportTest extends TestCase
{
    private string $log = '';

    protected function setUp(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'orchestration-forked-transport-');
        self::assertIsString($path);
        $this->log = $path;
    }

    protected function tearDown(): void
    {
        if (is_file($this->log)) {
            unlink($this->log);
        }
    }

    public function testEachChildSendsItsCompletionOverItsOwnClient(): void
    {
        $log = $this->log;
        $parentPid = (int) getmypid();
        $parentHttp = self::guzzle('parent', $log, [JobFixtures::activation('1', '2', '3')]);
        $client = new CamundaClient(
            ConfigResolver::resolve(overrides: ['CAMUNDA_AUTH_STRATEGY' => 'NONE'], environment: []),
            $parentHttp,
            static fn (): GuzzleClient => self::guzzle('child', $log, [new Response(204), new Response(204)]),
        );
        $worker = $client->createJobWorker(new JobWorkerOptions(type: 'greet', maxJobs: 8, forked: true));

        $processed = $worker->pollOnce(static function (ActivatedJobResult $job, JobActionClient $action) use ($client): null {
            if ($action->client() === $client) {
                throw new \RuntimeException('the child was handed the parent client');
            }

            return null;
        });

        self::assertSame(3, $processed, 'pollOnce() returns once every child has ended');
        $lines = file($log, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        self::assertIsArray($lines);
        $byOrigin = ['parent' => [], 'child' => []];
        foreach ($lines as $line) {
            [$origin, $pid, $path] = explode(' ', $line, 3);
            $byOrigin[$origin][] = [(int) $pid, $path];
        }
        self::assertSame([[$parentPid, '/v2/jobs/activation']], $byOrigin['parent'], 'only the activation goes over the parent client');
        self::assertCount(3, $byOrigin['child']);
        $pids = [];
        foreach ($byOrigin['child'] as [$pid, $path]) {
            self::assertNotSame($parentPid, $pid);
            self::assertMatchesRegularExpression('#^/v2/jobs/[123]/completion$#', $path);
            $pids[] = $pid;
        }
        self::assertCount(3, array_unique($pids), 'one child, and one client, per job');
    }

    public function testAClientWithoutAFactoryIsItsOwnChildClient(): void
    {
        $client = new CamundaClient(
            ConfigResolver::resolve(overrides: ['CAMUNDA_AUTH_STRATEGY' => 'NONE'], environment: []),
            new GuzzleClient(),
        );

        self::assertSame($client, $client->forChildProcess());
    }

    public function testAClientFromConfigurationBuildsAFreshChildClient(): void
    {
        $client = CamundaClient::fromConfiguration(
            ConfigResolver::resolve(overrides: ['CAMUNDA_AUTH_STRATEGY' => 'NONE'], environment: []),
        );

        $child = $client->forChildProcess();

        self::assertNotSame($client, $child);
        self::assertSame($client->configuration(), $child->configuration());
    }

    public function testAChildClientKeepsAMutatedApiConfiguration(): void
    {
        $client = CamundaClient::fromConfiguration(
            ConfigResolver::resolve(overrides: ['CAMUNDA_AUTH_STRATEGY' => 'NONE'], environment: []),
        );
        $client->api(\Camunda\Orchestration\Api\Api\JobApi::class)->getConfig()->setHost('https://override.example.test/v2');

        $child = $client->forChildProcess();
        $childConfig = $child->api(\Camunda\Orchestration\Api\Api\JobApi::class)->getConfig();

        self::assertSame('https://override.example.test/v2', $childConfig->getHost());
        self::assertNotSame($client->api(\Camunda\Orchestration\Api\Api\JobApi::class)->getConfig(), $childConfig);
    }

    /**
     * @param list<\Psr\Http\Message\ResponseInterface> $queue
     */
    private static function guzzle(string $origin, string $log, array $queue): GuzzleClient
    {
        $stack = HandlerStack::create(new MockHandler($queue));
        $stack->push(static function (callable $handler) use ($origin, $log): callable {
            return static function (RequestInterface $request, array $options) use ($handler, $origin, $log) {
                file_put_contents(
                    $log,
                    sprintf("%s %d %s\n", $origin, (int) getmypid(), $request->getUri()->getPath()),
                    FILE_APPEND | LOCK_EX,
                );

                return $handler($request, $options);
            };
        });

        return new GuzzleClient(['handler' => $stack, 'http_errors' => false]);
    }
}
