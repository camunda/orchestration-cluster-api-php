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

    /** Upper bound on the error summary carried in an {@see AuthenticationException}. */
    private const MAX_ERROR_SUMMARY_LENGTH = 200;

    /** Stand-in for the client secret wherever it would otherwise be reported. */
    private const REDACTED = '***';

    private ?string $accessToken = null;
    private float $expiresAtEpoch = 0.0;
    /** Epoch second at which the cached token is actually no longer accepted (no leeway). */
    private float $hardExpiresAtEpoch = 0.0;
    /** @var \Closure(): float */
    private readonly \Closure $clock;

    /**
     * @param (\Closure(): float)|null $clock Returns the current time as epoch seconds with
     *   sub-second precision. Defaults to `microtime(true)`; injectable so tests can drive time
     *   deterministically.
     */
    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly string $tokenUrl,
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $audience,
        ?\Closure $clock = null,
    ) {
        $this->clock = $clock ?? static fn (): float => microtime(true);
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
        $cached = $this->accessToken;
        if ($cached !== null && ($this->clock)() + $validForSeconds < $this->expiresAtEpoch) {
            return $cached;
        }
        $token = $this->fetchToken();
        // A fresh token is subject to the same rule as a cached one: if it cannot
        // outlive the scheduled delay the request would be sent with an expired
        // credential and fail with a non-retryable 401, so fail explicitly instead.
        if ($validForSeconds > 0.0 && ($this->clock)() + $validForSeconds >= $this->hardExpiresAtEpoch) {
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

        // The server's `expires_in` clock starts when it issues the token, which is no
        // later than when the response reaches us. Measure from before the request is
        // sent so token-endpoint latency can never make the token look fresher than it is.
        $requestStartedAt = ($this->clock)();

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
        $this->expiresAtEpoch = $requestStartedAt + max(1, $expiresIn - self::EXPIRY_LEEWAY_SECONDS);
        $this->hardExpiresAtEpoch = $requestStartedAt + max(1, $expiresIn);

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
     * literal, percent-encoded (with the hex digits of each escape in either case) and
     * JSON-escaped forms are all scrubbed. This is a second line of defence over an
     * open-ended space of encodings; the first is that only the two well-known OAuth
     * fields are quoted at all.
     */
    private function redactSecret(string $text): string
    {
        if ($this->clientSecret === '') {
            return $text;
        }

        return (string) preg_replace($this->secretPatterns(), self::REDACTED, $text);
    }

    /**
     * One pattern per representation, longest first so a form that contains another is
     * replaced whole.
     *
     * @return list<non-empty-string>
     */
    private function secretPatterns(): array
    {
        $literals = [$this->clientSecret];

        $json = json_encode($this->clientSecret);
        if (is_string($json)) {
            // Strip the surrounding quotes: `"a\/b"` -> `a\/b`.
            $literals[] = substr($json, 1, -1);
        }

        $patterns = array_map(static fn (string $l): string => preg_quote($l, '/'), $literals);
        foreach ([rawurlencode($this->clientSecret), urlencode($this->clientSecret)] as $encoded) {
            $patterns[] = self::patternForEncoded($encoded);
        }

        $patterns = array_values(array_unique($patterns));
        usort($patterns, static fn (string $a, string $b): int => \strlen($b) <=> \strlen($a));

        /** @var list<non-empty-string> */
        return array_map(static fn (string $p): string => '/' . $p . '/', $patterns);
    }

    /**
     * Match a percent-encoded form with the hex digits of every `%XX` escape in either
     * case — a provider may emit `%2f`, `%2F` or a mixture — and everything else
     * literally.
     */
    private static function patternForEncoded(string $encoded): string
    {
        return (string) preg_replace_callback(
            '/%([0-9A-Fa-f])([0-9A-Fa-f])/',
            static fn (array $match): string => '%' . self::eitherCase($match[1]) . self::eitherCase($match[2]),
            preg_quote($encoded, '/'),
        );
    }

    private static function eitherCase(string $hexDigit): string
    {
        $upper = strtoupper($hexDigit);
        $lower = strtolower($hexDigit);

        return $upper === $lower ? $upper : '[' . $upper . $lower . ']';
    }
}
