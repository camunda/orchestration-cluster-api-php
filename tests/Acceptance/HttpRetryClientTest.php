<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\ApiException;
use Camunda\Orchestration\Api\Model\TopologyResponse;
use Camunda\Orchestration\Auth\AuthProviderFactory;
use Camunda\Orchestration\CamundaAsyncClient;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Config\CamundaConfiguration;
use Camunda\Orchestration\Config\ConfigResolver;
use Camunda\Orchestration\Exception\RetryExhaustedException;
use Camunda\Orchestration\Http\HttpClientFactory;
use Camunda\Orchestration\Worker\JobWorkerOptions;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * Retry configuration (`CAMUNDA_SDK_HTTP_RETRY_*`, same names and defaults as the
 * JS SDK) and its wiring into the SDK-built HTTP client (issue #112).
 */
final class HttpRetryClientTest extends TestCase
{
    /** @var list<RequestInterface> */
    private array $requests = [];

    public function testDefaultsMatchTheJsSdk(): void
    {
        $config = ConfigResolver::resolve(environment: []);

        self::assertSame(3, $config->httpRetryMaxAttempts);
        self::assertSame(100, $config->httpRetryBaseDelayMs);
        self::assertSame(2000, $config->httpRetryMaxDelayMs);
    }

    public function testEnvironmentVariablesOverrideTheDefaults(): void
    {
        $config = ConfigResolver::resolve(environment: [
            'CAMUNDA_SDK_HTTP_RETRY_MAX_ATTEMPTS' => '5',
            'CAMUNDA_SDK_HTTP_RETRY_BASE_DELAY_MS' => '250',
            'CAMUNDA_SDK_HTTP_RETRY_MAX_DELAY_MS' => '9000',
        ]);

        self::assertSame(5, $config->httpRetryMaxAttempts);
        self::assertSame(250, $config->httpRetryBaseDelayMs);
        self::assertSame(9000, $config->httpRetryMaxDelayMs);
    }

    public function testExplicitOverridesBeatTheEnvironment(): void
    {
        $config = ConfigResolver::resolve(
            overrides: ['CAMUNDA_SDK_HTTP_RETRY_MAX_ATTEMPTS' => '1'],
            environment: ['CAMUNDA_SDK_HTTP_RETRY_MAX_ATTEMPTS' => '9'],
        );

        self::assertSame(1, $config->httpRetryMaxAttempts);
    }

    public function testAttemptsBelowOneAreClampedToASingleAttempt(): void
    {
        $config = ConfigResolver::resolve(environment: ['CAMUNDA_SDK_HTTP_RETRY_MAX_ATTEMPTS' => '0']);

        self::assertSame(1, $config->httpRetryMaxAttempts);
    }

    #[DataProvider('unusableValues')]
    public function testUnusableValuesFallBackToTheDefaults(string $value): void
    {
        $config = ConfigResolver::resolve(environment: [
            'CAMUNDA_SDK_HTTP_RETRY_MAX_ATTEMPTS' => $value,
            'CAMUNDA_SDK_HTTP_RETRY_BASE_DELAY_MS' => $value,
            'CAMUNDA_SDK_HTTP_RETRY_MAX_DELAY_MS' => $value,
        ]);

        self::assertSame(3, $config->httpRetryMaxAttempts);
        self::assertSame(100, $config->httpRetryBaseDelayMs);
        self::assertSame(2000, $config->httpRetryMaxDelayMs);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function unusableValues(): iterable
    {
        yield 'text' => ['often'];
        yield 'negative' => ['-1'];
        yield 'decimal' => ['1.5'];
        yield 'empty' => [''];
    }

    public function testConfigReferenceDocumentsTheRetryVariablesWithTheirDefaults(): void
    {
        $rows = [];
        foreach (ConfigResolver::configReference() as $row) {
            $rows[$row['variable']] = $row['default'];
        }

        self::assertSame('3', $rows['CAMUNDA_SDK_HTTP_RETRY_MAX_ATTEMPTS'] ?? null);
        self::assertSame('100', $rows['CAMUNDA_SDK_HTTP_RETRY_BASE_DELAY_MS'] ?? null);
        self::assertSame('2000', $rows['CAMUNDA_SDK_HTTP_RETRY_MAX_DELAY_MS'] ?? null);
    }

    public function testSyncOperationRetriesBackpressureThenSucceeds(): void
    {
        $client = $this->syncClient([
            $this->problem(503, 'RESOURCE_EXHAUSTED'),
            new Response(200, ['Content-Type' => 'application/json'], '{"clusterSize":3,"brokers":[]}'),
        ]);

        $topology = $client->getTopology();

        self::assertInstanceOf(TopologyResponse::class, $topology);
        self::assertCount(2, $this->requests);
    }

    public function testNoContentOperationExhaustionReportsFinalStatusAndAttempts(): void
    {
        $client = $this->syncClient(array_fill(0, 3, $this->problem(429, 'RESOURCE_EXHAUSTED')));

        try {
            $client->completeJob('2251799813686200');
            self::fail('Expected RetryExhaustedException');
        } catch (RetryExhaustedException $e) {
            self::assertSame(429, $e->getCode());
            self::assertSame(3, $e->getAttempts());
            self::assertInstanceOf(ApiException::class, $e);
        }

        self::assertCount(3, $this->requests);
    }

    public function testValueReturningOperationExhaustionAlsoThrows(): void
    {
        $client = $this->syncClient(array_fill(0, 3, $this->problem(503, 'RESOURCE_EXHAUSTED')));

        $this->expectException(RetryExhaustedException::class);
        try {
            $client->getTopology();
        } finally {
            self::assertCount(3, $this->requests);
        }
    }

    public function testConfiguredAttemptsAreHonoured(): void
    {
        $client = $this->syncClient(
            array_fill(0, 5, $this->problem(429, 'RESOURCE_EXHAUSTED')),
            ['CAMUNDA_SDK_HTTP_RETRY_MAX_ATTEMPTS' => '5'],
        );

        try {
            $client->completeJob('2251799813686200');
            self::fail('Expected RetryExhaustedException');
        } catch (RetryExhaustedException $e) {
            self::assertSame(5, $e->getAttempts());
        }
        self::assertCount(5, $this->requests);
    }

    public function testSingleAttemptRestoresTheOriginalBehaviour(): void
    {
        $client = $this->syncClient(
            [$this->problem(503, 'RESOURCE_EXHAUSTED')],
            ['CAMUNDA_SDK_HTTP_RETRY_MAX_ATTEMPTS' => '1'],
        );

        $result = $client->getTopology();

        self::assertNotInstanceOf(TopologyResponse::class, $result);
        self::assertCount(1, $this->requests);
    }

    public function testAsyncClientRetriesToo(): void
    {
        $client = CamundaAsyncClient::fromConfiguration(
            $this->config(),
            HttpClientFactory::create($this->config(), AuthProviderFactory::fromConfiguration($this->config()), $this->handler([
                $this->problem(429, 'RESOURCE_EXHAUSTED'),
                new Response(200, ['Content-Type' => 'application/json'], '{"clusterSize":3,"brokers":[]}'),
            ])),
        );

        $topology = $client->getTopology()->wait();

        self::assertInstanceOf(TopologyResponse::class, $topology);
        self::assertCount(2, $this->requests);
    }

    /**
     * Worker activation is a long poll: it must not retry inside the request.
     * The failure reaches the caller on the first response, exactly as before.
     */
    public function testWorkerActivationIsNotRetriedInsideTheLongPoll(): void
    {
        $client = $this->syncClient([
            $this->problem(503, 'RESOURCE_EXHAUSTED'),
            new Response(200, ['Content-Type' => 'application/json'], '{"jobs":[]}'),
        ]);
        $worker = $client->createJobWorker(new JobWorkerOptions(type: 'greet'));

        try {
            $worker->pollOnce(static fn () => null);
            self::fail('Expected ApiException');
        } catch (RetryExhaustedException) {
            self::fail('Activation must not go through the retry loop.');
        } catch (ApiException $e) {
            self::assertSame(503, $e->getCode());
        }

        self::assertCount(1, $this->requests);
    }

    public function testSuppliedHttpClientIsLeftUntouched(): void
    {
        $guzzle = new \GuzzleHttp\Client([
            'handler' => \GuzzleHttp\HandlerStack::create($this->handler([
                $this->problem(429, 'RESOURCE_EXHAUSTED'),
                new Response(200),
            ])),
            'http_errors' => false,
        ]);
        $client = CamundaClient::fromConfiguration($this->config(), $guzzle);

        $client->getTopology();

        self::assertCount(1, $this->requests, 'A caller-supplied Guzzle client owns its own middleware stack.');
    }

    // ------------------------------------------------------------------

    /**
     * @param list<Response> $responses
     * @param array<string, string> $overrides
     */
    private function syncClient(array $responses, array $overrides = []): CamundaClient
    {
        $config = $this->config($overrides);

        return CamundaClient::fromConfiguration(
            $config,
            HttpClientFactory::create($config, AuthProviderFactory::fromConfiguration($config), $this->handler($responses)),
        );
    }

    /**
     * @param array<string, string> $overrides
     */
    private function config(array $overrides = []): CamundaConfiguration
    {
        return ConfigResolver::resolve(
            overrides: $overrides + [
                'CAMUNDA_AUTH_STRATEGY' => 'NONE',
                // No real sleeping in tests: backoff is covered in HttpRetryMiddlewareTest.
                'CAMUNDA_SDK_HTTP_RETRY_BASE_DELAY_MS' => '0',
                'CAMUNDA_SDK_HTTP_RETRY_MAX_DELAY_MS' => '0',
            ],
            environment: [],
        );
    }

    /**
     * @param list<Response> $responses
     */
    private function handler(array $responses): MockHandler
    {
        $this->requests = [];

        return new MockHandler($responses, function (RequestInterface $request): void {
            $this->requests[] = $request;
        });
    }

    private function problem(int $status, string $title): Response
    {
        return new Response(
            $status,
            ['Content-Type' => 'application/problem+json'],
            (string) json_encode(['title' => $title, 'status' => $status, 'detail' => 'detail']),
        );
    }
}
