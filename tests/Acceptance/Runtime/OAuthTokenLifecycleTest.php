<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance\Runtime;

use Camunda\Orchestration\Auth\OAuthClientCredentialsProvider;
use Camunda\Orchestration\Exception\AuthenticationException;
use Camunda\Orchestration\Tests\Support\FakeClock;
use Camunda\Orchestration\Tests\Support\QueueHttpClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Discovery\Psr17Factory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * Token lifecycle of {@see OAuthClientCredentialsProvider}: caching, the refresh boundary (driven by
 * an injected clock, no sleeping), token-endpoint failures, re-entrant refresh and secret hygiene.
 */
final class OAuthTokenLifecycleTest extends TestCase
{
    private const SECRET = 's3cr3t-client-value';
    private const START = 1_000_000.0;

    private FakeClock $clock;

    protected function setUp(): void
    {
        $this->clock = new FakeClock(self::START);
    }

    public function testTokenIsServedFromCacheUntilTheLeewayBoundaryThenRefreshedExactlyOnce(): void
    {
        // expires_in=100 with a 30s refresh leeway => the cached token is usable for 70s.
        $http = new QueueHttpClient(
            $this->tokenResponse('token-1', 100),
            $this->tokenResponse('token-2', 100),
        );
        $provider = $this->provider($http);

        self::assertSame('Bearer token-1', $this->bearer($provider));

        $this->clock->set(self::START + 69.999);
        self::assertSame('Bearer token-1', $this->bearer($provider));
        self::assertCount(1, $http->requests, 'one tick before the boundary the cached token must be reused');

        $this->clock->set(self::START + 70.0);
        self::assertSame('Bearer token-2', $this->bearer($provider));
        self::assertCount(2, $http->requests, 'at the boundary itself the token is considered expired');

        $this->clock->set(self::START + 70.0 + 69.999);
        self::assertSame('Bearer token-2', $this->bearer($provider));
        self::assertCount(2, $http->requests);
    }

    #[DataProvider('lifetimesShorterThanTheLeeway')]
    public function testTokensWithLifetimeWithinTheLeewayAreCachedForOneSecond(int|string $expiresIn): void
    {
        $http = new QueueHttpClient(
            $this->tokenResponse('short-1', $expiresIn),
            $this->tokenResponse('short-2', $expiresIn),
        );
        $provider = $this->provider($http);

        self::assertSame('Bearer short-1', $this->bearer($provider));

        $this->clock->set(self::START + 0.5);
        self::assertSame('Bearer short-1', $this->bearer($provider));
        self::assertCount(1, $http->requests);

        $this->clock->set(self::START + 1.0);
        self::assertSame('Bearer short-2', $this->bearer($provider));
        self::assertCount(2, $http->requests);
    }

    /** @return iterable<string, array{int|string}> */
    public static function lifetimesShorterThanTheLeeway(): iterable
    {
        yield 'exactly the leeway' => [30];
        yield 'shorter than the leeway' => [10];
        yield 'zero' => [0];
        yield 'negative' => [-5];
        yield 'non-numeric string' => ['soon'];
    }

    public function testMissingExpiresInDefaultsToFiveMinutesMinusTheLeeway(): void
    {
        $http = new QueueHttpClient(
            new Response(200, [], '{"access_token":"no-expiry"}'),
            $this->tokenResponse('after-default', 300),
        );
        $provider = $this->provider($http);

        $provider->getHeaders();
        $this->clock->set(self::START + 269.999);
        $provider->getHeaders();
        self::assertCount(1, $http->requests);

        $this->clock->set(self::START + 270.0);
        self::assertSame('Bearer after-default', $this->bearer($provider));
        self::assertCount(2, $http->requests);
    }

    public function testNumericStringExpiresInIsHonoured(): void
    {
        $http = new QueueHttpClient(
            $this->tokenResponse('string-lifetime', '120'),
            $this->tokenResponse('refreshed', 120),
        );
        $provider = $this->provider($http);

        $provider->getHeaders();
        $this->clock->set(self::START + 89.999);
        $provider->getHeaders();
        self::assertCount(1, $http->requests);

        $this->clock->set(self::START + 90.0);
        $provider->getHeaders();
        self::assertCount(2, $http->requests);
    }

    public function testBurstOfCallsWithinTheValidityWindowIssuesASingleTokenRequest(): void
    {
        $http = new QueueHttpClient($this->tokenResponse('burst', 3600));
        $provider = $this->provider($http);

        for ($i = 0; $i < 50; ++$i) {
            self::assertSame('Bearer burst', $this->bearer($provider));
        }

        self::assertCount(1, $http->requests);
    }

