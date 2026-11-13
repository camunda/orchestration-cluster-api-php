<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance\Runtime;

use Camunda\Orchestration\Api\ApiException;
use Camunda\Orchestration\Api\Model\ActivatedJobResult;
use Camunda\Orchestration\Api\Model\ProblemDetail;
use Camunda\Orchestration\Tests\Support\JobFixtures;
use Camunda\Orchestration\Tests\Support\RecordingGuzzle;
use Camunda\Orchestration\Worker\JobActionClient;
use Camunda\Orchestration\Worker\JobWorker;
use Camunda\Orchestration\Worker\JobWorkerOptions;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Behaviour of {@see JobWorker} / {@see JobActionClient} on the paths that are easy to get wrong:
 * handler failures, action failures, shutdown while a job is in flight, and activation
 * backpressure. These pin the worker's current contract; none of them touch a live cluster.
 */
final class JobWorkerBehaviourTest extends TestCase
{
    private RecordingGuzzle $http;

    protected function setUp(): void
    {
        $this->http = new RecordingGuzzle();
    }

    // ------------------------------------------------------------------ dispatch

    public function testEmptyActivationProcessesNothingAndSendsNoFurtherRequests(): void
    {
        $worker = $this->worker([JobFixtures::activation()]);

        $processed = $worker->pollOnce(static function (): never {
            throw new \LogicException('handler must not be called for an empty batch');
        });

        self::assertSame(0, $processed);
        self::assertCount(1, $this->http->requests);
    }

    public function testEveryJobOfABatchIsHandledInOrderAndCompletedWithItsOwnKey(): void
    {
        $worker = $this->worker([
            JobFixtures::activation('101', '102', '103'),
            new Response(204),
            new Response(204),
            new Response(204),
        ]);
        $seen = [];

        $processed = $worker->pollOnce(function (ActivatedJobResult $job, JobActionClient $action) use (&$seen): array {
            $seen[] = [(string) $job->getJobKey(), $job->getType(), $job->getVariables()];

            return ['handled' => (string) $job->getJobKey()];
        });

        self::assertSame(3, $processed);
        self::assertSame([
            ['101', 'greet', ['name' => 'job-101']],
            ['102', 'greet', ['name' => 'job-102']],
            ['103', 'greet', ['name' => 'job-103']],
        ], $seen);
        self::assertCount(4, $this->http->requests);
        foreach (['101', '102', '103'] as $i => $key) {
            $request = $this->http->requests[$i + 1];
            self::assertSame('POST', $request->getMethod());
            self::assertStringEndsWith("/jobs/$key/completion", $request->getUri()->getPath());
            self::assertSame(['variables' => ['handled' => $key]], $this->http->jsonBody($i + 1));
        }
    }

    /**
     * @param array<string, mixed>|null $returned
     */
    #[DataProvider('handlerReturnsWithoutVariables')]
    public function testAutoCompletionWithoutVariablesSendsAnEmptyCompletionBody(?array $returned): void
    {
        $worker = $this->worker([JobFixtures::activation('7'), new Response(204)]);

        $worker->pollOnce(static fn (): ?array => $returned);

        self::assertSame('{}', (string) $this->http->requests[1]->getBody());
    }

    /** @return iterable<string, array{array<string, mixed>|null}> */
    public static function handlerReturnsWithoutVariables(): iterable
    {
        yield 'null' => [null];
        yield 'empty array' => [[]];
    }

    public function testNothingIsCompletedWhenAutoCompleteIsDisabled(): void
    {
        $worker = $this->worker(
            [JobFixtures::activation('7')],
            new JobWorkerOptions(type: 'greet', autoComplete: false),
        );

        $processed = $worker->pollOnce(static fn (): array => ['ignored' => true]);

        self::assertSame(1, $processed);
        self::assertCount(1, $this->http->requests, 'only the activation request must have been sent');
    }

    public function testAnExplicitCompletionIsNotFollowedByAnAutomaticOne(): void
    {
        $worker = $this->worker([JobFixtures::activation('7'), new Response(204)]);

        $worker->pollOnce(static function (ActivatedJobResult $job, JobActionClient $action): array {
            $action->complete(['explicit' => 1]);
            self::assertTrue($action->wasHandled());

            return ['returned' => 2];
        });

        self::assertCount(2, $this->http->requests);
        self::assertSame(['variables' => ['explicit' => 1]], $this->http->jsonBody(1));
    }

