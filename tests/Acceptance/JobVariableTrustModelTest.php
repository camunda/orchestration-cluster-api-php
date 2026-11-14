<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\ApiException;
use Camunda\Orchestration\Api\Model\ActivatedJobResult;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Config\ConfigResolver;
use Camunda\Orchestration\Worker\JobActionClient;
use Camunda\Orchestration\Worker\JobWorker;
use Camunda\Orchestration\Worker\JobWorkerOptions;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Issue #113: pin the documented trust model for server-supplied job variables.
 *
 * Neither job variables nor custom headers are shaped by the worker: variables carry
 * whatever is in scope for the task at activation, and custom headers come from the
 * deployed process model. The SDK therefore treats both as untrusted input. The README
 * states what it does and does not do about that; these tests keep the statement true.
 */
final class JobVariableTrustModelTest extends TestCase
{
    /** The depth at which `json_decode`'s default limit of 512 rejects the response. */
    private const JSON_DECODE_DEPTH_LIMIT = 512;

    /**
     * Documented position: nesting is bounded by `json_decode`'s default depth of 512,
     * and the SDK adds no recursion of its own on the way to the handler.
     */
    public function testADeeplyNestedVariablePayloadReachesTheHandlerIntact(): void
    {
        $depth = 400;
        $worker = $this->worker([
            $this->activationResponse(['payload' => self::nest($depth)]),
            new Response(204),
        ]);

        $seen = null;
        $processed = $worker->pollOnce(
            static function (ActivatedJobResult $job, JobActionClient $action) use (&$seen): array {
                $seen = $job->getVariables();

                return [];
            },
        );

        self::assertSame(1, $processed);
        self::assertIsArray($seen);
        self::assertArrayHasKey('payload', $seen);
        self::assertSame($depth, self::measureDepth($seen['payload']));
    }

    /**
     * Beyond the decoder's depth limit the response is rejected with an ordinary
     * `ApiException`. The point of the test is the absence of a stack overflow, a
     * fatal error or a silently truncated payload reaching the handler.
     */
    #[DataProvider('depthsBeyondTheDecoderLimit')]
    public function testNestingBeyondTheDecoderLimitFailsSafelyInsteadOfRecursingWithoutBound(int $depth): void
    {
        self::assertGreaterThan(self::JSON_DECODE_DEPTH_LIMIT, $depth);

        $worker = $this->worker([$this->activationResponse(['payload' => self::nest($depth)])]);

        $reachedHandler = false;

        try {
            $worker->pollOnce(
                static function (ActivatedJobResult $job, JobActionClient $action) use (&$reachedHandler): array {
                    $reachedHandler = true;

                    return [];
                },
            );
            self::fail('Expected the over-deep payload to be rejected by the decoder.');
        } catch (ApiException $exception) {
            self::assertStringContainsString('JSON decoding', $exception->getMessage());
        }

        self::assertFalse($reachedHandler, 'A payload the decoder rejected must never reach a handler.');
    }

    /**
     * @return iterable<string, array{int}>
     */
    public static function depthsBeyondTheDecoderLimit(): iterable
    {
        yield 'just over the limit' => [600];
        yield 'far over the limit' => [5_000];
    }

    /**
     * Variables are hydrated from `json_decode` only. A payload that names a class, or
     * carries a PHP serialization string, arrives as JSON-shaped values: the top level
     * is an array, nested JSON objects are `stdClass`, JSON arrays are PHP arrays and
     * everything else is a scalar. No other class is ever instantiated from a payload,
     * and nothing in it is passed to `unserialize()`.
     */
    public function testAHostilePayloadIsDeserializedIntoJsonShapedValuesOnly(): void
    {
        $hostile = [
            '__CLASS__' => 'Camunda\\Orchestration\\Config\\CamundaConfiguration',
            '@type' => 'SplFileObject',
            'serialized' => 'O:8:"stdClass":1:{s:4:"evil";s:3:"yes";}',
            'nested' => ['__PHP_Incomplete_Class_Name' => 'Evil', 'args' => ['/etc/passwd']],
        ];

        $worker = $this->worker([
            $this->activationResponse($hostile, customHeaders: $hostile),
            new Response(204),
        ]);

        $variables = null;
        $headers = null;
        $worker->pollOnce(
            static function (ActivatedJobResult $job, JobActionClient $action) use (&$variables, &$headers): array {
                $variables = $job->getVariables();
                $headers = $job->getCustomHeaders();

                return [];
            },
        );

        self::assertIsArray($variables);
        self::assertIsArray($headers);

        foreach ([$variables, $headers] as $payload) {
            self::assertSame(
                'Camunda\\Orchestration\\Config\\CamundaConfiguration',
                $payload['__CLASS__'],
                'A class name in the payload stays a string.',
            );
            self::assertSame(
                'O:8:"stdClass":1:{s:4:"evil";s:3:"yes";}',
                $payload['serialized'],
                'A PHP serialization string stays a string — nothing calls unserialize().',
            );
            self::assertSame(
                ['stdClass'],
                array_values(array_unique(self::classesIn($payload))),
                'A nested JSON object may only ever become a stdClass.',
            );
        }
    }

