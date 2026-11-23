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

        $headers = $provider->getHeaders();
        self::assertSame(['Authorization' => 'Bearer access-token'], $headers);
        self::assertSame($headers, $provider->getHeaders());
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

    public function testACopyWithANewTokenClientKeepsTheCachedTokenAndRefreshesThroughTheNewClient(): void
    {
        // Issue #187: a forked child keeps the parent's token but never its token connection.
        $token = static fn (string $t): Response => new Response(
            200,
            ['Content-Type' => 'application/json'],
            '{"access_token":"' . $t . '","expires_in":30}',
        );
        $parentHttp = new RecordingTokenClient($token('token-1'));
        $factories = new Psr17Factory();
        $parent = new OAuthClientCredentialsProvider(
            httpClient: $parentHttp,
            requestFactory: $factories,
            streamFactory: $factories,
            tokenUrl: 'https://login.example.test/oauth/token',
            clientId: 'id',
            clientSecret: 'secret',
            audience: 'zeebe-api',
        );
        self::assertSame(['Authorization' => 'Bearer token-1'], $parent->getHeaders());

        $childHttp = new RecordingTokenClient($token('token-2'));
        $child = $parent->withTokenHttpClient($childHttp);
        self::assertSame(['Authorization' => 'Bearer token-1'], $child->getHeaders());
        self::assertCount(0, $childHttp->requests);

        usleep(1_100_000);

        self::assertSame(['Authorization' => 'Bearer token-2'], $child->getHeaders());
        self::assertCount(1, $parentHttp->requests);
        self::assertCount(1, $childHttp->requests);
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
        $this->responses = array_values($responses);
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
