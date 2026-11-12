<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\ApiException;
use Camunda\Orchestration\Api\Model\ProblemDetail;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Config\ConfigResolver;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Regression tests for issue #94: every sync no-content operation
 * (`completeJob`, `failJob`, `cancelProcessInstance`, deletes, …) used to
 * return `null` for HTTP 4xx/5xx responses — its generated `*WithHttpInfo`
 * method returned `[null, $statusCode, $headers]` without checking the status,
 * so callers read an error as "no ProblemDetail, it worked". Every such
 * operation must now throw an `ApiException`, enriched with the declared
 * `ProblemDetail` where the spec declares one.
 *
 * The operation table is derived from the generated API classes themselves:
 * every sync `*WithHttpInfo` method whose success path returns
 * `[null, $statusCode, ...]` is covered, so a future spec change that adds a
 * no-content operation automatically joins the test.
 */
final class SyncNoContentHttpErrorHandlingTest extends TestCase
{
    /**
     * Every no-content operation throws on a 4xx, and the exception carries
     * the deserialized ProblemDetail the spec declares for the status. The
     * 4xx used per operation is one the spec declares for it (some operations
     * declare only 401/403, most also 404).
     */
    #[DataProvider('noContentOperations')]
    public function testNoContentOperationThrowsOn4xx(string $operation): void
    {
        $status = self::declaredClientErrorFor($operation);
        $client = $this->syncClient([
            new Response(
                $status,
                ['Content-Type' => 'application/problem+json'],
                sprintf('{"title":"CLIENT_ERROR","status":%d,"detail":"No such resource"}', $status),
            ),
        ]);

        $exception = $this->catchApiException(static fn () => $client->{$operation}(...self::argumentsFor($operation)));

        self::assertSame($status, $exception->getCode(), "$operation must surface the HTTP status");
        $body = $exception->getResponseBody();
        self::assertIsString($body);
        self::assertStringContainsString('CLIENT_ERROR', $body, "$operation must carry the raw response body");
        if (in_array($status, self::declaredProblemStatuses($operation), true)) {
            $problem = $exception->getResponseObject();
            self::assertInstanceOf(ProblemDetail::class, $problem, "$operation must deserialize the declared ProblemDetail");
            self::assertSame('CLIENT_ERROR', $problem->getTitle());
            self::assertSame($status, $problem->getStatus());
        }
    }

    /**
     * Every no-content operation throws on a 5xx, even when the error body is
     * not a problem document.
     */
    #[DataProvider('noContentOperations')]
    public function testNoContentOperationThrowsOn5xx(string $operation): void
    {
        $client = $this->syncClient([
            new Response(502, ['Content-Type' => 'text/plain'], 'Bad Gateway'),
        ]);

        $exception = $this->catchApiException(static fn () => $client->{$operation}(...self::argumentsFor($operation)));

        self::assertSame(502, $exception->getCode(), "$operation must surface the HTTP status");
        self::assertSame('Bad Gateway', $exception->getResponseBody());
    }

    /**
     * A 2xx response on a no-content operation still returns null (success).
     */
    #[DataProvider('noContentOperations')]
    public function testNoContentOperationStillSucceedsOn2xx(string $operation): void
    {
        $client = $this->syncClient([new Response(204)]);

        $result = $client->{$operation}(...self::argumentsFor($operation));

        self::assertNull($result, "$operation must keep returning null on success");
    }

    /**
     * The exact failure mode from the issue: a 415 on job completion used to
     * be counted as a completed job.
     */
    public function testCompleteJobThrowsOn415(): void
    {
        $client = $this->syncClient([
            new Response(415, ['Content-Type' => 'application/problem+json'], '{"title":"UNSUPPORTED_MEDIA_TYPE","status":415}'),
        ]);

        $exception = $this->catchApiException(static fn () => $client->completeJob('2251799813686200'));

        self::assertSame(415, $exception->getCode());
    }

    /**
     * A declared error status whose body does not match ProblemDetail must not
     * replace the ApiException with a deserialization error: enrichment is
     * best-effort and the original status, headers and raw body survive.
     */
    #[DataProvider('malformedErrorBodies')]
    public function testCompleteJobKeepsApiExceptionWhenErrorBodyIsMalformed(string $body): void
    {
        $client = $this->syncClient([
            new Response(503, ['Content-Type' => 'application/json', 'X-Trace' => 'abc'], $body),
        ]);

        $exception = $this->catchApiException(static fn () => $client->completeJob('2251799813686200'));

        self::assertSame(503, $exception->getCode());
        self::assertSame($body, $exception->getResponseBody());
        self::assertSame(['abc'], ($exception->getResponseHeaders() ?? [])['X-Trace'] ?? null);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function malformedErrorBodies(): iterable
    {
        yield 'mismatched shape' => ['{"status":"unavailable"}'];
        yield 'non-json' => ['<html>Service Unavailable</html>'];
    }

    /**
     * The worker's auto-complete path must surface a failed completion instead
     * of reporting the job as done.
     */
    public function testJobWorkerAutoCompleteSurfacesCompletionFailure(): void
    {
        $client = $this->syncClient([
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                (string) json_encode(['jobs' => [[
                    'type' => 'greet',
                    'processDefinitionId' => 'greet-process',
                    'processDefinitionVersion' => 1,
                    'elementId' => 'greet-task',
                    'customHeaders' => [],
                    'worker' => 'php-example-worker',
                    'retries' => 3,
                    'deadline' => 1_789_636_204_728,
                    'variables' => [],
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
                ]]]),
            ),
            new Response(
                404,
                ['Content-Type' => 'application/problem+json'],
                '{"title":"NOT_FOUND","status":404,"detail":"Job already timed out"}',
            ),
        ]);

        $worker = $client->createJobWorker(new \Camunda\Orchestration\Worker\JobWorkerOptions(type: 'greet'));

        $exception = $this->catchApiException(
            static fn () => $worker->pollOnce(static fn () => ['greeting' => 'Hello']),
        );

        self::assertSame(404, $exception->getCode());
        self::assertInstanceOf(ProblemDetail::class, $exception->getResponseObject());
    }