    public function testErrorActionRaisesABpmnErrorAndSuppressesAutoCompletion(): void
    {
        $worker = $this->worker([JobFixtures::activation('7'), new Response(204)]);

        $worker->pollOnce(static function (ActivatedJobResult $job, JobActionClient $action): array {
            $action->error('PAYMENT_DECLINED', 'card declined', ['reason' => 'limit']);

            return ['mustNotBeCompleted' => true];
        });

        self::assertCount(2, $this->http->requests);
        self::assertStringEndsWith('/jobs/7/error', $this->http->requests[1]->getUri()->getPath());
        self::assertSame(
            ['errorCode' => 'PAYMENT_DECLINED', 'errorMessage' => 'card declined', 'variables' => ['reason' => 'limit']],
            $this->http->jsonBody(1),
        );
    }

    public function testErrorActionOmitsOptionalFieldsWhenNotGiven(): void
    {
        $worker = $this->worker([JobFixtures::activation('7'), new Response(204)]);

        $worker->pollOnce(static fn (ActivatedJobResult $job, JobActionClient $action): null => $action->error('E1'));

        self::assertSame(['errorCode' => 'E1'], $this->http->jsonBody(1));
    }

    public function testFailActionDefaultsToNoRetriesAndOmitsOptionalFields(): void
    {
        $worker = $this->worker([JobFixtures::activation('7'), new Response(204)]);

        $worker->pollOnce(static fn (ActivatedJobResult $job, JobActionClient $action): null => $action->fail());

        self::assertStringEndsWith('/jobs/7/failure', $this->http->requests[1]->getUri()->getPath());
        self::assertSame(['retries' => 0], $this->http->jsonBody(1));
    }

    public function testFailActionCarriesVariablesRetriesMessageAndBackoff(): void
    {
        $worker = $this->worker([JobFixtures::activation('7'), new Response(204)]);

        $worker->pollOnce(static fn (ActivatedJobResult $job, JobActionClient $action): null => $action->fail(
            retries: 4,
            errorMessage: 'downstream timeout',
            retryBackOffMs: 2500,
            variables: ['attempt' => 2],
        ));

        self::assertSame(
            ['retries' => 4, 'errorMessage' => 'downstream timeout', 'retryBackOff' => 2500, 'variables' => ['attempt' => 2]],
            $this->http->jsonBody(1),
        );
    }

    public function testActivationRequestDefaultsAndOmissions(): void
    {
        $worker = $this->worker([JobFixtures::activation()], new JobWorkerOptions(type: 'greet'));

        $worker->pollOnce(static fn (): null => null);

        self::assertSame([
            'type' => 'greet',
            'worker' => 'camunda-orchestration-php',
            'timeout' => 60_000,
            'maxJobsToActivate' => 32,
            'requestTimeout' => 10_000,
        ], $this->http->jsonBody(0), 'fetchVariable, tenantIds and tenantFilter must be omitted when not configured');
    }

    public function testOptionsHaveDocumentedDefaults(): void
    {
        $options = new JobWorkerOptions(type: 't');

        self::assertSame(32, $options->maxJobs);
        self::assertSame(60_000, $options->timeoutMs);
        self::assertSame(10_000, $options->requestTimeoutMs);
        self::assertSame([], $options->fetchVariables);
        self::assertTrue($options->autoComplete);
        self::assertSame(250, $options->pollIntervalMs);
        self::assertFalse($options->forked);
        self::assertNull($options->workerName);
        self::assertSame([], $options->tenantIds);
    }

    // ------------------------------------------------------------------ handler failures

    public function testHandlerExceptionPropagatesAndTheJobIsNeitherCompletedNorFailedByTheWorker(): void
    {
        $worker = $this->worker([JobFixtures::activation('101', '102')]);
        $boom = new \DomainException('handler blew up');

        try {
            $worker->pollOnce(static function () use ($boom): never {
                throw $boom;
            });
            self::fail('Expected the handler exception to propagate.');
        } catch (\DomainException $e) {
            self::assertSame($boom, $e);
        }

        // Characterisation: the worker does not catch handler exceptions. The job is left to
        // time out on the broker, and the remaining jobs of the batch are not dispatched.
        self::assertCount(1, $this->http->requests, 'only the activation request: no completion, no failure');
    }

    public function testHandlerExceptionAfterAnExplicitActionDoesNotCauseASecondAction(): void
    {
        $worker = $this->worker([JobFixtures::activation('7'), new Response(204)]);

        try {
            $worker->pollOnce(static function (ActivatedJobResult $job, JobActionClient $action): never {
                $action->complete();
                throw new \DomainException('late failure');
            });
            self::fail('Expected the handler exception to propagate.');
        } catch (\DomainException) {
        }

        self::assertCount(2, $this->http->requests);
        self::assertStringEndsWith('/jobs/7/completion', $this->http->requests[1]->getUri()->getPath());
    }

