<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

require_once dirname(__DIR__, 2) . '/examples/advanced/internal/ExampleSupport.php';

use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Config\ConfigResolver;
use Camunda\Orchestration\Examples\Advanced\Internal\ExampleSupport;
use Camunda\Orchestration\Semantic\ProcessInstanceKey;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

final class AdvancedExampleSupportTest extends TestCase
{
    /** @var list<RequestInterface> */
    private array $requests = [];

    public function testCleanupRetriesTransientNotFoundBeforeCancellingActiveProcess(): void
    {
        $client = $this->client([
            $this->problemResponse(404, 'Process instance is not yet visible.'),
            $this->processInstanceResponse('ACTIVE'),
            new Response(204),
        ]);

        ExampleSupport::cancelIfActive($client, ProcessInstanceKey::of('2251799813686194'));

        self::assertCount(3, $this->requests);
        self::assertSame('GET', $this->requests[0]->getMethod());
        self::assertStringEndsWith('/process-instances/2251799813686194', $this->requests[0]->getUri()->getPath());
        self::assertSame('GET', $this->requests[1]->getMethod());
        self::assertSame('POST', $this->requests[2]->getMethod());
        self::assertStringEndsWith(
            '/process-instances/2251799813686194/cancellation',
            $this->requests[2]->getUri()->getPath(),
        );
    }

    public function testCleanupDoesNotCancelTerminalProcess(): void
    {
        $client = $this->client([$this->processInstanceResponse('COMPLETED')]);

        ExampleSupport::cancelIfActive($client, ProcessInstanceKey::of('2251799813686194'));

        self::assertCount(1, $this->requests);
        self::assertSame('GET', $this->requests[0]->getMethod());
    }

    /**
     * @param list<Response> $responses
     */
    private function client(array $responses): CamundaClient
    {
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(function (callable $handler): callable {
            return function (RequestInterface $request, array $options) use ($handler) {
                $this->requests[] = $request;

                return $handler($request, $options);
            };
        });

        return CamundaClient::fromConfiguration(
            ConfigResolver::resolve(
                overrides: ['CAMUNDA_AUTH_STRATEGY' => 'NONE'],
                environment: [],
            ),
            new GuzzleClient(['handler' => $stack, 'http_errors' => false]),
        );
    }

    private function problemResponse(int $status, string $detail): Response
    {
        return new Response(
            $status,
            ['Content-Type' => 'application/problem+json'],
            (string) json_encode([
                'type' => 'about:blank',
                'title' => 'Not Found',
                'status' => $status,
                'detail' => $detail,
                'instance' => '/v2/process-instances/2251799813686194',
            ], JSON_THROW_ON_ERROR),
        );
    }

    private function processInstanceResponse(string $state): Response
    {
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode(['state' => $state], JSON_THROW_ON_ERROR),
        );
    }
}