    /**
     * Every sync no-content operation on the flat facade, derived from the
     * generated API classes: a `*WithHttpInfo` method whose success path
     * returns `[null, $statusCode, ...]` is a no-content operation.
     *
     * @return iterable<string, array{0: string}>
     */
    public static function noContentOperations(): iterable
    {
        $facade = new \ReflectionClass(CamundaClient::class);
        foreach (glob(__DIR__ . '/../../generated/src/Api/*Api.php') ?: [] as $apiFile) {
            $source = (string) file_get_contents($apiFile);
            $matches = [];
            preg_match_all(
                '/public function (\w+WithHttpInfo)\(.*?\n    \{\n(?<body>.*?)(?=\n    \/\*\*|\n    public function|\n\})/s',
                $source,
                $matches,
            );
            foreach ($matches[1] as $i => $withHttpInfo) {
                if (!str_contains($matches['body'][$i], 'return [null, $statusCode, $response->getHeaders()];')) {
                    continue;
                }
                $operation = substr($withHttpInfo, 0, -strlen('WithHttpInfo'));
                if (!$facade->hasMethod($operation)) {
                    throw new \RuntimeException("Facade is missing no-content operation $operation");
                }
                yield $operation => [$operation];
            }
        }
    }

    /**
     * The 4xx status the spec declares for the operation (its generated
     * `catch (ApiException $e)` switch has a `case` deserializing a
     * ProblemDetail for it). Prefers 404, falls back to 400/401/403.
     */
    private static function declaredClientErrorFor(string $operation): int
    {
        $declared = self::declaredProblemStatuses($operation);
        foreach ([404, 400, 401, 403, 409] as $candidate) {
            if (in_array($candidate, $declared, true)) {
                return $candidate;
            }
        }
        // The spec declares no 4xx for this operation (e.g. resetClock,
        // getStatus); the status check must still throw, just without a
        // declared ProblemDetail to enrich the exception with.
        return 418;
    }

    /**
     * The statuses for which the operation's generated `catch` switch
     * deserializes a ProblemDetail.
     *
     * @return list<int>
     */
    private static function declaredProblemStatuses(string $operation): array
    {
        foreach (glob(__DIR__ . '/../../generated/src/Api/*Api.php') ?: [] as $apiFile) {
            $source = (string) file_get_contents($apiFile);
            $start = strpos($source, "function {$operation}WithHttpInfo(");
            if ($start === false) {
                continue;
            }
            $catch = strpos($source, 'catch (ApiException $e)', $start);
            $end = strpos($source, "\n    /**", $start);
            if ($catch === false || ($end !== false && $catch > $end)) {
                return [];
            }
            $switch = substr($source, $catch, ($end !== false ? $end : $start + 20000) - $catch);
            $cases = [];
            preg_match_all('/case (\d+):/', $switch, $cases);
            return array_map(intval(...), $cases[1]);
        }
        return [];
    }

    /**
     * Minimal valid arguments for every no-content operation, keyed by
     * operation name. Path parameters are strings (semantic value objects
     * accept their string form); body models are constructed empty.
     *
     * @return list<mixed>
     */
    private static function argumentsFor(string $operation): array
    {
        $facade = new \ReflectionClass(CamundaClient::class);
        $arguments = [];
        foreach ($facade->getMethod($operation)->getParameters() as $parameter) {
            if ($parameter->isDefaultValueAvailable()) {
                break;
            }
            $type = $parameter->getType();
            if ($type instanceof \ReflectionUnionType) {
                // Union body parameter: any member model satisfies it.
                $member = array_values(array_filter(
                    $type->getTypes(),
                    static fn (\ReflectionType $t): bool => $t instanceof \ReflectionNamedType && !in_array($t->getName(), ['null', 'string', 'int', 'array'], true),
                ))[0] ?? null;
                if ($member instanceof \ReflectionNamedType && class_exists($member->getName())) {
                    $arguments[] = new ($member->getName())();
                    continue;
                }
                throw new \RuntimeException("Cannot synthesize union argument \${$parameter->getName()} for $operation");
            }
            $name = $type instanceof \ReflectionNamedType ? $type->getName() : null;
            if ($name === 'string') {
                $arguments[] = '2251799813686200';
                continue;
            }
            if ($name === 'int') {
                $arguments[] = 2251799813686200;
                continue;
            }
            if (is_string($name) && class_exists($name)) {
                $arguments[] = new $name();
                continue;
            }
            throw new \RuntimeException("Cannot synthesize argument \${$parameter->getName()} for $operation");
        }
        return $arguments;
    }

    private function catchApiException(callable $operation): ApiException
    {
        try {
            $operation();
        } catch (ApiException $exception) {
            return $exception;
        }

        self::fail('Expected an ApiException, but the operation returned successfully.');
    }

    /**
     * @param list<Response> $responses
     */
    private function syncClient(array $responses): CamundaClient
    {
        $stack = HandlerStack::create(new MockHandler($responses));

        return CamundaClient::fromConfiguration(
            ConfigResolver::resolve(
                overrides: ['CAMUNDA_AUTH_STRATEGY' => 'NONE'],
                environment: [],
            ),
            new GuzzleClient(['handler' => $stack, 'http_errors' => false]),
        );
    }
}
