<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Config\ConfigResolver;
use PHPUnit\Framework\TestCase;

final class ConfigResolverTest extends TestCase
{
    public function testConfigReferenceCoversEverySupportedVariable(): void
    {
        $documented = array_map(
            static fn (array $row): string => $row['variable'],
            ConfigResolver::configReference(),
        );

        sort($documented);
        $expected = ConfigResolver::CONFIG_KEYS;
        sort($expected);

        self::assertSame($expected, $documented, 'configReference() must document exactly the CONFIG_KEYS variables.');
    }

    public function testNormalizesRestAddressWithV2Suffix(): void
    {
        $config = ConfigResolver::resolve(
            environment: ['CAMUNDA_REST_ADDRESS' => 'http://localhost:8080'],
        );

        self::assertSame('http://localhost:8080/v2', $config->restAddress);
    }

    public function testDoesNotDoubleAppendV2(): void
    {
        $config = ConfigResolver::resolve(
            environment: ['CAMUNDA_REST_ADDRESS' => 'http://localhost:8080/v2'],
        );

        self::assertSame('http://localhost:8080/v2', $config->restAddress);
    }

    public function testAutoDetectsOAuthFromClientCredentials(): void
    {
        $config = ConfigResolver::resolve(environment: [
            'CAMUNDA_REST_ADDRESS' => 'http://localhost:8080',
            'CAMUNDA_CLIENT_ID' => 'zeebe',
            'CAMUNDA_CLIENT_SECRET' => 'secret',
        ]);

        self::assertSame('OAUTH', $config->authStrategy);
        self::assertSame('zeebe', $config->clientId);
    }

    public function testAutoDetectsBasicFromCredentials(): void
    {
        $config = ConfigResolver::resolve(environment: [
            'CAMUNDA_REST_ADDRESS' => 'http://localhost:8080',
            'CAMUNDA_BASIC_AUTH_USERNAME' => 'demo',
            'CAMUNDA_BASIC_AUTH_PASSWORD' => 'demo',
        ]);

        self::assertSame('BASIC', $config->authStrategy);
    }

    public function testDefaultsToNoneWithoutCredentials(): void
    {
        $config = ConfigResolver::resolve(environment: [
            'CAMUNDA_REST_ADDRESS' => 'http://localhost:8080',
        ]);

        self::assertSame('NONE', $config->authStrategy);
    }

    public function testExplicitStrategyOverridesAutoDetection(): void
    {
        $config = ConfigResolver::resolve(environment: [
            'CAMUNDA_REST_ADDRESS' => 'http://localhost:8080',
            'CAMUNDA_AUTH_STRATEGY' => 'NONE',
            'CAMUNDA_CLIENT_ID' => 'zeebe',
            'CAMUNDA_CLIENT_SECRET' => 'secret',
        ]);

        self::assertSame('NONE', $config->authStrategy);
    }

    public function testParsesTenantIdsList(): void
    {
        $config = ConfigResolver::resolve(environment: [
            'CAMUNDA_REST_ADDRESS' => 'http://localhost:8080',
            'CAMUNDA_TENANT_IDS' => 'acme, globex ,initech',
        ]);

        self::assertSame(['acme', 'globex', 'initech'], $config->tenantIds);
    }

    public function testDefaultsRestAddressWhenUnset(): void
    {
        $config = ConfigResolver::resolve(environment: []);
        self::assertSame('http://localhost:8080/v2', $config->restAddress);
    }

    public function testLoadsEnvironmentFileWithoutOverridingProcessEnvironment(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'orchestration-config-');
        self::assertNotFalse($path);
        file_put_contents(
            $path,
            "CAMUNDA_REST_ADDRESS=http://from-file.example\nCAMUNDA_AUTH_STRATEGY=NONE\nCAMUNDA_WORKER_NAME=from-file\n",
        );

        $previousEnvFile = getenv('CAMUNDA_LOAD_ENVFILE');
        $previousAddress = getenv('CAMUNDA_REST_ADDRESS');
        $previousStrategy = getenv('CAMUNDA_AUTH_STRATEGY');
        $previousWorkerName = getenv('CAMUNDA_WORKER_NAME');
        putenv("CAMUNDA_LOAD_ENVFILE=$path");
        putenv('CAMUNDA_REST_ADDRESS=http://from-process.example');
        putenv('CAMUNDA_AUTH_STRATEGY=NONE');
        putenv('CAMUNDA_WORKER_NAME');

        try {
            $config = ConfigResolver::resolve();
            self::assertSame('http://from-process.example/v2', $config->restAddress);
            self::assertSame('from-file', $config->workerName);
        } finally {
            $this->restoreEnvironment('CAMUNDA_LOAD_ENVFILE', $previousEnvFile);
            $this->restoreEnvironment('CAMUNDA_REST_ADDRESS', $previousAddress);
            $this->restoreEnvironment('CAMUNDA_AUTH_STRATEGY', $previousStrategy);
            $this->restoreEnvironment('CAMUNDA_WORKER_NAME', $previousWorkerName);
            unlink($path);
        }
    }

    private function restoreEnvironment(string $name, string|false $value): void
    {
        if ($value === false) {
            putenv($name);
            return;
        }

        putenv("$name=$value");
    }
}
