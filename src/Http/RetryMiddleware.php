<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Http;

use Camunda\Orchestration\Exception\RetryExhaustedException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Guzzle middleware that retries transient failures with exponential backoff and
 * full jitter. Defaults, option names and classification mirror the JS SDK.
 *
 * What is retried:
 *
 *  - HTTP 429, always.
 *  - HTTP 503 whose problem `title` is `RESOURCE_EXHAUSTED`, and HTTP 500 whose problem
 *    `title` or `detail` mentions `RESOURCE_EXHAUSTED` (broker backpressure). Other 500 and
 *    503 responses are returned to the caller after a single request.
 *  - Connection-level failures (no response received), for idempotent methods only
 *    (`GET`, `HEAD`, `PUT`, `DELETE`, `OPTIONS`). A transport failure on a `POST` may have
 *    happened after the broker accepted the command, so it is never replayed.
 *
 * What is never retried: the job-activation long poll (`POST …/jobs/activation`; the worker
 * loop owns re-polling), requests whose body cannot be rewound, and everything else.
 *
 * Delay before attempt `n + 1` is `floor(random() * min(baseDelayMs * 2^(n-1), maxDelayMs))`.
 * A usable `Retry-After` header (delta-seconds or HTTP-date) raises that to at least the
 * server's value, bounded by {@see MAX_RETRY_AFTER_MS}. The wait is passed to the handler
 * through Guzzle's `delay` request option, so async requests do not block.
 *
 * When every attempt fails the middleware throws {@see RetryExhaustedException}. With
 * `maxAttempts` of `1` it is a pass-through.
 */
final class RetryMiddleware
{
    /** Upper bound applied to a server-supplied `Retry-After`, in milliseconds. */
    public const MAX_RETRY_AFTER_MS = 60_000;

    private const IDEMPOTENT_METHODS = ['GET', 'HEAD', 'PUT', 'DELETE', 'OPTIONS'];
    private const BACKPRESSURE_MARKER = 'RESOURCE_EXHAUSTED';
    private const MAX_CLASSIFIED_BODY_BYTES = 1_048_576;

    /** @var callable(): float */
    private $random;

    /**
     * @param int $maxAttempts Total attempts including the first (1 disables retry).
     * @param int $baseDelayMs Base of the exponential backoff.
     * @param int $maxDelayMs Cap of the exponential backoff.
     * @param (callable(): float)|null $random Source of jitter in [0, 1); injectable for tests.
     */
    public function __construct(
        private readonly int $maxAttempts,
        private readonly int $baseDelayMs,
        private readonly int $maxDelayMs,
        ?callable $random = null,
    ) {
        if ($maxAttempts < 1) {
            throw new \InvalidArgumentException('maxAttempts must be at least 1.');
        }
        if ($baseDelayMs < 0 || $maxDelayMs < 0) {
            throw new \InvalidArgumentException('Retry delays must not be negative.');
        }
        $this->random = $random ?? static fn (): float => mt_rand() / (mt_getrandmax() + 1);
    }

