<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance\Runtime;

use Camunda\Orchestration\Auth\AuthProvider;
use Camunda\Orchestration\Auth\OAuthClientCredentialsProvider;
use Camunda\Orchestration\Exception\AuthenticationException;
use Camunda\Orchestration\Http\AuthMiddleware;
use Camunda\Orchestration\Tests\Support\FakeClock;
use Camunda\Orchestration\Tests\Support\QueueHttpClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Discovery\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

final class AuthMiddlewareTest extends TestCase
{
    public function testStampsEveryProviderHeaderOntoTheRequestAndKeepsOtherHeaders(): void
    {
        $seen = null;
        $handler = $this->capturing($seen);
        $middleware = new AuthMiddleware($this->provider(['Authorization' => 'Bearer t', 'X-Extra' => 'e']));

        $middleware($handler)(
            (new Request('GET', 'https://cluster.test/v2/topology'))->withHeader('Accept', 'application/json'),
            [],
        );

        self::assertInstanceOf(RequestInterface::class, $seen);
        self::assertSame('Bearer t', $seen->getHeaderLine('Authorization'));
        self::assertSame('e', $seen->getHeaderLine('X-Extra'));
        self::assertSame('application/json', $seen->getHeaderLine('Accept'));
    }

    public function testReplacesAnAuthorizationHeaderAlreadyOnTheRequest(): void
    {
        $seen = null;
        $middleware = new AuthMiddleware($this->provider(['Authorization' => 'Bearer fresh']));

        $middleware($this->capturing($seen))(
            (new Request('GET', 'https://cluster.test/'))->withHeader('Authorization', 'Bearer stale'),
            [],
        );

        self::assertInstanceOf(RequestInterface::class, $seen);
        self::assertSame(['Bearer fresh'], $seen->getHeader('Authorization'), 'must replace, never append a second credential');
    }

    public function testProviderWithoutHeadersLeavesTheRequestUntouched(): void
    {
        $seen = null;
        $original = new Request('GET', 'https://cluster.test/', ['Accept' => 'application/json']);

        (new AuthMiddleware($this->provider([])))($this->capturing($seen))($original, []);

        self::assertSame($original, $seen);
    }

    public function testRequestOptionsAndHandlerResultPassThroughUnchanged(): void
    {
        $receivedOptions = null;
        $promise = new FulfilledPromise(new Response(204));
        $handler = function (RequestInterface $request, array $options) use (&$receivedOptions, $promise) {
            $receivedOptions = $options;

            return $promise;
        };

        $result = (new AuthMiddleware($this->provider(['Authorization' => 'Bearer t'])))($handler)(
            new Request('GET', 'https://cluster.test/'),
            ['timeout' => 3, 'verify' => '/etc/ca.pem'],
        );

        self::assertSame(['timeout' => 3, 'verify' => '/etc/ca.pem'], $receivedOptions);
        self::assertSame($promise, $result);
    }

    public function testProviderFailureStopsTheRequestFromBeingSentUnauthenticated(): void
    {
        $handlerCalled = false;
        $handler = function () use (&$handlerCalled) {
            $handlerCalled = true;

            return new FulfilledPromise(new Response(200));
        };
        $failing = new class () implements AuthProvider {
            public function getHeaders(): array
            {
                throw new AuthenticationException('token endpoint unreachable');
            }
        };

        try {
            (new AuthMiddleware($failing))($handler)(new Request('GET', 'https://cluster.test/'), []);
            self::fail('Expected the AuthenticationException to propagate.');
        } catch (AuthenticationException $e) {
            self::assertSame('token endpoint unreachable', $e->getMessage());
        }

        self::assertFalse($handlerCalled, 'a request must never leave without credentials when the provider fails');
    }

    public function testTokenRefreshHappensTransparentlyBetweenRequests(): void
    {
        $clock = new FakeClock();
        $tokenClient = new QueueHttpClient(
            new Response(200, [], '{"access_token":"token-1","expires_in":100}'),
            new Response(200, [], '{"access_token":"token-2","expires_in":100}'),
        );
        $factories = new Psr17Factory();
        $provider = new OAuthClientCredentialsProvider(
            httpClient: $tokenClient,
            requestFactory: $factories,
            streamFactory: $factories,
            tokenUrl: 'https://login.example.test/token',
            clientId: 'id',
            clientSecret: 'secret',
            audience: 'aud',
            clock: $clock->closure(),
        );

        $mock = new MockHandler([new Response(200), new Response(200), new Response(200)]);
        $sent = [];
        $stack = HandlerStack::create($mock);
        $stack->push(new AuthMiddleware($provider), 'auth');
        $stack->push(static function (callable $next) use (&$sent): callable {
            return static function (RequestInterface $request, array $options) use ($next, &$sent) {
                $sent[] = $request->getHeaderLine('Authorization');

                return $next($request, $options);
            };
        }, 'record');
        $guzzle = new GuzzleClient(['handler' => $stack]);

        $guzzle->request('GET', 'https://cluster.test/a');
        $clock->advance(69.0);
        $guzzle->request('GET', 'https://cluster.test/b');
        $clock->advance(1.0);
        $guzzle->request('GET', 'https://cluster.test/c');

        self::assertSame(['Bearer token-1', 'Bearer token-1', 'Bearer token-2'], $sent);
        self::assertCount(2, $tokenClient->requests);
    }

    /**
     * @param array<string, string> $headers
     */
    private function provider(array $headers): AuthProvider
    {
        return new class ($headers) implements AuthProvider {
            /** @param array<string, string> $headers */
            public function __construct(private readonly array $headers)
            {
            }

            public function getHeaders(): array
            {
                return $this->headers;
            }
        };
    }

    /**
     * @return callable(RequestInterface, array<string, mixed>): FulfilledPromise
     */
    private function capturing(?RequestInterface &$seen): callable
    {
        return static function (RequestInterface $request, array $options) use (&$seen): FulfilledPromise {
            $seen = $request;

            return new FulfilledPromise(new Response(200));
        };
    }
}
