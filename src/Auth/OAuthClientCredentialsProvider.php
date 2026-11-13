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

    /** Upper bound on the error summary carried in an {@see AuthenticationException}. */
    private const MAX_ERROR_SUMMARY_LENGTH = 200;

    /** Stand-in for the client secret wherever it would otherwise be reported. */
    private const REDACTED = '***';

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

    /** @return array{Authorization: string} */
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
            // A transport error message can quote the request it failed to send, and
            // that request carries the client secret (issue #110). The cause is not
            // attached: a logger or `(string) $exception` renders the whole chain, so
            // an unsanitised `previous` would leak the secret the message no longer
            // carries. Its class and redacted message are reported here instead.
            throw new AuthenticationException(sprintf(
                'Failed to reach the OAuth token endpoint (%s): %s',
                $e::class,
                $this->redactSecret($e->getMessage()),
            ));
        }

        $status = $response->getStatusCode();
        $raw = (string) $response->getBody();
        if ($status < 200 || $status >= 300) {
            throw new AuthenticationException(
                'OAuth token request failed with HTTP ' . $status . $this->errorSummary($raw)
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

    /**
     * Summarise a token-endpoint error body instead of echoing it.
     *
     * The request sent to the token endpoint is a form containing `client_secret`, and
     * an identity provider is free to quote the request it rejected. Only the
     * well-known OAuth 2.0 `error` and `error_description` fields of a JSON body are
     * reported, bounded in length and scrubbed of the configured secret (issue #110).
     */
    private function errorSummary(string $raw): string
    {
        /** @var mixed $payload */
        $payload = json_decode($raw, true);
        if (!is_array($payload)) {
            return '.';
        }

        $parts = [];
        foreach (['error', 'error_description'] as $field) {
            /** @var mixed $value */
            $value = $payload[$field] ?? null;
            if (is_string($value) && $value !== '') {
                $parts[] = $value;
            }
        }
        if ($parts === []) {
            return '.';
        }

        $summary = $this->redactSecret(implode(': ', $parts));
        if (mb_strlen($summary) > self::MAX_ERROR_SUMMARY_LENGTH) {
            $summary = mb_substr($summary, 0, self::MAX_ERROR_SUMMARY_LENGTH) . '...';
        }

        return ' (' . $summary . ').';
    }

    /**
     * Replace the configured client secret wherever it appears in text destined for an
     * exception message — a backstop for every place the secret could be reflected back
     * to us, not only the structured fields we choose to report.
     *
     * The secret may come back in a representation other than the one we sent, so the
     * literal, percent-encoded (in either hex case) and JSON-escaped forms are all
     * scrubbed. This is a second line of defence: the first is that only the two
     * well-known OAuth fields are quoted at all.
     */
    private function redactSecret(string $text): string
    {
        if ($this->clientSecret === '') {
            return $text;
        }

        return str_replace($this->secretRepresentations(), self::REDACTED, $text);
    }

    /**
     * @return list<string>
     */
    private function secretRepresentations(): array
    {
        $forms = [$this->clientSecret];
        foreach ([rawurlencode($this->clientSecret), urlencode($this->clientSecret)] as $encoded) {
            $forms[] = $encoded;
            // A provider is free to emit `%2f` where PHP emits `%2F`.
            $forms[] = (string) preg_replace_callback(
                '/%[0-9A-Fa-f]{2}/',
                static fn (array $match): string => strtolower($match[0]),
                $encoded,
            );
        }

        $json = json_encode($this->clientSecret);
        if (is_string($json)) {
            // Strip the surrounding quotes: `"a\/b"` -> `a\/b`.
            $forms[] = substr($json, 1, -1);
        }

        // Longest first, so a form that contains another is replaced whole.
        $forms = array_values(array_unique($forms));
        usort($forms, static fn (string $a, string $b): int => \strlen($b) <=> \strlen($a));

        return $forms;
    }
}
