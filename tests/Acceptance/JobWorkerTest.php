<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Model\ActivatedJobResult;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Config\ConfigResolver;
use Camunda\Orchestration\Worker\JobActionClient;
use Camunda\Orchestration\Worker\JobWorkerOptions;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

final class JobWorkerTest extends TestCase
{
    /** @var list<RequestInterface> */
    private array $requests = [];

    public function testPollOncePropagatesOptionsAndAutoCompletesReturnedVariables(): void
    {
        $worker = $this->worker([
            $this->activationResponse(),
            new Response(204),
        ], new JobWorkerOptions(
            type: 'greet',
            maxJobs: 2,
            timeoutMs: 60_000,
            requestTimeoutMs: 5_000,
            fetchVariables: ['name'],
            workerName: 'php-example-worker',
            tenantIds: ['acme'],
        ));

        $processed = $worker->pollOnce(
            static fn (ActivatedJobResult $job, JobActionClient $action): array => ['greeting' => 'Hello'],
        );

        self::assertSame(1, $processed);
        self::assertCount(2, $this->requests);
        self::assertStringEndsWith('/jobs/activation', $this->requests[0]->getUri()->getPath());
        self::assertSame([
            'type' => 'greet',
            'worker' => 'php-example-worker',
            'timeout' => 60_000,
            'maxJobsToActivate' => 2,
            'fetchVariable' => ['name'],
            'requestTimeout' => 5_000,
            'tenantIds' => ['acme'],
            'tenantFilter' => 'PROVIDED',
        ], $this->jsonBody($this->requests[0]));
        self::assertStringEndsWith('/jobs/2251799813686200/completion', $this->requests[1]->getUri()->getPath());
        self::assertSame(['variables' => ['greeting' => 'Hello']], $this->jsonBody($this->requests[1]));
    }

    public function testExplicitFailurePreventsAutomaticCompletion(): void
    {
        $worker = $this->worker([
            $this->activationResponse(),
            new Response(204),
        ], new JobWorkerOptions(type: 'greet'));

        $worker->pollOnce(
            static function (ActivatedJobResult $job, JobActionClient $action): array {
                $action->fail(
                    retries: 2,
                    errorMessage: 'inventory service unavailable',
                    retryBackOffMs: 5_000,
                );

                return ['mustNotBeCompleted' => true];
            },
        );

        self::assertCount(2, $this->requests);
        self::assertStringEndsWith('/jobs/2251799813686200/failure', $this->requests[1]->getUri()->getPath());
        self::assertSame([
            'retries' => 2,
            'errorMessage' => 'inventory service unavailable',
            'retryBackOff' => 5_000,
        ], $this->jsonBody($this->requests[1]));
    }

    /**
     * @param list<Response> $responses
     */
    private function worker(array $responses, JobWorkerOptions $options): \Camunda\Orchestration\Worker\JobWorker
    {
        $client = CamundaClient::fromConfiguration(
            ConfigResolver::resolve(
                overrides: ['CAMUNDA_AUTH_STRATEGY' => 'NONE'],
                environment: [],
            ),
            $this->httpClient($responses),
        );

        return $client->createJobWorker($options);
    }

    private function activationResponse(): Response
    {
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode([
                'jobs' => [[
                    'type' => 'greet',
                    'processDefinitionId' => 'greet-process',
                    'processDefinitionVersion' => 1,
                    'elementId' => 'greet-task',
                    'customHeaders' => [],
                    'worker' => 'php-example-worker',
                    'retries' => 3,
                    'deadline' => 1_789_636_204_728,
                    'variables' => ['name' => 'worker-regression'],
                    'tenantId' => '<default>',
                    'jobKey' => '2251799813686200',
                    'processInstanceKey' => '2251799813686194',
                    'processDefinitionKey' => '2251799813685676',
                    'elementInstanceKey' => '2251799813686199',
                    'kind' => 'BPMN_ELEMENT',
                    'listenerEventType' => 'UNSPECIFIED',
                    'userTask' => null,
                    'tags' => [],
                    'rootProcessInstanceKey' => '2251799813686194',
                ]],
            ]),
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