    /**
     * @param int $status
     */
    #[DataProvider('rejectedActionStatuses')]
    public function testRejectedJobActionSurfacesAsApiExceptionAndIsNotMarkedHandled(int $status): void
    {
        $worker = $this->worker([JobFixtures::activation('7'), JobFixtures::problem($status, 'REJECTED', 'nope')]);
        $actions = [];

        try {
            $worker->pollOnce(function (ActivatedJobResult $job, JobActionClient $action) use (&$actions): null {
                $actions[] = $action;
                $action->complete(['x' => 1]);

                return null;
            });
            self::fail('Expected an ApiException for the rejected completion.');
        } catch (ApiException $e) {
            self::assertSame($status, $e->getCode());
        }

        self::assertCount(1, $actions);
        self::assertFalse($actions[0]->wasHandled(), 'a rejected action must not count as handled');
        self::assertCount(2, $this->http->requests, 'no automatic retry of the rejected action');
    }

    /** @return iterable<string, array{int}> */
    public static function rejectedActionStatuses(): iterable
    {
        yield '404 job not found' => [404];
        yield '409 job in invalid state' => [409];
        yield '429 rate limited' => [429];
        yield '500 server error' => [500];
        yield '503 backpressure' => [503];
    }

    public function testRejectedFailAndErrorActionsAlsoSurface(): void
    {
        foreach (['fail', 'error'] as $kind) {
            $this->http = new RecordingGuzzle();
            $worker = $this->worker([JobFixtures::activation('7'), JobFixtures::problem(404, 'NOT_FOUND')]);

            try {
                $worker->pollOnce(static function (ActivatedJobResult $job, JobActionClient $action) use ($kind): null {
                    $kind === 'fail' ? $action->fail(1) : $action->error('E');

                    return null;
                });
                self::fail("Expected an ApiException for the rejected $kind action.");
            } catch (ApiException $e) {
                self::assertSame(404, $e->getCode(), $kind);
            }
        }
    }

    // ------------------------------------------------------------------ run loop / shutdown

    public function testStopRequestedFromAHandlerLetsTheInFlightJobFinishAndEndsTheLoop(): void
    {
        $worker = $this->worker([
            JobFixtures::activation('7', '8'),
            new Response(204),
            new Response(204),
        ], new JobWorkerOptions(type: 'greet', pollIntervalMs: 0));
        $handled = [];

        $worker->run(function (ActivatedJobResult $job, JobActionClient $action) use (&$handled, &$worker): array {
            $handled[] = (string) $job->getJobKey();
            $worker->stop();

            return ['done' => true];
        });

        // stop() is cooperative: the whole in-flight batch is finished and acknowledged, and no
        // further activation is requested (a further poll would exhaust the mock queue and throw).
        self::assertSame(['7', '8'], $handled);
        self::assertCount(3, $this->http->requests);
        self::assertStringEndsWith('/jobs/activation', $this->http->requests[0]->getUri()->getPath());
        self::assertStringEndsWith('/jobs/7/completion', $this->http->requests[1]->getUri()->getPath());
        self::assertStringEndsWith('/jobs/8/completion', $this->http->requests[2]->getUri()->getPath());
    }

    public function testRunKeepsPollingThroughEmptyBatchesUntilAJobArrives(): void
    {
        $worker = $this->worker([
            JobFixtures::activation(),
            JobFixtures::activation(),
            JobFixtures::activation('9'),
            new Response(204),
        ], new JobWorkerOptions(type: 'greet', pollIntervalMs: 0));

        $worker->run(static function (ActivatedJobResult $job, JobActionClient $action) use (&$worker): null {
            $worker->stop();

            return null;
        });

        self::assertCount(4, $this->http->requests);
    }

    public function testRunAppliesThePollIntervalOnlyAfterAnEmptyBatch(): void
    {
        $worker = $this->worker([
            JobFixtures::activation(),
            JobFixtures::activation('9'),
            new Response(204),
        ], new JobWorkerOptions(type: 'greet', pollIntervalMs: 50));

        $start = microtime(true);
        $worker->run(static function (ActivatedJobResult $job, JobActionClient $action) use (&$worker): null {
            $worker->stop();

            return null;
        });
        $elapsed = microtime(true) - $start;

        self::assertGreaterThanOrEqual(0.045, $elapsed, 'one empty batch => one poll interval of back-off');
        self::assertLessThan(5.0, $elapsed);
    }

    public function testHandlerFailureInsideRunTerminatesTheLoopAndLeavesNoStaleRunningState(): void
    {
        $worker = $this->worker([
            JobFixtures::activation('7'),
            JobFixtures::activation('8'),
            new Response(204),
        ], new JobWorkerOptions(type: 'greet', pollIntervalMs: 0));
        $calls = 0;

        try {
            $worker->run(static function () use (&$calls): never {
                ++$calls;
                throw new \DomainException('boom');
            });
            self::fail('Expected run() to propagate the handler exception.');
        } catch (\DomainException) {
        }

        self::assertSame(1, $calls, 'the loop must not keep dispatching after a handler exception');
        self::assertCount(1, $this->http->requests);
    }