    public function testFailedRefreshDoesNotServeTheExpiredTokenAndTheNextCallRetries(): void
    {
        $http = new QueueHttpClient(
            $this->tokenResponse('old-token', 100),
            new Response(503, [], 'token endpoint unavailable'),
            $this->tokenResponse('new-token', 100),
        );
        $provider = $this->provider($http);
        self::assertSame('Bearer old-token', $this->bearer($provider));

        $this->clock->set(self::START + 70.0);
        try {
            $provider->getHeaders();
            self::fail('A failed refresh must surface as an AuthenticationException, not serve the expired token.');
        } catch (AuthenticationException $e) {
            self::assertStringContainsString('HTTP 503', $e->getMessage());
            self::assertStringNotContainsString('old-token', (string) $e);
        }

        // The failure must not be cached: the very next call asks the endpoint again.
        self::assertSame('Bearer new-token', $this->bearer($provider));
        self::assertCount(3, $http->requests);
    }

    /**
     * @param array{0: int, 1: string} $response
     */
    #[DataProvider('unusableTokenResponses')]
    public function testUnusableTokenEndpointResponsesRaiseAuthenticationException(array $response, string $expectedMessage): void
    {
        $http = new QueueHttpClient(
            new Response($response[0], [], $response[1]),
            $this->tokenResponse('recovered', 3600),
        );
        $provider = $this->provider($http);

        try {
            $provider->getHeaders();
            self::fail('Expected an AuthenticationException.');
        } catch (AuthenticationException $e) {
            self::assertStringContainsString($expectedMessage, $e->getMessage());
            self::assertStringNotContainsString(self::SECRET, (string) $e);
        }

        self::assertCount(1, $http->requests, 'a failing token request must not be retried in a hidden loop');
        self::assertSame('Bearer recovered', $this->bearer($provider), 'nothing is cached after a failure');
    }

    /** @return iterable<string, array{array{int, string}, string}> */
    public static function unusableTokenResponses(): iterable
    {
        yield '401 invalid_client' => [[401, '{"error":"invalid_client"}'], 'HTTP 401'];
        yield '403' => [[403, '{"error":"forbidden"}'], 'HTTP 403'];
        yield '429 rate limited' => [[429, 'slow down'], 'HTTP 429'];
        yield '500' => [[500, 'boom'], 'HTTP 500'];
        yield '503' => [[503, ''], 'HTTP 503'];
        yield '3xx is not a token' => [[302, ''], 'HTTP 302'];
        yield '200 with a non-JSON body' => [[200, '<html>login</html>'], 'did not contain an access_token'];
        yield '200 with an empty body' => [[200, ''], 'did not contain an access_token'];
        yield '200 with JSON null' => [[200, 'null'], 'did not contain an access_token'];
        yield '200 with an empty object' => [[200, '{}'], 'did not contain an access_token'];
        yield '200 with a numeric token' => [[200, '{"access_token":12345}'], 'did not contain an access_token'];
        yield '200 with a null token' => [[200, '{"access_token":null}'], 'did not contain an access_token'];
    }

    public function testTransportFailureIsWrappedAndKeepsThePreviousException(): void
    {
        $transport = new class ('connection refused') extends \RuntimeException implements ClientExceptionInterface {
        };
        $provider = $this->provider(new QueueHttpClient($transport));

        try {
            $provider->getHeaders();
            self::fail('Expected an AuthenticationException.');
        } catch (AuthenticationException $e) {
            self::assertSame('Failed to reach the OAuth token endpoint: connection refused', $e->getMessage());
            self::assertSame($transport, $e->getPrevious());
        }
    }

    public function testExceptionsThatAreNotPsr18ClientExceptionsAreNotMasked(): void
    {
        $bug = new \LogicException('programming error in the HTTP client');
        $provider = $this->provider(new QueueHttpClient($bug));

        try {
            $provider->getHeaders();
            self::fail('Expected the LogicException to propagate.');
        } catch (\LogicException $e) {
            self::assertSame($bug, $e);
        }
    }

