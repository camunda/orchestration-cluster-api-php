<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Model\ActivatedJobResult;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Config\ConfigResolver;
use Camunda\Orchestration\Worker\JobActionClient;
use Camunda\Orchestration\Worker\JobHandler;
use Camunda\Orchestration\Worker\JobWorker;
use Camunda\Orchestration\Worker\JobWorkerOptions;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * Issue #109: a handler that throws must not leave the job leased, drop the rest of
 * the batch, or stop the poll loop.
 *
 * These tests target the defect class — "a `Throwable` escaping the handler" — rather
 * than one handler shape: closures, {@see JobHandler} objects, errors and exceptions
 * all take the same path through `JobWorker::dispatch()`.
 */
final class JobWorkerHandlerExceptionTest extends TestCase
{
    /** @var list<RequestInterface> */
    private array $requests = [];

    public function testThrowingHandlerFailsTheJobWithOneRetryConsumed(): void
    {
        $worker = $this->worker([
            $this->activationResponse([['jobKey' => '1', 'retries' => 3]]),
            new Response(204),
        ]);

        $processed = $worker->pollOnce(
            static function (ActivatedJobResult $job, JobActionClient $action): array {
                throw new \RuntimeException('handler blew up');
            },
        );

        self::assertSame(1, $processed);
        self::assertCount(2, $this->requests);
        self::assertStringEndsWith('/jobs/1/failure', $this->requests[1]->getUri()->getPath());

        $body = $this->jsonBody($this->requests[1]);
        self::assertSame(2, $body['retries']);
        self::assertIsString($body['errorMessage']);
        self::assertStringContainsString('RuntimeException', $body['errorMessage']);
        self::assertStringContainsString('handler blew up', $body['errorMessage']);
    }

    /**
     * The whole `Throwable` hierarchy must be caught, not just `Exception`.
     */
    #[DataProvider('throwingHandlers')]
    public function testEveryThrowableShapeIsTurnedIntoAJobFailure(\Closure $handler, string $expectedFragment): void
    {
        $worker = $this->worker([
            $this->activationResponse([['jobKey' => '1', 'retries' => 3]]),
            new Response(204),
        ]);

        $worker->pollOnce($handler);

        self::assertCount(2, $this->requests);
        self::assertStringEndsWith('/jobs/1/failure', $this->requests[1]->getUri()->getPath());
        $errorMessage = $this->jsonBody($this->requests[1])['errorMessage'];
        self::assertIsString($errorMessage);
        self::assertStringContainsString($expectedFragment, $errorMessage);
    }

    /**
     * @return iterable<string, array{\Closure, string}>
     */
    public static function throwingHandlers(): iterable
    {
        yield 'exception' => [
            static function (ActivatedJobResult $job, JobActionClient $action): void {
                throw new \LogicException('bad state');
            },
            'LogicException',
        ];

        yield 'error' => [
            static function (ActivatedJobResult $job, JobActionClient $action): void {
                throw new \TypeError('wrong type');
            },
            'TypeError',
        ];

        yield 'division by zero' => [
            static function (ActivatedJobResult $job, JobActionClient $action): void {
                /** @var int $zero */
                $zero = 0;
                throw new \DivisionByZeroError('Division by zero ' . $zero);
            },
            'DivisionByZeroError',
        ];
    }

    public function testThrowingJobHandlerObjectIsAlsoFailed(): void
    {
        $worker = $this->worker([
            $this->activationResponse([['jobKey' => '1', 'retries' => 1]]),
            new Response(204),
        ]);

        $worker->pollOnce(new class () implements JobHandler {
            public function handle(ActivatedJobResult $job, JobActionClient $action): ?array
            {
                throw new \RuntimeException('object handler blew up');
            }
        });

        self::assertCount(2, $this->requests);
        self::assertStringEndsWith('/jobs/1/failure', $this->requests[1]->getUri()->getPath());
        self::assertSame(0, $this->jsonBody($this->requests[1])['retries']);
    }

    public function testRetriesNeverGoNegative(): void
    {
        $worker = $this->worker([
            $this->activationResponse([['jobKey' => '1', 'retries' => 0]]),
            new Response(204),
        ]);

        $worker->pollOnce(
            static function (ActivatedJobResult $job, JobActionClient $action): void {
                throw new \RuntimeException('no retries left');
            },
        );

        self::assertSame(0, $this->jsonBody($this->requests[1])['retries']);
    }

