<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Auth;

use Camunda\Orchestration\Exception\AuthenticationException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * OAuth 2.0 client-credentials authentication.
 *
 * Acquires a bearer token from the configured token endpoint and caches it in memory
 * until shortly before it expires, transparently refreshing on demand.
 */
final class OAuthClientCredentialsProvider implements AuthProvider
{
    /** Refresh this many seconds before the token actually expires. */
    private const EXPIRY_LEEWAY_SECONDS = 30;

    private ?string $accessToken = null;
    private float $expiresAtEpoch = 0.0;

    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly string $tokenUrl,
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $audience,
    ) {
    }

    public function getHeaders(): array
    {
        return ['Authorization' => 'Bearer ' . $this->token()];
    }

    private function token(): string
    {
        if ($this->accessToken !== null && microtime(true) < $this->expiresAtEpoch) {
            return $this->accessToken;
        }
        return $this->fetchToken();
    }

    private function fetchToken(): string
    {
        $body = http_build_query([
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'audience' => $this->audience,
        ], '', '&', PHP_QUERY_RFC3986);

        $request = $this->requestFactory->createRequest('POST', $this->tokenUrl)
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withHeader('Accept', 'application/json')
            ->withBody($this->streamFactory->createStream($body));

        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (\Psr\Http\Client\ClientExceptionInterface $e) {
            throw new AuthenticationException(
                'Failed to reach the OAuth token endpoint: ' . $e->getMessage(),
                0,
                $e
            );
        }

        $status = $response->getStatusCode();
        $raw = (string) $response->getBody();
        if ($status < 200 || $status >= 300) {
            throw new AuthenticationException(
                "OAuth token request failed with HTTP $status: $raw"
            );
        }

        /** @var array{access_token?: string, expires_in?: int|string}|null $payload */
        $payload = json_decode($raw, true);
        if (!is_array($payload) || !isset($payload['access_token']) || !is_string($payload['access_token'])) {
            throw new AuthenticationException('OAuth token response did not contain an access_token.');
        }

        $expiresIn = isset($payload['expires_in']) ? (int) $payload['expires_in'] : 300;
        $this->accessToken = $payload['access_token'];
        $this->expiresAtEpoch = microtime(true) + max(1, $expiresIn - self::EXPIRY_LEEWAY_SECONDS);

        return $this->accessToken;
    }
}