    /**
     * @param callable(RequestInterface, array<int|string, mixed>): PromiseInterface $handler
     * @return callable(RequestInterface, array<int|string, mixed>): PromiseInterface
     */
    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler): PromiseInterface {
            if ($this->maxAttempts === 1 || self::isJobActivation($request)) {
                return $handler($request, $options);
            }

            return $this->attempt($handler, $request, $options, 1);
        };
    }

    /**
     * @param callable(RequestInterface, array<int|string, mixed>): PromiseInterface $handler
     * @param array<int|string, mixed> $options
     */
    private function attempt(callable $handler, RequestInterface $request, array $options, int $attempt): PromiseInterface
    {
        return $handler($request, $options)->then(
            function (ResponseInterface $response) use ($handler, $request, $options, $attempt) {
                [$response, $retryable] = $this->classify($response);
                if (!$retryable) {
                    return $response;
                }
                if ($attempt >= $this->maxAttempts) {
                    throw $this->exhaustedByResponse($response, $attempt);
                }
                if (!self::rewind($request)) {
                    return $response;
                }

                $options['delay'] = $this->delayMs($attempt, $response);
                return $this->attempt($handler, $request, $options, $attempt + 1);
            },
            function (mixed $reason) use ($handler, $request, $options, $attempt) {
                if (!$reason instanceof \Throwable || !self::isRetryableTransportFailure($reason, $request)) {
                    return Create::rejectionFor($reason);
                }
                if ($attempt >= $this->maxAttempts) {
                    return Create::rejectionFor(new RetryExhaustedException(
                        sprintf('[0] Request failed after %d attempts: %s', $attempt, $reason->getMessage()),
                        0,
                        $attempt,
                    ));
                }
                if (!self::rewind($request)) {
                    return Create::rejectionFor($reason);
                }

                $options['delay'] = $this->delayMs($attempt, null);
                return $this->attempt($handler, $request, $options, $attempt + 1);
            },
        );
    }

    /**
     * @return array{ResponseInterface, bool} The response (body restored) and whether to retry.
     */
    private function classify(ResponseInterface $response): array
    {
        $status = $response->getStatusCode();
        if ($status === 429) {
            return [$response, true];
        }
        if ($status !== 500 && $status !== 503) {
            return [$response, false];
        }

        $size = $response->getBody()->getSize();
        if ($size !== null && $size > self::MAX_CLASSIFIED_BODY_BYTES) {
            return [$response, false];
        }
        [$response, $contents] = self::readBody($response);
        $problem = json_decode($contents, true);
        if (!is_array($problem)) {
            return [$response, false];
        }
        $title = is_string($problem['title'] ?? null) ? $problem['title'] : '';
        $detail = is_string($problem['detail'] ?? null) ? $problem['detail'] : '';

        $backpressure = $status === 503
            ? $title === self::BACKPRESSURE_MARKER
            : str_contains($title, self::BACKPRESSURE_MARKER) || str_contains($detail, self::BACKPRESSURE_MARKER);

        return [$response, $backpressure];
    }

    /**
     * @return array{ResponseInterface, string}
     */
    private static function readBody(ResponseInterface $response): array
    {
        $body = $response->getBody();
        $contents = (string) $body;
        if ($body->isSeekable()) {
            $body->rewind();
        } else {
            $response = $response->withBody(Utils::streamFor($contents));
        }

        return [$response, $contents];
    }

    private function exhaustedByResponse(ResponseInterface $response, int $attempts): RetryExhaustedException
    {
        $status = $response->getStatusCode();
        [, $contents] = self::readBody($response);
        $problem = json_decode($contents, true);
        $title = is_array($problem) && is_string($problem['title'] ?? null) ? $problem['title'] : null;

        return new RetryExhaustedException(
            sprintf('[%d] Request failed after %d attempts%s', $status, $attempts, $title !== null ? " ($title)" : ''),
            $status,
            $attempts,
            $response->getHeaders(),
            $contents,
        );
    }

    private function delayMs(int $failedAttempt, ?ResponseInterface $response): int
    {
        $ceiling = min($this->baseDelayMs * (2 ** ($failedAttempt - 1)), $this->maxDelayMs);
        $delay = (int) floor(($this->random)() * $ceiling);

        $retryAfter = $response !== null ? self::retryAfterMs($response) : null;

        return $retryAfter !== null ? max($delay, $retryAfter) : $delay;
    }

    private static function retryAfterMs(ResponseInterface $response): ?int
    {
        $value = trim($response->getHeaderLine('Retry-After'));
        if ($value === '') {
            return null;
        }
        if (ctype_digit($value)) {
            return min((int) $value * 1000, self::MAX_RETRY_AFTER_MS);
        }
        $date = \DateTimeImmutable::createFromFormat('D, d M Y H:i:s \\G\\M\\T', $value, new \DateTimeZone('UTC'));
        if ($date === false) {
            return null;
        }
        $ms = ($date->getTimestamp() - time()) * 1000;

        return $ms > 0 ? min($ms, self::MAX_RETRY_AFTER_MS) : null;
    }

    private static function isJobActivation(RequestInterface $request): bool
    {
        return strtoupper($request->getMethod()) === 'POST'
            && str_ends_with(rtrim($request->getUri()->getPath(), '/'), '/jobs/activation');
    }

    private static function isRetryableTransportFailure(\Throwable $failure, RequestInterface $request): bool
    {
        if (!in_array(strtoupper($request->getMethod()), self::IDEMPOTENT_METHODS, true)) {
            return false;
        }
        if ($failure instanceof ConnectException) {
            return true;
        }

        return $failure instanceof RequestException
            && !$failure->hasResponse()
            && !$failure instanceof TooManyRedirectsException;
    }

    /** Rewind the request body so it can be sent again; false when that is impossible. */
    private static function rewind(RequestInterface $request): bool
    {
        $body = $request->getBody();
        if (!$body->isSeekable()) {
            return false;
        }
        $body->rewind();

        return true;
    }
}
