<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\CamundaAsyncClient;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Config\CamundaConfiguration;
use GuzzleHttp\Client as GuzzleClient;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Issue #111: dumping a configuration or a client is the ordinary way a PHP
 * application reports a startup problem — framework debug pages, `dd()`, structured
 * log context and crash reporters all serialise objects this way. None of those
 * surfaces may show a credential.
 *
 * The tests walk every credential field and every interceptable dump surface, so a
 * credential added later is caught by the same test rather than needing a new one.
 */
final class ConfigurationSecretRedactionTest extends TestCase
{
    private const CLIENT_SECRET = 'sentinel-client-secret-a1';
    private const BASIC_PASSWORD = 'sentinel-basic-password-b2';
    private const MTLS_PASSPHRASE = 'sentinel-mtls-passphrase-c3';

    /**
     * @return iterable<string, array{string}>
     */
    public static function credentials(): iterable
    {
        yield 'client secret' => [self::CLIENT_SECRET];
        yield 'basic auth password' => [self::BASIC_PASSWORD];
        yield 'mtls key passphrase' => [self::MTLS_PASSPHRASE];
    }

    #[DataProvider('credentials')]
    public function testVarDumpOfTheConfigurationHidesEveryCredential(string $sentinel): void
    {
        self::assertStringNotContainsString($sentinel, self::varDump($this->configuration()));
    }

    #[DataProvider('credentials')]
    public function testJsonEncodeOfTheConfigurationHidesEveryCredential(string $sentinel): void
    {
        self::assertStringNotContainsString($sentinel, (string) json_encode($this->configuration()));
    }

    #[DataProvider('credentials')]
    public function testVarDumpOfTheSyncClientHidesEveryCredential(string $sentinel): void
    {
        $client = CamundaClient::fromConfiguration($this->configuration(), new GuzzleClient());

        self::assertStringNotContainsString($sentinel, self::varDump($client));
    }

    #[DataProvider('credentials')]
    public function testVarDumpOfTheAsyncClientHidesEveryCredential(string $sentinel): void
    {
        $client = CamundaAsyncClient::fromConfiguration($this->configuration(), new GuzzleClient());

        self::assertStringNotContainsString($sentinel, self::varDump($client));
    }

    #[DataProvider('credentials')]
    public function testJsonEncodeOfTheSyncClientHidesEveryCredential(string $sentinel): void
    {
        $client = CamundaClient::fromConfiguration($this->configuration(), new GuzzleClient());

        self::assertStringNotContainsString($sentinel, (string) json_encode($client));
    }

    public function testRedactedFieldsAreMarkedRatherThanSilentlyDropped(): void
    {
        $debug = $this->configuration()->__debugInfo();

        self::assertSame('***', $debug['clientSecret']);
        self::assertSame('***', $debug['basicAuthPassword']);
        self::assertSame('***', $debug['mtlsKeyPassphrase']);
    }

    public function testUnsetCredentialsStayNullSoAMissingValueIsStillVisible(): void
    {
        $debug = (new CamundaConfiguration(
            restAddress: 'https://cluster.example.test',
            authStrategy: 'NONE',
        ))->__debugInfo();

        self::assertNull($debug['clientSecret']);
        self::assertNull($debug['basicAuthPassword']);
        self::assertNull($debug['mtlsKeyPassphrase']);
    }

    public function testNonSecretFieldsStayVisibleForDebugging(): void
    {
        $debug = $this->configuration()->__debugInfo();

        self::assertSame('https://cluster.example.test', $debug['restAddress']);
        self::assertSame('OAUTH', $debug['authStrategy']);
        self::assertSame('sentinel-client-id', $debug['clientId']);
        self::assertSame('operator', $debug['basicAuthUsername']);
        self::assertSame('/etc/camunda/client.pem', $debug['mtlsCertPath']);
        self::assertSame(['acme'], $debug['tenantIds']);
    }

    public function testEveryPublicPropertyIsAccountedForInTheDebugView(): void
    {
        $configuration = $this->configuration();
        $debug = $configuration->__debugInfo();

        self::assertSame(
            array_keys(get_object_vars($configuration)),
            array_keys($debug),
            'A newly added configuration field must be classified as secret or non-secret.',
        );
    }

    public function testJsonEncodeKeepsTheSameShapeAsTheDebugView(): void
    {
        $configuration = $this->configuration();

        /** @var array<string, mixed> $encoded */
        $encoded = json_decode((string) json_encode($configuration), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame(array_keys($configuration->__debugInfo()), array_keys($encoded));
        self::assertSame('***', $encoded['clientSecret']);
    }

    /**
     * The readonly value-object API is unchanged: redaction is a debug-surface concern,
     * not a change to how the SDK reads its own configuration.
     */
    public function testTheReadonlyPublicApiStillReturnsTheRealValues(): void
    {
        $configuration = $this->configuration();

        self::assertSame(self::CLIENT_SECRET, $configuration->clientSecret);
        self::assertSame(self::BASIC_PASSWORD, $configuration->basicAuthPassword);
        self::assertSame(self::MTLS_PASSPHRASE, $configuration->mtlsKeyPassphrase);
    }

    private function configuration(): CamundaConfiguration
    {
        return new CamundaConfiguration(
            restAddress: 'https://cluster.example.test',
            authStrategy: 'OAUTH',
            clientId: 'sentinel-client-id',
            clientSecret: self::CLIENT_SECRET,
            basicAuthUsername: 'operator',
            basicAuthPassword: self::BASIC_PASSWORD,
            tenantIds: ['acme'],
            mtlsCertPath: '/etc/camunda/client.pem',
            mtlsKeyPath: '/etc/camunda/client.key',
            mtlsKeyPassphrase: self::MTLS_PASSPHRASE,
        );
    }

    private static function varDump(object $value): string
    {
        ob_start();
        var_dump($value);

        return (string) ob_get_clean();
    }
}
