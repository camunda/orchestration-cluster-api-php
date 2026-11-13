<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Support;

use Camunda\Orchestration\CamundaAsyncClient;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Config\ConfigResolver;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\RequestInterface;

/**
 * Builds Guzzle clients (and SDK clients on top of them) that replay queued responses through a
 * {@see MockHandler} and record every outgoing request, so tests can assert on the wire format.
 */
final class RecordingGuzzle
{
    /** @var list<RequestInterface> */
    public array $requests = [];

    /**
     * @param list<\Psr\Http\Message\ResponseInterface|\Throwable> $queue
     */
    public function guzzle(array $queue): GuzzleClient
    {
        $stack = HandlerStack::create(new MockHandler($queue));
        $stack->push(function (callable $handler): callable {
            return function (RequestInterface $request, array $options) use ($handler) {
                $this->requests[] = $request;

                return $handler($request, $options);
            };
        });

        return new GuzzleClient(['handler' => $stack, 'http_errors' => false]);
    }

    /**
     * @param list<\Psr\Http\Message\ResponseInterface|\Throwable> $queue
     * @param array<string, string> $overrides
     */
    public function client(array $queue, array $overrides = []): CamundaClient
    {
        return CamundaClient::fromConfiguration(
            ConfigResolver::resolve(
                overrides: ['CAMUNDA_AUTH_STRATEGY' => 'NONE'] + $overrides,
                environment: [],
            ),
            $this->guzzle($queue),
        );
    }

    /**
     * @param list<\Psr\Http\Message\ResponseInterface|\Throwable> $queue
     */
    public function asyncClient(array $queue): CamundaAsyncClient
    {
        return CamundaAsyncClient::fromConfiguration(
            ConfigResolver::resolve(
                overrides: ['CAMUNDA_AUTH_STRATEGY' => 'NONE'],
                environment: [],
            ),
            $this->guzzle($queue),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonBody(int $index): array
    {
        /** @var array<string, mixed> $body */
        $body = json_decode((string) $this->requests[$index]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        return $body;
    }
}