    public function testTheRestOfTheBatchIsStillDispatchedAfterAHandlerThrows(): void
    {
        $worker = $this->worker([
            $this->activationResponse([
                ['jobKey' => '1', 'retries' => 3],
                ['jobKey' => '2', 'retries' => 3],
            ]),
            new Response(204),
            new Response(204),
        ]);

        $processed = $worker->pollOnce(
            static function (ActivatedJobResult $job, JobActionClient $action): array {
                if ((string) $job->getJobKey() === '1') {
                    throw new \RuntimeException('first job blew up');
                }

                return ['handled' => true];
            },
        );

        self::assertSame(2, $processed);
        self::assertCount(3, $this->requests);
        self::assertStringEndsWith('/jobs/1/failure', $this->requests[1]->getUri()->getPath());
        self::assertStringEndsWith('/jobs/2/completion', $this->requests[2]->getUri()->getPath());
    }

    public function testRunKeepsPollingAfterAHandlerThrows(): void
    {
        $worker = $this->worker([
            $this->activationResponse([['jobKey' => '1', 'retries' => 3]]),
            new Response(204),
            $this->activationResponse([['jobKey' => '2', 'retries' => 3]]),
            new Response(204),
        ], new JobWorkerOptions(type: 'greet', pollIntervalMs: 0));

        $worker->run(static function (ActivatedJobResult $job, JobActionClient $action) use ($worker): array {
            if ((string) $job->getJobKey() === '1') {
                throw new \RuntimeException('first batch blew up');
            }

            $worker->stop();

            return ['handled' => true];
        });

        self::assertCount(4, $this->requests);
        self::assertStringEndsWith('/jobs/1/failure', $this->requests[1]->getUri()->getPath());
        self::assertStringEndsWith('/jobs/activation', $this->requests[2]->getUri()->getPath());
        self::assertStringEndsWith('/jobs/2/completion', $this->requests[3]->getUri()->getPath());
    }

    public function testAJobAlreadyActedOnIsNotFailedAgainWhenTheHandlerThrows(): void
    {
        $worker = $this->worker([
            $this->activationResponse([['jobKey' => '1', 'retries' => 3]]),
            new Response(204),
        ]);

        $worker->pollOnce(
            static function (ActivatedJobResult $job, JobActionClient $action): void {
                $action->complete(['done' => true]);
                throw new \RuntimeException('thrown after the terminal action');
            },
        );

        self::assertCount(2, $this->requests);
        self::assertStringEndsWith('/jobs/1/completion', $this->requests[1]->getUri()->getPath());
    }

    public function testStopRequestedBeforeAHandlerThrowsStillStopsTheLoop(): void
    {
        $worker = $this->worker([
            $this->activationResponse([['jobKey' => '1', 'retries' => 3]]),
            new Response(204),
        ], new JobWorkerOptions(type: 'greet', pollIntervalMs: 0));

        $worker->run(static function (ActivatedJobResult $job, JobActionClient $action) use ($worker): void {
            $worker->stop();
            throw new \RuntimeException('blew up on the way out');
        });

        self::assertCount(2, $this->requests);
        self::assertStringEndsWith('/jobs/1/failure', $this->requests[1]->getUri()->getPath());
    }

    /**
     * A terminal action the handler asked for explicitly must not be replaced by the
     * worker's own failure when its request fails. Doing so would discard the retries,
     * back-off and message the handler chose and swallow the transport error.
     *
     * @param \Closure(ActivatedJobResult, JobActionClient): void $handler
     * @param non-empty-string $expectedPath
     */
    #[DataProvider('explicitTerminalActions')]
    public function testAFailedExplicitTerminalActionIsNotReplacedByTheWorker(
        \Closure $handler,
        string $expectedPath,
    ): void {
        $worker = $this->worker([
            $this->activationResponse([['jobKey' => '1', 'retries' => 3]]),
            new Response(503, ['Content-Type' => 'application/problem+json'], '{"title":"RESOURCE_EXHAUSTED"}'),
            new Response(204),
        ]);

        try {
            $worker->pollOnce($handler);
            self::fail('Expected the failed terminal action to surface to the caller.');
        } catch (\Camunda\Orchestration\Api\ApiException $exception) {
            self::assertSame(503, $exception->getCode());
        }

        self::assertCount(2, $this->requests, 'The worker must not send a substitute terminal action.');
        self::assertStringEndsWith($expectedPath, $this->requests[1]->getUri()->getPath());
    }

