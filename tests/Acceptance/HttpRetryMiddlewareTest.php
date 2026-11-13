<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Model\ProblemDetail;
use Camunda\Orchestration\Exception\RetryExhaustedException;
use Camunda\Orchestration\Http\RetryMiddleware;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\PumpStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Behaviour of the transient-failure retry middleware (issue #112).
 *
 * Defaults, classification and backoff mirror the JS SDK: 429 is always retried;
 * 503 and 500 only when the problem document names `RESOURCE_EXHAUSTED`
 * (broker backpressure); everything else is surfaced after a single request.
 */
final class HttpRetryMiddlewareTest extends TestCase
{
    /** @var list<array{request: RequestInterface, options: array<int|string, mixed>, body: string}> */
    private array $calls = [];

    public function testRetriesA429AndReturnsTheEventualSuccess(): void
    {
        $response = $this->send(
            [self::problem(429, 'RESOURCE_EXHAUSTED'), new Response(200, [], '{"ok":true}')],
            new Request('GET', 'http://cluster/v2/topology'),
        );

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('{"ok":true}', (string) $response->getBody());
        self::assertCount(2, $this->calls);
    }

    #[DataProvider('backpressureResponses')]
    public function testRetriesBackpressureSignals(ResponseInterface $first): void
    {
        $response = $this->send([$first, new Response(200)], new Request('GET', 'http://cluster/v2/topology'));

        self::assertSame(200, $response->getStatusCode());
        self::assertCount(2, $this->calls);
    }

    /**
     * @return iterable<string, array{ResponseInterface}>
     */
    public static function backpressureResponses(): iterable
    {
        yield '429 without a body' => [new Response(429)];
        yield '429 with a problem body' => [self::problem(429, 'RESOURCE_EXHAUSTED')];
        yield '503 titled RESOURCE_EXHAUSTED' => [self::problem(503, 'RESOURCE_EXHAUSTED')];
        yield '500 titled RESOURCE_EXHAUSTED' => [self::problem(500, 'RESOURCE_EXHAUSTED')];
        yield '500 with RESOURCE_EXHAUSTED in the detail' => [
            self::problem(500, 'INTERNAL', 'Command rejected: RESOURCE_EXHAUSTED partition 1'),
        ];
    }

    /**
     * Everything that is not a backpressure signal is a single request: the
     * caller sees the response exactly as before the retry feature existed.
     */
    #[DataProvider('nonRetryableResponses')]
    public function testDoesNotRetryOtherFailures(ResponseInterface $first): void
    {
        $response = $this->send([$first, new Response(200)], new Request('GET', 'http://cluster/v2/topology'));

        self::assertSame($first->getStatusCode(), $response->getStatusCode());
        self::assertCount(1, $this->calls);
    }

    /**
     * @return iterable<string, array{ResponseInterface}>
     */
    public static function nonRetryableResponses(): iterable
    {
        yield '200' => [new Response(200)];
        yield '400' => [self::problem(400, 'INVALID_ARGUMENT')];
        yield '401' => [self::problem(401, 'UNAUTHORIZED')];
        yield '403' => [self::problem(403, 'FORBIDDEN')];
        yield '404' => [self::problem(404, 'NOT_FOUND')];
        yield '409' => [self::problem(409, 'ALREADY_EXISTS')];
        yield '500 without backpressure' => [self::problem(500, 'INTERNAL')];
        yield '500 with a non-json body' => [new Response(500, [], '<html>boom</html>')];
        yield '503 without backpressure' => [self::problem(503, 'UNAVAILABLE')];
        yield '503 with no body' => [new Response(503)];
    }

    public function testErrorBodyOfAReturnedResponseIsStillReadableAfterClassification(): void
    {
        $first = self::problem(503, 'UNAVAILABLE');

        $response = $this->send([$first], new Request('GET', 'http://cluster/v2/topology'));

        self::assertStringContainsString('UNAVAILABLE', (string) $response->getBody());
    }

    public function testExhaustionThrowsWithTheFinalStatusAndAttemptCount(): void
    {
        $responses = [
            self::problem(429, 'RESOURCE_EXHAUSTED', 'first'),
            self::problem(429, 'RESOURCE_EXHAUSTED', 'second'),
            new Response(429, ['Retry-After' => '0', 'X-Trace' => 'last'], '{"title":"RESOURCE_EXHAUSTED","status":429,"detail":"third"}'),
        ];

        try {
            $this->send($responses, new Request('POST', 'http://cluster/v2/jobs/1/completion', [], '{}'));
            self::fail('Expected RetryExhaustedException');
        } catch (RetryExhaustedException $e) {
            self::assertSame(429, $e->getCode());
            self::assertSame(3, $e->getAttempts());
            self::assertStringContainsString('3 attempts', $e->getMessage());
            self::assertStringContainsString('[429]', $e->getMessage());
            self::assertSame(['last'], ($e->getResponseHeaders() ?? [])['X-Trace'] ?? null);
            $body = $e->getResponseBody();
            self::assertIsString($body);
            self::assertStringContainsString('third', $body);
        }

        self::assertCount(3, $this->calls);
    }

    /**
     * A streamed error body of unknown size must not be buffered whole to classify
     * it: the read stops just past the classification limit, and the caller still
     * receives the complete body (consumed prefix plus the unread remainder).
     */
    public function testUnknownSizeErrorBodyIsReadOnlyUpToTheClassificationLimit(): void
    {
        $limit = 1_048_576;
        $total = 3 * $limit;
        $produced = 0;
        $source = static function (int $length) use (&$produced, $total): string|false {
            if ($produced >= $total) {
                return false;
            }
            $chunk = str_repeat('x', min($length, $total - $produced));
            $produced += strlen($chunk);

            return $chunk;
        };
        $stream = new PumpStream($source);
        self::assertNull($stream->getSize());

        $response = $this->send(
            [new Response(500, ['Content-Type' => 'application/problem+json'], $stream), new Response(200)],
            new Request('GET', 'http://cluster/v2/topology'),
        );

        self::assertSame(500, $response->getStatusCode());
        self::assertCount(1, $this->calls);
        self::assertLessThanOrEqual($limit + 1, $produced, 'Classification must not drain the whole stream.');

        $body = (string) $response->getBody();
        self::assertSame($total, strlen($body), 'The oversized body must reach the caller intact.');
        self::assertSame(str_repeat('x', $total), $body);
    }

    /**
     * A non-seekable stream may return an empty read before it is at EOF (for example a
     * socket that has no data yet). That must never truncate the body the caller gets
     * back: the response is returned unclassified, with every byte still readable.
     */
    public function testAnEmptyReadBeforeEofDoesNotTruncateTheErrorBody(): void
    {
        $stream = new EmptyReadBeforeEofStream(['{"title":"Other","detail":"first half, ', '', 'second half"}']);
        self::assertNull($stream->getSize());

        $response = $this->send(
            [new Response(500, ['Content-Type' => 'application/problem+json'], $stream), new Response(200)],
            new Request('GET', 'http://cluster/v2/topology'),
        );

        self::assertSame(500, $response->getStatusCode());
        self::assertCount(1, $this->calls);

        $body = $response->getBody();
        $received = '';
        while (!$body->eof()) {
            $received .= $body->read(8192);
        }
        self::assertSame('{"title":"Other","detail":"first half, second half"}', $received);
    }

    public function testUnknownSizeBackpressureBodyIsStillClassifiedAndRetried(): void
    {
        $json = (string) json_encode(['title' => 'RESOURCE_EXHAUSTED', 'status' => 503, 'detail' => 'busy']);
        $sent = false;
        $stream = new PumpStream(static function (int $length) use (&$sent, $json): string|false {
            if ($sent) {
                return false;
            }
            $sent = true;

            return $json;
        });

        $response = $this->send(
            [new Response(503, [], $stream), new Response(200)],
            new Request('GET', 'http://cluster/v2/topology'),
        );

        self::assertSame(200, $response->getStatusCode());
        self::assertCount(2, $this->calls);
    }

    public function testExhaustionExposesTheDeserializedProblemDetail(): void
    {
        try {
            $this->send(
                array_fill(0, 3, self::problem(503, 'RESOURCE_EXHAUSTED', 'Broker is under backpressure')),
                new Request('GET', 'http://cluster/v2/topology'),
            );
            self::fail('Expected RetryExhaustedException');
        } catch (RetryExhaustedException $e) {
            $problem = $e->getResponseObject();
            self::assertInstanceOf(ProblemDetail::class, $problem);
            self::assertSame('RESOURCE_EXHAUSTED', $problem->getTitle());
            self::assertSame(503, $problem->getStatus());
            self::assertSame('Broker is under backpressure', $problem->getDetail());
            self::assertSame(503, $e->getCode());
            self::assertSame(3, $e->getAttempts());
        }
    }

    public function testExhaustionWithANonProblemBodyLeavesTheResponseObjectEmpty(): void
    {
        try {
            $this->send(
                array_fill(0, 3, new Response(429, [], 'slow down')),
                new Request('GET', 'http://cluster/v2/topology'),
            );
            self::fail('Expected RetryExhaustedException');
        } catch (RetryExhaustedException $e) {
            self::assertNull($e->getResponseObject());
            self::assertSame('slow down', $e->getResponseBody());
        }
    }

    public function testMaxAttemptsOfOneDisablesRetryAndReturnsTheResponse(): void
    {
        $response = $this->send(
            [self::problem(429, 'RESOURCE_EXHAUSTED'), new Response(200)],
            new Request('GET', 'http://cluster/v2/topology'),
            maxAttempts: 1,
        );

        self::assertSame(429, $response->getStatusCode());
        self::assertCount(1, $this->calls);
    }

    public function testBackoffIsExponentialWithFullJitterAndCapped(): void
    {
        $this->send(
            array_fill(0, 5, self::problem(429, 'RESOURCE_EXHAUSTED')),
            new Request('GET', 'http://cluster/v2/topology'),
            maxAttempts: 5,
            baseDelayMs: 100,
            maxDelayMs: 250,
            random: static fn (): float => 0.5,
            catchExhaustion: true,
        );

        // attempt 1 -> floor(0.5 * min(100, 250)), attempt 2 -> min(200, 250), then capped at 250.
        self::assertSame([null, 50, 100, 125, 125], array_map(
            static fn (array $call) => $call['options']['delay'] ?? null,
            $this->calls,
        ));
    }

    public function testJitterNeverExceedsTheExponentialCeiling(): void
    {
        $this->send(
            array_fill(0, 3, self::problem(429, 'RESOURCE_EXHAUSTED')),
            new Request('GET', 'http://cluster/v2/topology'),
            maxAttempts: 3,
            baseDelayMs: 100,
            maxDelayMs: 2000,
            random: static fn (): float => 0.999999,
            catchExhaustion: true,
        );

        self::assertSame([null, 99, 199], array_map(
            static fn (array $call) => $call['options']['delay'] ?? null,
            $this->calls,
        ));
    }

    public function testRetryAfterSecondsOverridesAShorterBackoff(): void
    {
        $this->send(
            [new Response(429, ['Retry-After' => '3']), new Response(200)],
            new Request('GET', 'http://cluster/v2/topology'),
            random: static fn (): float => 0.0,
        );

        self::assertSame(3000, $this->calls[1]['options']['delay']);
    }

    public function testRetryAfterHttpDateIsHonoured(): void
    {
        $date = gmdate('D, d M Y H:i:s', time() + 5) . ' GMT';

        $this->send(
            [new Response(429, ['Retry-After' => $date]), new Response(200)],
            new Request('GET', 'http://cluster/v2/topology'),
            random: static fn (): float => 0.0,
        );

        $delay = $this->calls[1]['options']['delay'];
        self::assertIsInt($delay);
        self::assertGreaterThanOrEqual(3000, $delay);
        self::assertLessThanOrEqual(5000, $delay);
    }

    public function testRetryAfterIsBoundedByACeiling(): void
    {
        $this->send(
            [new Response(429, ['Retry-After' => '86400']), new Response(200)],
            new Request('GET', 'http://cluster/v2/topology'),
        );

        self::assertSame(RetryMiddleware::MAX_RETRY_AFTER_MS, $this->calls[1]['options']['delay']);
    }

    #[DataProvider('ignoredRetryAfterValues')]
    public function testUnusableRetryAfterFallsBackToBackoff(string $value): void
    {
        $this->send(
            [new Response(429, ['Retry-After' => $value]), new Response(200)],
            new Request('GET', 'http://cluster/v2/topology'),
            random: static fn (): float => 0.5,
        );

        self::assertSame(50, $this->calls[1]['options']['delay']);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function ignoredRetryAfterValues(): iterable
    {
        yield 'garbage' => ['soon'];
        yield 'negative' => ['-5'];
        yield 'past date' => ['Wed, 21 Oct 2015 07:28:00 GMT'];
    }

    public function testRequestBodyIsReplayedOnEveryAttempt(): void
    {
        $this->send(
            [self::problem(429, 'RESOURCE_EXHAUSTED'), new Response(204)],
            new Request('POST', 'http://cluster/v2/process-instances', ['Content-Type' => 'application/json'], '{"a":1}'),
        );

        self::assertCount(2, $this->calls);
        self::assertSame('{"a":1}', $this->calls[0]['body']);
        self::assertSame('{"a":1}', $this->calls[1]['body']);
    }

    public function testPostBackpressureIsRetriedBecauseTheRequestWasRejectedUnprocessed(): void
    {
        $response = $this->send(
            [self::problem(503, 'RESOURCE_EXHAUSTED'), new Response(200)],
            new Request('POST', 'http://cluster/v2/process-instances', [], '{}'),
        );

        self::assertSame(200, $response->getStatusCode());
        self::assertCount(2, $this->calls);
    }

    #[DataProvider('idempotentMethods')]
    public function testIdempotentRequestsRetryTransportFailures(string $method): void
    {
        $request = new Request($method, 'http://cluster/v2/thing');

        $response = $this->send(
            [new ConnectException('connection refused', $request), new Response(200)],
            $request,
        );

        self::assertSame(200, $response->getStatusCode());
        self::assertCount(2, $this->calls);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function idempotentMethods(): iterable
    {
        foreach (['GET', 'HEAD', 'PUT', 'DELETE', 'OPTIONS'] as $method) {
            yield $method => [$method];
        }
    }

    public function testGetRetriesRequestFailuresThatNeverReceivedAResponse(): void
    {
        $request = new Request('GET', 'http://cluster/v2/topology');

        $response = $this->send(
            [new RequestException('cURL error 56: Recv failure: Connection reset by peer', $request), new Response(200)],
            $request,
        );

        self::assertSame(200, $response->getStatusCode());
        self::assertCount(2, $this->calls);
    }

    /**
     * A transport failure on a POST may have happened after the broker accepted the
     * command, so replaying it could duplicate the side effect. It surfaces untouched.
     */
    public function testPostTransportFailuresAreNotRetried(): void
    {
        $request = new Request('POST', 'http://cluster/v2/process-instances', [], '{}');
        $failure = new ConnectException('cURL error 28: Operation timed out', $request);

        try {
            $this->send([$failure, new Response(200)], $request);
            self::fail('Expected the original ConnectException');
        } catch (ConnectException $e) {
            self::assertSame($failure, $e);
        }

        self::assertCount(1, $this->calls);
    }

    public function testTransportFailureExhaustionReportsAttemptsAndTheCause(): void
    {
        $request = new Request('GET', 'http://cluster/v2/topology');
        $failure = new ConnectException('connection refused', $request);

        try {
            $this->send([$failure, $failure, $failure], $request);
            self::fail('Expected RetryExhaustedException');
        } catch (RetryExhaustedException $e) {
            self::assertSame(0, $e->getCode());
            self::assertSame(3, $e->getAttempts());
            self::assertStringContainsString('3 attempts', $e->getMessage());
            self::assertStringContainsString('connection refused', $e->getMessage());
        }
    }

    /**
     * The job-activation long poll is never retried inside the request: the worker
     * loop owns re-polling, and a hidden retry would hold a long poll open for
     * several times its request timeout.
     */
    public function testJobActivationLongPollIsNeverRetried(): void
    {
        $response = $this->send(
            [self::problem(503, 'RESOURCE_EXHAUSTED'), new Response(200)],
            new Request('POST', 'http://cluster/v2/jobs/activation', [], '{}'),
        );

        self::assertSame(503, $response->getStatusCode());
        self::assertCount(1, $this->calls);
    }

    public function testTransportFailureOfAJobActivationIsNotRetried(): void
    {
        $request = new Request('POST', 'http://cluster/v2/jobs/activation', [], '{}');

        $this->expectException(ConnectException::class);
        try {
            $this->send([new ConnectException('connection refused', $request), new Response(200)], $request);
        } finally {
            self::assertCount(1, $this->calls);
        }
    }

    public function testAsyncRequestsRetryAndResolve(): void
    {
        $promise = $this->sendAsync(
            [self::problem(429, 'RESOURCE_EXHAUSTED'), new Response(200)],
            new Request('GET', 'http://cluster/v2/topology'),
        );

        $response = $promise->wait();
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertSame(200, $response->getStatusCode());
        self::assertCount(2, $this->calls);
    }

    public function testAsyncExhaustionRejectsWithRetryExhaustedException(): void
    {
        $promise = $this->sendAsync(
            array_fill(0, 3, self::problem(429, 'RESOURCE_EXHAUSTED')),
            new Request('GET', 'http://cluster/v2/topology'),
        );

        $rejection = null;
        $promise->then(null, static function (mixed $reason) use (&$rejection): void {
            $rejection = $reason;
        });
        $promise->wait(false);

        self::assertInstanceOf(RetryExhaustedException::class, $rejection);
        // The async path never reaches the generated response parser, so the
        // exception itself must carry the deserialized problem.
        self::assertInstanceOf(ProblemDetail::class, $rejection->getResponseObject());
        self::assertCount(3, $this->calls);
    }

    public function testRejectsInvalidPolicies(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new RetryMiddleware(maxAttempts: 0, baseDelayMs: 100, maxDelayMs: 2000);
    }

    // ------------------------------------------------------------------

    /**
     * @param list<ResponseInterface|\Throwable> $outcomes
     */
    private function send(
        array $outcomes,
        RequestInterface $request,
        int $maxAttempts = 3,
        int $baseDelayMs = 100,
        int $maxDelayMs = 2000,
        ?callable $random = null,
        bool $catchExhaustion = false,
    ): ResponseInterface {
        try {
            $response = $this->sendAsync($outcomes, $request, $maxAttempts, $baseDelayMs, $maxDelayMs, $random)->wait();
            self::assertInstanceOf(ResponseInterface::class, $response);

            return $response;
        } catch (RetryExhaustedException $e) {
            if (!$catchExhaustion) {
                throw $e;
            }

            return new Response($e->getCode());
        }
    }

    /**
     * @param list<ResponseInterface|\Throwable> $outcomes
     */
    private function sendAsync(
        array $outcomes,
        RequestInterface $request,
        int $maxAttempts = 3,
        int $baseDelayMs = 100,
        int $maxDelayMs = 2000,
        ?callable $random = null,
    ): PromiseInterface {
        $this->calls = [];
        $handler = function (RequestInterface $request, array $options) use (&$outcomes): PromiseInterface {
            $this->calls[] = [
                'request' => $request,
                'options' => $options,
                // getContents() reads from the current position: a body that was not
                // rewound between attempts would come back empty on the retry.
                'body' => $request->getBody()->getContents(),
            ];
            $outcome = array_shift($outcomes);
            if ($outcome === null) {
                throw new \LogicException('Handler called more often than the test scripted.');
            }

            return $outcome instanceof \Throwable ? Create::rejectionFor($outcome) : Create::promiseFor($outcome);
        };

        $middleware = new RetryMiddleware($maxAttempts, $baseDelayMs, $maxDelayMs, $random);

        return $middleware($handler)($request, []);
    }

    private static function problem(int $status, string $title, string $detail = 'detail'): Response
    {
        return new Response(
            $status,
            ['Content-Type' => 'application/problem+json'],
            (string) json_encode(['type' => 'about:blank', 'title' => $title, 'status' => $status, 'detail' => $detail]),
        );
    }
}

/**
 * A non-seekable body of unknown size whose reads may come back empty before EOF,
 * as a socket with no data available yet does. Guzzle's own PumpStream hides this by
 * looping, so the behaviour needs a stream that exposes it.
 */
final class EmptyReadBeforeEofStream implements \Psr\Http\Message\StreamInterface
{
    /** @param list<string> $chunks One entry per read() call; an empty string is an empty read. */
    public function __construct(private array $chunks)
    {
    }

    public function __toString(): string
    {
        throw new \LogicException('Not supported on a non-seekable stream.');
    }

    public function close(): void
    {
        $this->chunks = [];
    }

    public function detach()
    {
        $this->chunks = [];

        return null;
    }

    public function getSize(): ?int
    {
        return null;
    }

    public function tell(): int
    {
        throw new \RuntimeException('Not supported.');
    }

    public function eof(): bool
    {
        return $this->chunks === [];
    }

    public function isSeekable(): bool
    {
        return false;
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        throw new \RuntimeException('Not seekable.');
    }

    public function rewind(): void
    {
        throw new \RuntimeException('Not seekable.');
    }

    public function isWritable(): bool
    {
        return false;
    }

    public function write(string $string): int
    {
        throw new \RuntimeException('Not writable.');
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function read(int $length): string
    {
        $chunk = array_shift($this->chunks) ?? '';

        return substr($chunk, 0, $length);
    }

    public function getContents(): string
    {
        $out = '';
        while (!$this->eof()) {
            $out .= $this->read(8192);
        }

        return $out;
    }

    public function getMetadata(?string $key = null)
    {
        return $key === null ? [] : null;
    }
}
