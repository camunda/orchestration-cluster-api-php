<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Auth\OAuthClientCredentialsProvider;
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
        $now = 1_000.0;
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
            now: static function () use (&$now): float {
                return $now;
            },
        );

        self::assertSame(['Authorization' => 'Bearer ' . 'access-token'], $provider->getHeaders());
        self::assertSame(['Authorization' => 'Bearer ' . 'access-token'], $provider->getHeaders());
        self::assertCount(1, $httpClient->requests);

        $now += 1.1;

        self::assertSame(['Authorization' => 'Bearer ' . 'refreshed-access-token'], $provider->getHeaders());
        self::assertCount(2, $httpClient->requests);
        self::assertSame('POST', $httpClient->requests[0]->getMethod());
        self::assertSame(
            'grant_type=client_credentials&client_id=client%20id&client_secret=client%20secret&audience=zeebe-api',
            (string) $httpClient->requests[0]->getBody(),
        );
    }
}

final class RecordingTokenClient implements ClientInterface
{
    /** @var list<RequestInterface> */
    public array $requests = [];

    /** @var list<ResponseInterface> */
    private array $responses;

    public function __construct(ResponseInterface ...$responses)
    {
        $this->responses = $responses;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->requests[] = $request;

        $response = array_shift($this->responses);
        if (!$response instanceof ResponseInterface) {
            throw new \RuntimeException('No mocked OAuth responses remain.');
        }

        return $response;
    }
}