    /**
     * @return iterable<string, array{\Closure, non-empty-string}>
     */
    public static function explicitTerminalActions(): iterable
    {
        yield 'fail' => [
            static function (ActivatedJobResult $job, JobActionClient $action): void {
                $action->fail(retries: 0, errorMessage: 'give up on this one', retryBackOffMs: 5_000);
            },
            '/jobs/1/failure',
        ];

        yield 'complete' => [
            static function (ActivatedJobResult $job, JobActionClient $action): void {
                $action->complete(['done' => true]);
            },
            '/jobs/1/completion',
        ];

        yield 'bpmn error' => [
            static function (ActivatedJobResult $job, JobActionClient $action): void {
                $action->error('PAYMENT_DECLINED', 'the card was declined');
            },
            '/jobs/1/error',
        ];
    }

    /**
     * A failure report that itself fails is a transport problem, not a handler problem:
     * it keeps the existing behaviour of surfacing to the caller, exactly as a failing
     * auto-completion does today.
     */
    public function testATransportErrorWhileFailingTheJobStillSurfacesToTheCaller(): void
    {
        $worker = $this->worker([
            $this->activationResponse([['jobKey' => '1', 'retries' => 3]]),
            new Response(503, ['Content-Type' => 'application/problem+json'], '{"title":"RESOURCE_EXHAUSTED"}'),
        ]);

        $this->expectException(\Camunda\Orchestration\Api\ApiException::class);

        $worker->pollOnce(
            static function (ActivatedJobResult $job, JobActionClient $action): void {
                throw new \RuntimeException('handler blew up');
            },
        );
    }

    /**
     * @param list<Response> $responses
     */
    private function worker(array $responses, ?JobWorkerOptions $options = null): JobWorker
    {
        $client = CamundaClient::fromConfiguration(
            ConfigResolver::resolve(
                overrides: ['CAMUNDA_AUTH_STRATEGY' => 'NONE'],
                environment: [],
            ),
            $this->httpClient($responses),
        );

        return $client->createJobWorker($options ?? new JobWorkerOptions(type: 'greet'));
    }

    /**
     * @param list<array{jobKey: string, retries: int}> $jobs
     */
    private function activationResponse(array $jobs): Response
    {
        $payload = [];
        foreach ($jobs as $job) {
            $payload[] = [
                'type' => 'greet',
                'processDefinitionId' => 'greet-process',
                'processDefinitionVersion' => 1,
                'elementId' => 'greet-task',
                'customHeaders' => [],
                'worker' => 'php-example-worker',
                'retries' => $job['retries'],
                'deadline' => 1_789_636_204_728,
                'variables' => ['name' => 'handler-exception-regression'],
                'tenantId' => '<default>',
                'jobKey' => $job['jobKey'],
                'processInstanceKey' => '2251799813686194',
                'processDefinitionKey' => '2251799813685676',
                'elementInstanceKey' => '2251799813686199',
                'kind' => 'BPMN_ELEMENT',
                'listenerEventType' => 'UNSPECIFIED',
                'userTask' => null,
                'tags' => [],
                'rootProcessInstanceKey' => '2251799813686194',
            ];
        }

        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode(['jobs' => $payload]),
        );
    }

    /**
     * @param list<Response> $responses
     */
    private function httpClient(array $responses): GuzzleClient
    {
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(function (callable $handler): callable {
            return function (RequestInterface $request, array $options) use ($handler) {
                $this->requests[] = $request;

                return $handler($request, $options);
            };
        });

        return new GuzzleClient(['handler' => $stack, 'http_errors' => false]);
    }

    /**
     * @return array<string, mixed>
     */
    private function jsonBody(RequestInterface $request): array
    {
        /** @var array<string, mixed> $body */
        $body = json_decode((string) $request->getBody(), true, flags: JSON_THROW_ON_ERROR);

        return $body;
    }
}