    // ------------------------------------------------------------------ backpressure

    /**
     * @param int $status
     */
    #[DataProvider('activationFailureStatuses')]
    public function testActivationFailureEndsRunWithoutAHiddenRetryLoop(int $status): void
    {
        $worker = $this->worker(
            [JobFixtures::problem($status, 'RESOURCE_EXHAUSTED', 'Broker is under backpressure')],
            new JobWorkerOptions(type: 'greet', pollIntervalMs: 0),
        );

        try {
            $worker->run(static function (): never {
                throw new \LogicException('no job was activated');
            });
            self::fail('Expected an ApiException.');
        } catch (ApiException $e) {
            self::assertSame($status, $e->getCode());
            if ($status === 429) {
                // 429 is not a documented response of the activation operation, so the generated
                // client raises its generic exception and keeps the raw problem body.
                $body = $e->getResponseBody();
                self::assertIsString($body);
                self::assertStringContainsString('RESOURCE_EXHAUSTED', $body);
            } else {
                // Documented error statuses are handed to the worker as a ProblemDetail.
                self::assertSame(
                    sprintf('[%d] Job activation failed (RESOURCE_EXHAUSTED: Broker is under backpressure)', $status),
                    $e->getMessage(),
                );
                $problem = $e->getResponseObject();
                self::assertInstanceOf(ProblemDetail::class, $problem);
                self::assertSame('RESOURCE_EXHAUSTED', $problem->getTitle());
            }
        }

        self::assertCount(1, $this->http->requests, 'the worker must not re-poll behind the caller\'s back');
    }

    /** @return iterable<string, array{int}> */
    public static function activationFailureStatuses(): iterable
    {
        yield '429 too many requests' => [429];
        yield '500 internal error' => [500];
        yield '503 broker backpressure' => [503];
    }

    public function testActivationFailureWithAnUnparsableBodyStillRaisesWithTheHttpStatus(): void
    {
        $worker = $this->worker([new Response(502, [], '<html>bad gateway</html>')]);

        try {
            $worker->pollOnce(static fn (): null => null);
            self::fail('Expected an ApiException.');
        } catch (ApiException $e) {
            self::assertSame(502, $e->getCode());
        }
    }

    public function testRetryAfterHeaderOfABackpressureResponseIsPreservedForTheCaller(): void
    {
        $worker = $this->worker([new Response(
            429,
            ['Content-Type' => 'application/problem+json', 'Retry-After' => '7'],
            '{"title":"TOO_MANY_REQUESTS","status":429}',
        )]);

        try {
            $worker->pollOnce(static fn (): null => null);
            self::fail('Expected an ApiException.');
        } catch (ApiException $e) {
            $headers = array_change_key_case((array) $e->getResponseHeaders(), CASE_LOWER);
            self::assertSame(['7'], $headers['retry-after'] ?? null);
        }
    }

    public function testActivationTransportFailureSurfacesAsApiException(): void
    {
        $worker = $this->worker([new ConnectException('Connection refused', new Request('POST', 'http://localhost:8080/v2/jobs/activation'))]);

        $this->expectException(ApiException::class);

        $worker->pollOnce(static fn (): null => null);
    }

    public function testSuccessfulActivationStatusOtherThan200WithoutJobsIsTreatedAsEmpty(): void
    {
        // The generated client only names 200 as the documented success status; any other 2xx
        // takes its success fallback, which must still be read as an activation result.
        $worker = $this->worker([new Response(201, ['Content-Type' => 'application/json'], '{"jobs":[]}')]);

        self::assertSame(0, $worker->pollOnce(static fn (): null => null));
    }

    public function testJobsActivatedWithASuccessStatusOtherThan200AreStillProcessed(): void
    {
        $activation = JobFixtures::activation('7');
        $worker = $this->worker([
            new Response(201, $activation->getHeaders(), (string) $activation->getBody()),
            new Response(204),
        ]);

        self::assertSame(1, $worker->pollOnce(static fn (): null => null));
        self::assertStringEndsWith('/jobs/7/completion', $this->http->requests[1]->getUri()->getPath());
    }

    /**
     * @param list<\Psr\Http\Message\ResponseInterface|\Throwable> $queue
     */
    private function worker(array $queue, ?JobWorkerOptions $options = null): JobWorker
    {
        return $this->http->client($queue)->createJobWorker($options ?? new JobWorkerOptions(type: 'greet'));
    }
}