    public function testTokenRequestShapeAndFormEncoding(): void
    {
        $http = new QueueHttpClient($this->tokenResponse('shape', 3600));
        $factories = new Psr17Factory();
        $provider = new OAuthClientCredentialsProvider(
            httpClient: $http,
            requestFactory: $factories,
            streamFactory: $factories,
            tokenUrl: 'https://login.example.test/realms/camunda/token',
            clientId: 'id with space',
            clientSecret: 's&c=r+t/ü',
            audience: 'zeebe-api',
            clock: $this->clock->closure(),
        );

        $provider->getHeaders();

        $request = $http->requests[0];
        self::assertSame('POST', $request->getMethod());
        self::assertSame('https://login.example.test/realms/camunda/token', (string) $request->getUri());
        self::assertSame('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        self::assertSame('application/json', $request->getHeaderLine('Accept'));
        parse_str((string) $request->getBody(), $form);
        self::assertSame([
            'grant_type' => 'client_credentials',
            'client_id' => 'id with space',
            'client_secret' => 's&c=r+t/ü',
            'audience' => 'zeebe-api',
        ], $form);
    }

    /**
     * The client secret is sent to the token endpoint but must never be echoed into an exception
     * message, previous exception, or stack trace, whichever way the exchange fails (and whichever
     * PSR-18 client is used, including a real Guzzle client, the discovered default).
     *
     * @param \Closure(self): OAuthClientCredentialsProvider $makeProvider
     */
    #[DataProvider('failingExchanges')]
    public function testClientSecretNeverAppearsInExceptions(\Closure $makeProvider): void
    {
        $provider = $makeProvider($this);

        try {
            $provider->getHeaders();
            self::fail('Expected an AuthenticationException.');
        } catch (AuthenticationException $e) {
            $dump = '';
            for ($cursor = $e; $cursor !== null; $cursor = $cursor->getPrevious()) {
                $dump .= $cursor->getMessage() . "\n" . $cursor->getTraceAsString() . "\n" . $cursor . "\n";
            }
            self::assertStringNotContainsString(self::SECRET, $dump);
            self::assertStringNotContainsString(rawurlencode(self::SECRET), $dump);
        }
    }

    /** @return iterable<string, array{\Closure(self): OAuthClientCredentialsProvider}> */
    public static function failingExchanges(): iterable
    {
        yield 'transport error' => [static fn (self $t) => $t->provider(new QueueHttpClient(
            new class ('network down') extends \RuntimeException implements ClientExceptionInterface {
            },
        ))];
        yield '401 from a client that does not throw' => [static fn (self $t) => $t->provider(new QueueHttpClient(
            new Response(401, [], '{"error":"invalid_client"}'),
        ))];
        yield 'malformed body' => [static fn (self $t) => $t->provider(new QueueHttpClient(
            new Response(200, [], 'not json'),
        ))];
        yield '401 through a real Guzzle client' => [static fn (self $t) => $t->provider(
            new GuzzleClient(['handler' => HandlerStack::create(new MockHandler([
                new Response(401, [], '{"error":"invalid_client"}'),
            ]))]),
        )];
        yield 'connect error through a real Guzzle client' => [static fn (self $t) => $t->provider(
            new GuzzleClient(['handler' => HandlerStack::create(new MockHandler([
                new ConnectException('Connection refused', new Request('POST', 'https://login.example.test/token')),
            ]))]),
        )];
    }

    public function testReentrantRefreshFromInterleavedFibersStaysConsistent(): void
    {
        // Two fibers (e.g. async callers) hit an empty cache while the first token request is in
        // flight. There is no single-flight lock, so both may fetch; the provider must stay
        // consistent: both callers get a valid bearer, and the cache serves later calls.
        $http = new QueueHttpClient(
            static function (): Response {
                \Fiber::suspend('first request in flight');

                return new Response(200, [], '{"access_token":"token-a","expires_in":3600}');
            },
            new Response(200, [], '{"access_token":"token-b","expires_in":3600}'),
        );
        $provider = $this->provider($http);

        $first = new \Fiber(static fn (): array => $provider->getHeaders());
        $second = new \Fiber(static fn (): array => $provider->getHeaders());

        self::assertSame('first request in flight', $first->start());
        $second->start();
        $first->resume();

        self::assertTrue($first->isTerminated());
        self::assertTrue($second->isTerminated());
        foreach ([$first->getReturn(), $second->getReturn()] as $header) {
            self::assertIsArray($header);
            self::assertContains($header['Authorization'] ?? null, ['Bearer token-a', 'Bearer token-b']);
        }

        $requestsSoFar = count($http->requests);
        self::assertLessThanOrEqual(2, $requestsSoFar);
        self::assertContains($this->bearer($provider), ['Bearer token-a', 'Bearer token-b']);
        self::assertCount($requestsSoFar, $http->requests, 'after the race the cache must serve further calls');
    }

    public function testDefaultClockIsTheWallClock(): void
    {
        $http = new QueueHttpClient($this->tokenResponse('wall-clock', 3600));
        $factories = new Psr17Factory();
        $provider = new OAuthClientCredentialsProvider(
            httpClient: $http,
            requestFactory: $factories,
            streamFactory: $factories,
            tokenUrl: 'https://login.example.test/token',
            clientId: 'id',
            clientSecret: self::SECRET,
            audience: 'aud',
        );

        $provider->getHeaders();
        $provider->getHeaders();

        self::assertCount(1, $http->requests);
    }

    /**
     * @internal public only so data-provider closures can call it
     */
    public function provider(\Psr\Http\Client\ClientInterface $http): OAuthClientCredentialsProvider
    {
        $factories = new Psr17Factory();

        return new OAuthClientCredentialsProvider(
            httpClient: $http,
            requestFactory: $factories,
            streamFactory: $factories,
            tokenUrl: 'https://login.example.test/oauth/token',
            clientId: 'client-id',
            clientSecret: self::SECRET,
            audience: 'zeebe-api',
            clock: $this->clock->closure(),
        );
    }

    /**
     * The Authorization header value. Each call may hit the (fake) token endpoint, so PHPStan must
     * not assume repeated calls return the same value.
     *
     * @phpstan-impure
     */
    private function bearer(OAuthClientCredentialsProvider $provider): string
    {
        return $provider->getHeaders()['Authorization'];
    }

    private function tokenResponse(string $token, int|string $expiresIn): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], (string) json_encode([
            'access_token' => $token,
            'expires_in' => $expiresIn,
        ]));
    }
}
