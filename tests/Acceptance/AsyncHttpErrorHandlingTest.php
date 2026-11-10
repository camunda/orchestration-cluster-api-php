<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\ApiException;
use Camunda\Orchestration\Api\Model\JobActivationRequest;
use Camunda\Orchestration\Api\Model\ProblemDetail;
use Camunda\Orchestration\Api\Model\ProcessInstanceCreationInstructionByKey;
use Camunda\Orchestration\CamundaAsyncClient;
use Camunda\Orchestration\Config\ConfigResolver;
use Camunda\Orchestration\Semantic\ProcessDefinitionKey;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Regression tests for issue #85: the async client used to fulfil its promises
 * for HTTP 4xx/5xx responses, deserializing the problem body into the
 * operation's result model (e.g. a `503` broker backpressure rejection surfaced
 * as a successful, empty `CreateProcessInstanceResult`). Every async operation
 * must now reject with an `ApiException`, matching the sync client.
 */
final class AsyncHttpErrorHandlingTest extends TestCase
{
    public function testCreateProcessInstanceRejectsOn503Backpressure(): void
    {
        $client = $this->asyncClient([
            new Response(
                503,
                ['Content-Type' => 'application/problem+json'],
                '{"title":"RESOURCE_EXHAUSTED","status":503,"detail":"Broker is under backpressure"}',
            ),
        ]);

        $instruction = new ProcessInstanceCreationInstructionByKey();
        $instruction->setProcessDefinitionKey(new ProcessDefinitionKey('2251799813685676'));

        $exception = $this->awaitRejection(
            static fn () => $client->createProcessInstance($instruction),
        );

        self::assertSame(503, $exception->getCode());
        $body = $exception->getResponseBody();
        self::assertIsString($body);
        self::assertStringContainsString('RESOURCE_EXHAUSTED', $body);
        $problem = $exception->getResponseObject();
        self::assertInstanceOf(ProblemDetail::class, $problem);
        self::assertSame('RESOURCE_EXHAUSTED', $problem->getTitle());
        self::assertSame(503, $problem->getStatus());
    }

    public function testCreateProcessInstanceRejectsOn400(): void
    {
        $client = $this->asyncClient([
            new Response(
                400,
                ['Content-Type' => 'application/problem+json'],
                '{"title":"INVALID_ARGUMENT","status":400,"detail":"No process definition found"}',
            ),
        ]);

        $instruction = new ProcessInstanceCreationInstructionByKey();
        $instruction->setProcessDefinitionKey(new ProcessDefinitionKey('2251799813685676'));

        $exception = $this->awaitRejection(
            static fn () => $client->createProcessInstance($instruction),
        );

        self::assertSame(400, $exception->getCode());
        self::assertInstanceOf(ProblemDetail::class, $exception->getResponseObject());
    }

    public function testActivateJobsRejectsOn503(): void
    {
        $client = $this->asyncClient([
            new Response(
                503,
                ['Content-Type' => 'application/problem+json'],
                '{"title":"RESOURCE_EXHAUSTED","status":503}',
            ),
        ]);

        $request = new JobActivationRequest();
        $request->setType('greet');
        $request->setMaxJobsToActivate(1);
        $request->setTimeout(10_000);

        $exception = $this->awaitRejection(
            static fn () => $client->activateJobs($request),
        );

        self::assertSame(503, $exception->getCode());
        $problem = $exception->getResponseObject();
        self::assertInstanceOf(ProblemDetail::class, $problem);
        self::assertSame('RESOURCE_EXHAUSTED', $problem->getTitle());
    }

    public function testNonProblemErrorBodyStillRejectsWithRawBody(): void
    {
        $client = $this->asyncClient([
            new Response(502, ['Content-Type' => 'text/plain'], 'Bad Gateway'),
        ]);

        $request = new JobActivationRequest();
        $request->setType('greet');
        $request->setMaxJobsToActivate(1);
        $request->setTimeout(10_000);

        $exception = $this->awaitRejection(
            static fn () => $client->activateJobs($request),
        );

        self::assertSame(502, $exception->getCode());
        self::assertSame('Bad Gateway', $exception->getResponseBody());
        self::assertNull($exception->getResponseObject());
    }

    public function testSuccessfulAsyncResponseStillResolves(): void
    {
        $client = $this->asyncClient([
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                (string) json_encode([
                    'processDefinitionKey' => '2251799813685676',
                    'processDefinitionId' => 'greet-process',
                    'processDefinitionVersion' => 1,
                    'processInstanceKey' => '2251799813686194',
                    'tenantId' => '<default>',
                ]),
            ),
        ]);

        $instruction = new ProcessInstanceCreationInstructionByKey();
        $instruction->setProcessDefinitionKey(new ProcessDefinitionKey('2251799813685676'));

        $result = $client->createProcessInstance($instruction)->wait();

        self::assertInstanceOf(\Camunda\Orchestration\Api\Model\CreateProcessInstanceResult::class, $result);
        self::assertSame('2251799813686194', $result->getProcessInstanceKey()->value());
    }

    /**
     * @param list<Response> $responses
     */
    private function asyncClient(array $responses): CamundaAsyncClient
    {
        $stack = HandlerStack::create(new MockHandler($responses));

        return CamundaAsyncClient::fromConfiguration(
            ConfigResolver::resolve(
                overrides: ['CAMUNDA_AUTH_STRATEGY' => 'NONE'],
                environment: [],
            ),
            new GuzzleClient(['handler' => $stack, 'http_errors' => false]),
        );
    }

    /**
     * @param callable(): \GuzzleHttp\Promise\PromiseInterface $operation
     */
    private function awaitRejection(callable $operation): ApiException
    {
        try {
            $operation()->wait();
        } catch (ApiException $exception) {
            return $exception;
        }

        self::fail('Expected the promise to reject with an ApiException, but it fulfilled.');
    }
}
