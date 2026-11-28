<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance\Runtime;

use Camunda\Orchestration\Api\ApiException;
use Camunda\Orchestration\Api\Model\ActivatedJobResult;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Config\ConfigResolver;
use Camunda\Orchestration\Exception\RetryExhaustedException;
use Camunda\Orchestration\Http\RetryMiddleware;
use Camunda\Orchestration\Tests\Support\JobFixtures;
use Camunda\Orchestration\Worker\JobActionClient;
use Camunda\Orchestration\Worker\JobWorkerOptions;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Log\AbstractLogger;

/**
 * Issue #193: job commands are retried only when the server says they were not applied
 * (429, 503, connection failure before sending), and the worker sends no follow-up
 * `failJob` when a job command's outcome is unknown.
 */
final class JobCommandOutcomeTest extends TestCase
{
    /** @var list<RequestInterface> */
    private array $requests = [];

    /** @var list<string> */
    private array $warnings = [];

    /** @return iterable<string, array{int}> */
    public static function unknownOutcomeStatuses(): iterable
    {
        yield '504 gateway timeout' => [504];
        yield '502 bad gateway' => [502];
        yield '500 server error' => [500];
    }

    #[DataProvider('unknownOutcomeStatuses')]
    public function testUnknownOutcomeOnAutoCompletionIsSentOnceWithNoFailJob(int $status): void
    {
        $processed = $this->client([
            JobFixtures::activation('7', '8'),
            JobFixtures::problem($status, 'RESOURCE_EXHAUSTED', 'outcome unknown'),
            new Response(204),
        ])->createJobWorker($this->options())->pollOnce(static fn (): array => ['ok' => true]);

        self::assertSame(2, $processed, 'the dispatch loop carries on with the batch');
        self::assertSame(
            ['/jobs/activation', '/jobs/7/completion', '/jobs/8/completion'],
            $this->paths(),
            'exactly one completion request for job 7 and no failJob',
        );
        self::assertCount(1, $this->warnings);
        self::assertStringContainsString('Job 7: job command outcome unknown (HTTP ' . $status . ')', $this->warnings[0]);
        self::assertStringContainsString('may have completed', $this->warnings[0]);
    }

    public function testReadTimeoutOnCompletionIsNotRetriedAndNotFailed(): void
    {
        $timeout = static fn (RequestInterface $request): ConnectException => new ConnectException(
            'cURL error 28: Operation timed out',
            $request,
            null,
            ['errno' => 28],
        );
        $this->client([JobFixtures::activation('7'), $timeout])
            ->createJobWorker($this->options())
            ->pollOnce(static fn (): array => []);

        self::assertSame(['/jobs/activation', '/jobs/7/completion'], $this->paths());
        self::assertStringContainsString('outcome unknown (no response)', $this->warnings[0]);
    }

    public function testUnknownOutcomeOfAnExplicitActionDoesNotFailTheJob(): void
    {
        $this->client([JobFixtures::activation('7'), JobFixtures::problem(504, 'GATEWAY_TIMEOUT')])
            ->createJobWorker($this->options())
            ->pollOnce(static function (ActivatedJobResult $job, JobActionClient $action): null {
                $action->complete();

                return null;
            });

        self::assertSame(['/jobs/activation', '/jobs/7/completion'], $this->paths());
        self::assertCount(1, $this->warnings);
    }

    public function testCaughtUnknownOutcomeOfAnExplicitActionIsNotAutoCompleted(): void
    {
        foreach (['complete', 'error'] as $method) {
            $this->requests = [];
            $this->warnings = [];
            $this->client([JobFixtures::activation('7'), JobFixtures::problem(504, 'GATEWAY_TIMEOUT')])
                ->createJobWorker($this->options())
                ->pollOnce(static function (ActivatedJobResult $job, JobActionClient $action) use ($method): null {
                    try {
                        $method === 'complete' ? $action->complete() : $action->error('E');
                    } catch (ApiException) {
                    }

                    return null;
                });

            $path = $method === 'complete' ? '/jobs/7/completion' : '/jobs/7/error';
            self::assertSame(['/jobs/activation', $path], $this->paths(), $method);
            self::assertCount(1, $this->warnings, $method);
            self::assertStringContainsString('not auto-completing', implode("\n", $this->warnings));
        }
    }

    public function testUnknownOutcomeOfTheWorkersOwnFailJobIsNotRepeated(): void
    {
        $this->client([JobFixtures::activation('7'), JobFixtures::problem(504, 'GATEWAY_TIMEOUT')])
            ->createJobWorker($this->options())
            ->pollOnce(static function (): never {
                throw new \RuntimeException('boom');
            });

        self::assertSame(['/jobs/activation', '/jobs/7/failure'], $this->paths());
        self::assertCount(1, $this->warnings);
    }

    /** @return iterable<string, array{int, string}> */
    public static function notAppliedStatuses(): iterable
    {
        yield '503 without RESOURCE_EXHAUSTED' => [503, 'SERVICE_UNAVAILABLE'];
        yield '429' => [429, 'TOO_MANY_REQUESTS'];
    }

    #[DataProvider('notAppliedStatuses')]
    public function testNotAppliedCompletionIsRetried(int $status, string $title): void
    {
        $this->client([
            JobFixtures::activation('7'),
            JobFixtures::problem($status, $title),
            new Response(204),
        ])->createJobWorker($this->options())->pollOnce(static fn (): array => []);

        self::assertSame(['/jobs/activation', '/jobs/7/completion', '/jobs/7/completion'], $this->paths());
        self::assertSame([], $this->warnings);
    }