    /**
     * The SDK never appends a payload to an exception message it raises: a failing
     * terminal action reports the server's problem detail, not the variables that were
     * sent. The raw response body is still attached to the generated `ApiException`,
     * which is why the README tells you to log the message rather than the exception.
     */
    public function testTheSdkAddsNoVariableContentToAnExceptionMessage(): void
    {
        $sentinel = 'sentinel-variable-value-4d9c';
        $worker = $this->worker([
            $this->activationResponse(['secretish' => $sentinel]),
            new Response(
                400,
                ['Content-Type' => 'application/problem+json'],
                '{"title":"INVALID_ARGUMENT","detail":"variables rejected","status":400}',
            ),
        ]);

        try {
            $worker->pollOnce(
                static fn (ActivatedJobResult $job, JobActionClient $action): array => $job->getVariables(),
            );
            self::fail('Expected the failing completion to throw an ApiException.');
        } catch (ApiException $exception) {
            self::assertSame(400, $exception->getCode());
            self::assertStringContainsString('/v2/jobs/1/completion', $exception->getMessage());
            self::assertStringNotContainsString($sentinel, $exception->getMessage());
        }
    }

    /**
     * The documented caveat, pinned so it cannot be quietly overstated: an activation
     * that fails after the body has been read still carries that body — the variables —
     * on the generated exception, even though the SDK's own message does not.
     */
    public function testTheRawResponseBodyIsStillAttachedToAFailedActivation(): void
    {
        $sentinel = 'sentinel-variable-value-7e1a';
        $worker = $this->worker([$this->activationResponse(['payload' => self::nest(600)])]);

        try {
            $worker->pollOnce(
                static fn (ActivatedJobResult $job, JobActionClient $action): array => ['echo' => $sentinel],
            );
            self::fail('Expected the over-deep payload to be rejected by the decoder.');
        } catch (ApiException $exception) {
            self::assertStringNotContainsString('"level"', $exception->getMessage());

            $body = $exception->getResponseBody();
            self::assertIsString($body);
            self::assertStringContainsString('"level"', $body);
        }
    }

    /**
     * Each dispatched job gets its own `JobActionClient` and its own variables, so the
     * SDK never shares or mutates a payload across jobs. This is object isolation, not
     * sandboxing — a stateful handler can still carry its own data between jobs.
     */
    public function testVariablesAreNotSharedBetweenJobsOfTheSameBatch(): void
    {
        $worker = $this->worker([
            $this->activationResponse(['owner' => 'first'], jobKey: '1', extraJob: ['owner' => 'second']),
            new Response(204),
            new Response(204),
        ]);

        /** @var list<array<string, mixed>> $seen */
        $seen = [];
        /** @var list<int> $jobIds */
        $jobIds = [];
        /** @var list<int> $actionIds */
        $actionIds = [];
        /** @var list<ActivatedJobResult> $jobs */
        $jobs = [];
        /** @var list<JobActionClient> $actions */
        $actions = [];

        $worker->pollOnce(
            static function (ActivatedJobResult $job, JobActionClient $action) use (
                &$seen,
                &$jobIds,
                &$actionIds,
                &$jobs,
                &$actions,
            ): array {
                // Hold a reference to each object before taking its id: PHP reuses the
                // id of an object that has already been freed.
                $jobs[] = $job;
                $actions[] = $action;
                $jobIds[] = spl_object_id($job);
                $actionIds[] = spl_object_id($action);

                $seen[] = $job->getVariables();

                // Write through the model, not into the copy getVariables() returns —
                // mutating a copy would prove nothing about isolation.
                $job->setVariables(['owner' => 'mutated by the handler']);

                return [];
            },
        );

        self::assertCount(2, $seen);
        self::assertSame(['owner' => 'first'], $seen[0]);
        self::assertSame(
            ['owner' => 'second'],
            $seen[1],
            "The first handler's mutation must not be visible to the second job.",
        );

        self::assertCount(2, array_unique($jobIds), 'Each job must get its own ActivatedJobResult.');
        self::assertCount(2, array_unique($actionIds), 'Each job must get its own JobActionClient.');

        // The second job's payload was never touched by the first handler call.
        self::assertSame('2', (string) $jobs[1]->getJobKey());
        self::assertNotSame($jobs[0], $jobs[1]);
    }

