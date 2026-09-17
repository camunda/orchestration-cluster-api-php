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

    public function testConfiguredPathPrefixIsPreservedForClusterAdminOperations(): void
    {
        $client = $this->client('https://cluster.example.test/gateway/v2');

        $request = $client->api(ClusterApi::class)->getClusterStatusRequest();

        self::assertSame('https://cluster.example.test/gateway/cluster/v2/status', (string) $request->getUri());
    }

    public function testMutatedApiConfigHostIsUsedForClusterAdminOperations(): void
    {
        $client = $this->client('https://cluster.example.test/v2');
        $api = $client->api(ClusterApi::class);
        $api->getConfig()->setHost('https://override.example.test/proxy/v2');

        $request = $api->getClusterStatusRequest();

        self::assertSame('https://override.example.test/proxy/cluster/v2/status', (string) $request->getUri());
    }

    public function testExplicitOperationHostVariablesStillOverrideMutatedHost(): void
    {
        $client = $this->client('https://cluster.example.test/v2');
        $api = $client->api(ClusterApi::class);
        $api->getConfig()
            ->setHost('https://override.example.test/proxy/v2')
            ->setOperationHostVariables([
                'schema' => 'http',
                'host' => 'manual.example.test',
                'port' => '8081',
                'basePath' => '/manual',
            ]);

        $request = $api->getClusterStatusRequest();

        self::assertSame('http://manual.example.test:8081/manual/cluster/v2/status', (string) $request->getUri());
    }

    public function testExplicitOperationHostVariablesOverrideRelativeMutatedHost(): void
    {
        $client = $this->client('https://cluster.example.test/v2');
        $api = $client->api(ClusterApi::class);
        $api->getConfig()
            ->setHost('/proxy/v2')
            ->setOperationHostVariables([
                'schema' => 'http',
                'host' => 'manual.example.test',
                'port' => '8081',
                'basePath' => '/manual',
            ]);

        $request = $api->getClusterStatusRequest();

        self::assertSame('http://manual.example.test:8081/manual/cluster/v2/status', (string) $request->getUri());
    }

    public function testRelativeMutatedHostKeepsPathPrefixWhenAbsoluteOverrideOmitsBasePath(): void
    {
        $client = $this->client('https://cluster.example.test/v2');
        $api = $client->api(ClusterApi::class);
        $api->getConfig()
            ->setHost('/proxy/v2')
            ->setOperationHostVariables([
                'schema' => 'http',
                'host' => 'manual.example.test',
                'port' => '8081',
            ]);

        $request = $api->getClusterStatusRequest();

        self::assertSame('http://manual.example.test:8081/proxy/cluster/v2/status', (string) $request->getUri());
    }

    public function testPathRelativeMutatedHostKeepsPathPrefixWhenAbsoluteOverrideOmitsBasePath(): void
    {
        $client = $this->client('https://cluster.example.test/v2');
        $api = $client->api(ClusterApi::class);
        $api->getConfig()
            ->setHost('proxy/v2')
            ->setOperationHostVariables([
                'schema' => 'http',
                'host' => 'manual.example.test',
                'port' => '8081',
            ]);

        $request = $api->getClusterStatusRequest();

        self::assertSame('http://manual.example.test:8081/proxy/cluster/v2/status', (string) $request->getUri());
    }

    public function testVersionOnlyPathRelativeHostDoesNotBecomeBasePathPrefix(): void
    {
        $client = $this->client('https://cluster.example.test/v2');
        $api = $client->api(ClusterApi::class);
        $api->getConfig()
            ->setHost('v2')
            ->setOperationHostVariables([
                'schema' => 'http',
                'host' => 'manual.example.test',
                'port' => '8081',
            ]);

        $request = $api->getClusterStatusRequest();

        self::assertSame('http://manual.example.test:8081/cluster/v2/status', (string) $request->getUri());
    }

    public function testRelativeMutatedHostStaysRelativeForClusterAdminOperations(): void
    {
        $client = $this->client('https://cluster.example.test/v2');
        $api = $client->api(ClusterApi::class);
        $api->getConfig()->setHost('/proxy/v2');

        $request = $api->getClusterStatusRequest();

        self::assertSame('/proxy/cluster/v2/status', (string) $request->getUri());
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

    public function testAsyncClientPreservesConfiguredPathPrefixForOperationSpecificServer(): void
    {
        $config = ConfigResolver::resolve(
            overrides: [
                'CAMUNDA_REST_ADDRESS' => 'https://cluster.example.test/proxy/v2',
                'CAMUNDA_AUTH_STRATEGY' => 'NONE',
            ],
            environment: [],
        );
        $client = CamundaAsyncClient::fromConfiguration($config, new GuzzleClient());

        $request = $client->api(ExportingApi::class)->getClusterExportingStatusRequest();

        self::assertSame('https://cluster.example.test/proxy/cluster/v2/exporting', (string) $request->getUri());
    }

    public function testPerCallOperationHostVariablesOverrideRelativeMutatedHost(): void
    {
        $client = $this->client('https://cluster.example.test/v2');
        $api = $client->api(ClusterApi::class);
        $api->getConfig()->setHost('/proxy/v2');

        $request = $api->getClusterStatusRequest(
            variables: [
                'schema' => 'http',
                'host' => 'override.example.test',
                'port' => '8082',
                'basePath' => '/override',
            ],
        );

        self::assertSame('http://override.example.test:8082/override/cluster/v2/status', (string) $request->getUri());
    }

    public function testMalformedMutatedHostStillFailsFastForClusterAdminOperations(): void
    {
        $client = $this->client('https://cluster.example.test/v2');
        $api = $client->api(ClusterApi::class);
        $api->getConfig()->setHost('https://secret-token@/v2?apiKey=secret-value');

        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage(
            'CAMUNDA_REST_ADDRESS must be an absolute URL with scheme and host to resolve operation-specific hosts.',
        );

        $api->getClusterStatusRequest();
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