    public function testExhaustedNotAppliedCompletionStillSurfaces(): void
    {
        $worker = $this->client([
            JobFixtures::activation('7'),
            JobFixtures::problem(503, 'UNAVAILABLE'),
            JobFixtures::problem(503, 'UNAVAILABLE'),
            JobFixtures::problem(503, 'UNAVAILABLE'),
        ])->createJobWorker($this->options());

        try {
            $worker->pollOnce(static fn (): array => []);
            self::fail('Expected RetryExhaustedException');
        } catch (RetryExhaustedException $e) {
            self::assertSame(503, $e->getCode());
        }
        self::assertCount(4, $this->requests);
    }

    public function testNotFoundIsLoggedWithoutStackTraceAndStillSurfaces(): void
    {
        $worker = $this->client([JobFixtures::activation('7'), JobFixtures::problem(404, 'NOT_FOUND')])
            ->createJobWorker($this->options());

        try {
            $worker->pollOnce(static fn (): array => []);
            self::fail('Expected ApiException');
        } catch (ApiException $e) {
            self::assertSame(404, $e->getCode());
        }
        self::assertSame(['Job 7: job not found (already completed, failed or timed out)'], $this->warnings);
        self::assertCount(2, $this->requests);
    }

    public function testMiddlewareRetriesJobCommandOnlyOnPreSendConnectionFailure(): void
    {
        $request = new Request('POST', 'http://localhost/v2/jobs/7/completion', [], '{}');
        foreach ([7 => 2, 6 => 2, 35 => 2, 28 => 1, 56 => 1] as $errno => $expected) {
            $calls = 0;
            $handler = static function (RequestInterface $r) use (&$calls, $errno): \GuzzleHttp\Promise\PromiseInterface {
                ++$calls;

                return $calls === 1
                    ? \GuzzleHttp\Promise\Create::rejectionFor(new ConnectException('x', $r, null, ['errno' => $errno]))
                    : \GuzzleHttp\Promise\Create::promiseFor(new Response(204));
            };
            $middleware = new RetryMiddleware(3, 0, 0, static fn (): float => 0.0);
            try {
                $middleware($handler)($request, [])->wait();
            } catch (ConnectException) {
            }
            self::assertSame($expected, $calls, "curl errno $errno");
        }
    }

    public function testMiddlewareRetriesJobCommandOnResponselessRequestExceptionPreSendFailure(): void
    {
        $request = new Request('POST', 'http://localhost/v2/jobs/7/completion', [], '{}');
        foreach ([5 => 2, 6 => 2, 28 => 1] as $errno => $expected) {
            $calls = 0;
            $handler = static function (RequestInterface $r) use (&$calls, $errno): \GuzzleHttp\Promise\PromiseInterface {
                ++$calls;

                return $calls === 1
                    ? \GuzzleHttp\Promise\Create::rejectionFor(new RequestException('x', $r, null, null, ['errno' => $errno]))
                    : \GuzzleHttp\Promise\Create::promiseFor(new Response(204));
            };
            $middleware = new RetryMiddleware(3, 0, 0, static fn (): float => 0.0);
            try {
                $middleware($handler)($request, [])->wait();
            } catch (RequestException) {
            }
            self::assertSame($expected, $calls, "curl errno $errno");
        }
    }

    public function testMiddlewareRecognisesJobCommands(): void
    {
        self::assertTrue(RetryMiddleware::isJobCommand(new Request('POST', '/v2/jobs/1/completion')));
        self::assertTrue(RetryMiddleware::isJobCommand(new Request('POST', '/v2/jobs/1/failure')));
        self::assertTrue(RetryMiddleware::isJobCommand(new Request('POST', '/v2/jobs/1/error')));
        self::assertFalse(RetryMiddleware::isJobCommand(new Request('POST', '/v2/jobs/activation')));
        self::assertFalse(RetryMiddleware::isJobCommand(new Request('PATCH', '/v2/jobs/1')));
        self::assertFalse(RetryMiddleware::isJobCommand(new Request('POST', '/v2/user-tasks/1/completion')));
    }

    private function options(): JobWorkerOptions
    {
        $record = function (string $message): void {
            $this->warnings[] = $message;
        };
        $logger = new class ($record) extends AbstractLogger {
            /** @param \Closure(string): void $record */
            public function __construct(private readonly \Closure $record)
            {
            }

            public function log($level, string|\Stringable $message, array $context = []): void
            {
                ($this->record)((string) $message);
            }
        };

        return new JobWorkerOptions(type: 'greet', logger: $logger);
    }

    /**
     * @param list<Response|\Closure(RequestInterface): \Throwable> $queue
     */
    private function client(array $queue): CamundaClient
    {
        $mock = new MockHandler(array_map(
            static fn (Response|\Closure $item) => $item instanceof \Closure
                ? static fn (RequestInterface $request) => \GuzzleHttp\Promise\Create::rejectionFor($item($request))
                : $item,
            $queue,
        ));
        $stack = HandlerStack::create($mock);
        // Pushed first, so it wraps the recorder: every attempt is recorded.
        $stack->push(new RetryMiddleware(3, 0, 0, static fn (): float => 0.0));
        $stack->push(function (callable $handler): callable {
            return function (RequestInterface $request, array $options) use ($handler) {
                $this->requests[] = $request;

                return $handler($request, $options);
            };
        });

        return CamundaClient::fromConfiguration(
            ConfigResolver::resolve(overrides: ['CAMUNDA_AUTH_STRATEGY' => 'NONE'], environment: []),
            new GuzzleClient(['handler' => $stack, 'http_errors' => false]),
        );
    }

    /** @return list<string> */
    private function paths(): array
    {
        return array_map(
            static fn (RequestInterface $r): string => (string) preg_replace('#^.*?(/jobs/)#', '$1', $r->getUri()->getPath()),
            $this->requests,
        );
    }
}
