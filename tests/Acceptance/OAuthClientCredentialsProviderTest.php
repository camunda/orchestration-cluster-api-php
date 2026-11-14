<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Auth\OAuthClientCredentialsProvider;
use Camunda\Orchestration\Exception\AuthenticationException;
use GuzzleHttp\Psr7\Response;
use Http\Discovery\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class OAuthClientCredentialsProviderTest extends TestCase
{
    public function testCachesAnOAuthTokenUntilItsRefreshWindow(): void
    {
        $httpClient = new RecordingTokenClient(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                '{"access_token":"access-token","expires_in":30}',
            ),
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                '{"access_token":"refreshed-access-token","expires_in":3600}',
            ),
        );
        $factories = new Psr17Factory();
        $provider = new OAuthClientCredentialsProvider(
            httpClient: $httpClient,
            requestFactory: $factories,
            streamFactory: $factories,
            tokenUrl: 'https://login.example.test/oauth/token',
            clientId: 'client id',
            clientSecret: 'client secret',
            audience: 'zeebe-api',
        );

        self::assertSame(['Authorization' => 'Bearer ' . 'access-token'], $provider->getHeaders());
        self::assertSame('Bearer access-token', $provider->getHeaders()['Authorization']);
        self::assertCount(1, $httpClient->requests);

        usleep(1_100_000);

        self::assertSame(['Authorization' => 'Bearer ' . 'refreshed-access-token'], $provider->getHeaders());
        self::assertCount(2, $httpClient->requests);
        self::assertSame('POST', $httpClient->requests[0]->getMethod());
        self::assertSame(
            'grant_type=client_credentials&client_id=client%20id&client_secret=client%20secret&audience=zeebe-api',
            (string) $httpClient->requests[0]->getBody(),
        );
    }

    /**
     * A cached token that is still usable now may not survive a scheduled delay:
     * the provider must refresh when the token would be inside its refresh window
     * by the time the delayed request is sent.
     */
    public function testRefreshesACachedTokenThatWouldExpireDuringTheScheduledDelay(): void
    {
        // expires_in 75 s -> cached for 45 s (refresh leeway is 30 s).
        $httpClient = new RecordingTokenClient(
            new Response(200, ['Content-Type' => 'application/json'], '{"access_token":"short-lived","expires_in":75}'),
            new Response(200, ['Content-Type' => 'application/json'], '{"access_token":"fresh","expires_in":3600}'),
        );
        $provider = $this->provider($httpClient);

        self::assertSame('Bearer short-lived', $provider->getHeaders()['Authorization']);
        self::assertSame('Bearer short-lived', $provider->getHeadersValidFor(10.0)['Authorization']);
        self::assertCount(1, $httpClient->requests, 'A token that outlives the delay is reused.');

        self::assertSame('Bearer fresh', $provider->getHeadersValidFor(60.0)['Authorization']);
        self::assertCount(2, $httpClient->requests, 'A token that would be stale after 60 s is refreshed.');

        self::assertSame('Bearer fresh', $provider->getHeaders()['Authorization']);
        self::assertCount(2, $httpClient->requests);
    }

    /**
     * A freshly fetched token is subject to the same rule as a cached one: if its real
     * lifetime cannot cover the scheduled delay, the request would be sent with an
     * expired credential and fail with a non-retryable 401. The provider fails
     * explicitly instead, without echoing the token.
     */
    public function testRejectsAFreshlyFetchedTokenThatCannotCoverTheScheduledDelay(): void
    {
        $httpClient = new RecordingTokenClient(
            new Response(200, ['Content-Type' => 'application/json'], '{"access_token":"short-lived-secret","expires_in":30}'),
            new Response(200, ['Content-Type' => 'application/json'], '{"access_token":"long-lived","expires_in":3600}'),
        );
        $provider = $this->provider($httpClient);

        try {
            $provider->getHeadersValidFor(60.0);
            self::fail('A token that expires before the delayed request is sent must not be returned.');
        } catch (AuthenticationException $e) {
            self::assertStringContainsString('lifetime', $e->getMessage());
            self::assertStringNotContainsString('short-lived-secret', $e->getMessage());
        }

        // A token that does cover the delay, and an undelayed request, are unaffected.
        self::assertSame('Bearer long-lived', $provider->getHeadersValidFor(10.0)['Authorization']);
    }

    public function testAZeroDelayBehavesLikeGetHeaders(): void
    {
        $httpClient = new RecordingTokenClient(
            new Response(200, ['Content-Type' => 'application/json'], '{"access_token":"only","expires_in":3600}'),
        );
        $provider = $this->provider($httpClient);

        self::assertSame($provider->getHeaders(), $provider->getHeadersValidFor(0.0));
        self::assertCount(1, $httpClient->requests);
    }

    /**
     * Token expiry is measured from when the token request is sent, not from when the
     * response arrives: the authorization server issued the token (and started its
     * `expires_in` clock) before the response travelled back, so a slow token endpoint
     * must not make the token look fresher than it is.
     */
    public function testMeasuresTokenExpiryFromBeforeTheTokenRequestIsSent(): void
    {
        $clock = new ManualClock(1_000.0);
        // The endpoint takes 40 s to answer; the token lives 100 s from issuance.
        $httpClient = new RecordingTokenClient(
            new Response(200, ['Content-Type' => 'application/json'], '{"access_token":"slow","expires_in":100}'),
            new Response(200, ['Content-Type' => 'application/json'], '{"access_token":"fresh","expires_in":3600}'),
        );
        $httpClient->latencySeconds = 40.0;
        $httpClient->clock = $clock;
        $provider = $this->provider($httpClient, $clock);

        self::assertSame('Bearer slow', $provider->getHeaders()['Authorization']);
        self::assertSame(1_040.0, $clock->now, 'The response arrived 40 s after the request was sent.');

        // Soft expiry = request start (1000) + 100 - 30 s leeway = 1070.
        $clock->now = 1_069.0;
        self::assertSame('Bearer slow', $provider->getHeaders()['Authorization']);
        self::assertCount(1, $httpClient->requests, 'Still inside the refresh window boundary: reused.');

        $clock->now = 1_071.0;
        $httpClient->latencySeconds = 0.0;
        self::assertSame('Bearer fresh', $provider->getHeaders()['Authorization']);
        self::assertCount(2, $httpClient->requests, 'Measured from the response (1040 + 70) it would wrongly be reused.');
    }

    public function testHardExpiryIsMeasuredFromBeforeTheTokenRequestIsSent(): void
    {
        $clock = new ManualClock(1_000.0);
        // 40 s of latency leaves only 20 s of a 60 s token once it is in hand.
        $httpClient = new RecordingTokenClient(
            new Response(200, ['Content-Type' => 'application/json'], '{"access_token":"slow-secret","expires_in":60}'),
        );
        $httpClient->latencySeconds = 40.0;
        $httpClient->clock = $clock;
        $provider = $this->provider($httpClient, $clock);

        try {
            $provider->getHeadersValidFor(30.0);
            self::fail('A token with 20 s left must not be returned for a request sent in 30 s.');
        } catch (AuthenticationException $e) {
            self::assertStringContainsString('lifetime', $e->getMessage());
            self::assertStringNotContainsString('slow-secret', $e->getMessage());
        }
    }

    private function provider(RecordingTokenClient $httpClient, ?ManualClock $clock = null): OAuthClientCredentialsProvider
    {
        $factories = new Psr17Factory();

        return new OAuthClientCredentialsProvider(
            httpClient: $httpClient,
            requestFactory: $factories,
            streamFactory: $factories,
            tokenUrl: 'https://login.example.test/oauth/token',
            clientId: 'client id',
            clientSecret: 'client secret',
            audience: 'zeebe-api',
            clock: $clock === null ? null : static fn (): float => $clock->now,
        );
    }
}

final class ManualClock
{
    public function __construct(public float $now)
    {
    }
}

final class RecordingTokenClient implements ClientInterface
{
    /** @var list<RequestInterface> */
    public array $requests = [];

    /** Simulated time the token endpoint takes to answer; advances {@see $clock}. */
    public float $latencySeconds = 0.0;
    public ?ManualClock $clock = null;

    /** @var list<ResponseInterface> */
    private array $responses;

    public function __construct(ResponseInterface ...$responses)
    {
        $this->responses = array_values($responses);
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->requests[] = $request;
        if ($this->clock !== null) {
            $this->clock->now += $this->latencySeconds;
        }

        $response = array_shift($this->responses);
        if (!$response instanceof ResponseInterface) {
            throw new \RuntimeException('No mocked OAuth responses remain.');
        }

        return $response;
    }
}
