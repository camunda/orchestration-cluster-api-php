<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Api\ClusterApi;
use Camunda\Orchestration\Api\Api\ExportingApi;
use Camunda\Orchestration\CamundaAsyncClient;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Config\ConfigResolver;
use Camunda\Orchestration\Exception\ConfigurationException;
use Camunda\Orchestration\Http\OperationHost;
use GuzzleHttp\Client as GuzzleClient;
use PHPUnit\Framework\TestCase;

final class OperationHostTest extends TestCase
{
    public function testConfiguredHostIsUsedForClusterAdminOperations(): void
    {
        $client = $this->client('https://cluster.example.test:8443/v2');

        $request = $client->api(ClusterApi::class)->getClusterStatusRequest();

        self::assertSame('https://cluster.example.test:8443/cluster/v2/status', (string) $request->getUri());
    }

    public function testExplicitOperationHostVariablesOverrideConfiguredHost(): void
    {
        $client = $this->client('https://cluster.example.test/v2');

        $request = $client->api(ClusterApi::class)->getClusterStatusRequest(
            variables: [
                'schema' => 'http',
                'host' => 'override.example.test',
                'port' => '8081',
            ],
        );

        self::assertSame('http://override.example.test:8081/cluster/v2/status', (string) $request->getUri());
    }

    public function testAsyncClientUsesConfiguredHostForOperationSpecificServer(): void
    {
        $config = ConfigResolver::resolve(
            overrides: [
                'CAMUNDA_REST_ADDRESS' => 'http://cluster.example.test:8080/v2',
                'CAMUNDA_AUTH_STRATEGY' => 'NONE',
            ],
            environment: [],
        );
        $client = CamundaAsyncClient::fromConfiguration($config, new GuzzleClient());

        $request = $client->api(ExportingApi::class)->getClusterExportingStatusRequest();

        self::assertSame('http://cluster.example.test:8080/cluster/v2/exporting', (string) $request->getUri());
    }

    public function testOperationHostRejectsRelativeAddress(): void
    {
        $this->expectException(ConfigurationException::class);

        OperationHost::variables('/v2');
    }

    public function testOperationHostErrorDoesNotLeakConfiguredAddress(): void
    {
        $restAddress = 'https://secret-token@/v2?apiKey=secret-value';

        try {
            OperationHost::variables($restAddress);
            self::fail('Expected configuration exception for invalid rest address.');
        } catch (ConfigurationException $exception) {
            self::assertSame(
                'CAMUNDA_REST_ADDRESS must be an absolute URL with scheme and host to resolve operation-specific hosts.',
                $exception->getMessage(),
            );
            self::assertStringNotContainsString($restAddress, $exception->getMessage());
            self::assertStringNotContainsString('secret-token', $exception->getMessage());
            self::assertStringNotContainsString('secret-value', $exception->getMessage());
        }
    }

    private function client(string $restAddress): CamundaClient
    {
        $config = ConfigResolver::resolve(
            overrides: [
                'CAMUNDA_REST_ADDRESS' => $restAddress,
                'CAMUNDA_AUTH_STRATEGY' => 'NONE',
            ],
            environment: [],
        );

        return CamundaClient::fromConfiguration($config, new GuzzleClient());
    }
}
