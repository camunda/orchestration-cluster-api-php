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
final class OAuthClientCredentialsProvider implements DelayAwareAuthProvider
{
    /** Refresh this many seconds before the token actually expires. */
    private const EXPIRY_LEEWAY_SECONDS = 30;

    private ?string $accessToken = null;
    private float $expiresAtEpoch = 0.0;
    /** Epoch second at which the cached token is actually no longer accepted (no leeway). */
    private float $hardExpiresAtEpoch = 0.0;

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

    /** @return array{Authorization: string} */
    public function getHeaders(): array
    {
        return $this->getHeadersValidFor(0.0);
    }

    /**
     * A cached token is reused only if it will still be outside its refresh window
     * when a request delayed by `$seconds` is sent; otherwise a fresh one is fetched.
     *
     * @return array{Authorization: string}
     */
    public function getHeadersValidFor(float $seconds): array
    {
        return ['Authorization' => 'Bearer ' . $this->token(max(0.0, $seconds))];
    }

    private function token(float $validForSeconds): string
    {
        if ($this->accessToken !== null && microtime(true) + $validForSeconds < $this->expiresAtEpoch) {
            return $this->accessToken;
        }
        $token = $this->fetchToken();
        // A fresh token is subject to the same rule as a cached one: if it cannot
        // outlive the scheduled delay the request would be sent with an expired
        // credential and fail with a non-retryable 401, so fail explicitly instead.
        if ($validForSeconds > 0.0 && microtime(true) + $validForSeconds >= $this->hardExpiresAtEpoch) {
            throw new AuthenticationException(
                'The OAuth token lifetime is shorter than the scheduled retry delay '
                . '(' . (int) ceil($validForSeconds) . ' s), so the delayed request could not be authenticated.'
            );
        }

        return $token;
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
        $now = microtime(true);
        $this->expiresAtEpoch = $now + max(1, $expiresIn - self::EXPIRY_LEEWAY_SECONDS);
        $this->hardExpiresAtEpoch = $now + max(1, $expiresIn);

        return $this->accessToken;
    }
}