    /**
     * @return array<string, mixed>|list<mixed>
     */
    private static function nest(int $depth): array
    {
        /** @var array<string, mixed> $value */
        $value = ['leaf' => 'value'];
        for ($i = 0; $i < $depth; ++$i) {
            $value = ['level' => $value];
        }

        return $value;
    }

    /**
     * Walk iteratively — a recursive measurement would hit the very limit under test.
     */
    private static function measureDepth(mixed $value): int
    {
        $depth = 0;
        while (true) {
            if (is_array($value) && array_key_exists('level', $value)) {
                /** @var mixed $value */
                $value = $value['level'];
            } elseif ($value instanceof \stdClass && property_exists($value, 'level')) {
                /** @var mixed $value */
                $value = $value->level;
            } else {
                return $depth;
            }
            ++$depth;
        }
    }

    /**
     * Every class reachable in a decoded payload, found iteratively so the walk itself
     * cannot blow the stack.
     *
     * @param array<array-key, mixed> $value
     * @return list<string>
     */
    private static function classesIn(array $value): array
    {
        $found = [];
        $stack = [$value];
        while ($stack !== []) {
            /** @var mixed $current */
            $current = array_pop($stack);
            if (is_object($current)) {
                $found[] = $current::class;
                foreach (get_object_vars($current) as $item) {
                    $stack[] = $item;
                }
                continue;
            }
            if (is_array($current)) {
                foreach ($current as $item) {
                    $stack[] = $item;
                }
            }
        }

        return $found;
    }

    /**
     * @param list<Response> $responses
     */
    private function worker(array $responses): JobWorker
    {
        $client = CamundaClient::fromConfiguration(
            ConfigResolver::resolve(
                overrides: ['CAMUNDA_AUTH_STRATEGY' => 'NONE'],
                environment: [],
            ),
            $this->httpClient($responses),
        );

        return $client->createJobWorker(new JobWorkerOptions(type: 'greet'));
    }

    /**
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $customHeaders
     * @param array<string, mixed>|null $extraJob
     */
    private function activationResponse(
        array $variables,
        array $customHeaders = [],
        string $jobKey = '1',
        ?array $extraJob = null,
    ): Response {
        $jobs = [self::job($jobKey, $variables, $customHeaders)];
        if ($extraJob !== null) {
            $jobs[] = self::job('2', $extraJob, $customHeaders);
        }

        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode(['jobs' => $jobs], JSON_THROW_ON_ERROR, depth: 8192),
        );
    }

    /**
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $customHeaders
     * @return array<string, mixed>
     */
    private static function job(string $jobKey, array $variables, array $customHeaders): array
    {
        return [
            'type' => 'greet',
            'processDefinitionId' => 'greet-process',
            'processDefinitionVersion' => 1,
            'elementId' => 'greet-task',
            'customHeaders' => $customHeaders,
            'worker' => 'php-example-worker',
            'retries' => 3,
            'deadline' => 1_789_636_204_728,
            'variables' => $variables,
            'tenantId' => '<default>',
            'jobKey' => $jobKey,
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

    /**
     * @param list<Response> $responses
     */
    private function httpClient(array $responses): GuzzleClient
    {
        return new GuzzleClient([
            'handler' => HandlerStack::create(new MockHandler($responses)),
            'http_errors' => false,
        ]);
    }
}
