<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Model\TopologyResponse;
use Camunda\Orchestration\CamundaAsyncClient;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Config\ConfigResolver;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * The flat facade exposes all 244 generated operations directly on the clients
 * (e.g. `$client->getTopology()`), forwarding to the underlying API groups.
 */
final class FlatFacadeTest extends TestCase
{
    /** @var list<RequestInterface> */
    private array $transactions = [];

    public function testSyncForwarderCallsUnderlyingOperation(): void
    {
        $client = $this->clientWith(new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode(['clusterSize' => 3, 'brokers' => []]),
        ));

        $topology = $client->getTopology();

        self::assertInstanceOf(TopologyResponse::class, $topology);
        self::assertSame(3, $topology->getClusterSize());
        self::assertCount(1, $this->transactions);
        self::assertSame('GET', $this->transactions[0]->getMethod());
        self::assertStringEndsWith('/topology', $this->transactions[0]->getUri()->getPath());
    }

    public function testAsyncForwarderReturnsPromise(): void
    {
        $client = $this->asyncClientWith(new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode(['clusterSize' => 5, 'brokers' => []]),
        ));

        $promise = $client->getTopology();

        self::assertInstanceOf(PromiseInterface::class, $promise);
        $topology = $promise->wait();
        self::assertInstanceOf(TopologyResponse::class, $topology);
        self::assertSame(5, $topology->getClusterSize());
    }

    private function clientWith(Response $response): CamundaClient
    {
        return CamundaClient::fromConfiguration(
            ConfigResolver::resolve(['CAMUNDA_AUTH_STRATEGY' => 'NONE']),
            $this->mockHttpClient($response),
        );
    }

    private function asyncClientWith(Response $response): CamundaAsyncClient
    {
        return CamundaAsyncClient::fromConfiguration(
            ConfigResolver::resolve(['CAMUNDA_AUTH_STRATEGY' => 'NONE']),
            $this->mockHttpClient($response),
        );
    }

    private function mockHttpClient(Response $response): GuzzleClient
    {
        $stack = HandlerStack::create(new MockHandler([$response]));
        $stack->push(function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                $this->transactions[] = $request;
                return $handler($request, $options);
            };
        });

        return new GuzzleClient(['handler' => $stack, 'http_errors' => false]);
    }
}
