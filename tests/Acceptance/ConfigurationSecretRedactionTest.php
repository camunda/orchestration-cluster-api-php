<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Api\JobApi;
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
    public function testPrintROfTheConfigurationHidesEveryCredential(string $sentinel): void
    {
        self::assertStringNotContainsString($sentinel, print_r($this->configuration(), true));
    }

    #[DataProvider('credentials')]
    public function testPrintROfTheClientsHidesEveryCredential(string $sentinel): void
    {
        self::assertStringNotContainsString($sentinel, print_r($this->syncClient(), true));
        self::assertStringNotContainsString($sentinel, print_r($this->asyncClient(), true));
    }

    /**
     * `var_export()` builds a `__set_state()` literal from the properties with no hook
     * to intercept, and it exports private and protected properties just the same, so
     * it is the one surface that still prints credentials and no change of visibility
     * would alter that. Pinned rather than left implicit, so the day it stops being
     * true the README paragraph is updated with it.
     */
    #[DataProvider('credentials')]
    public function testVarExportIsTheDocumentedExceptionRegardlessOfVisibility(string $sentinel): void
    {
        self::assertStringContainsString($sentinel, var_export($this->configuration(), true));

        $privateHolder = new class ($sentinel) {
            public function __construct(private readonly string $secret)
            {
            }

            public function reveal(): string
            {
                return $this->secret;
            }

            /** @return array<string, mixed> */
            public function __debugInfo(): array
            {
                return ['secret' => '***'];
            }
        };

        self::assertStringContainsString(
            $sentinel,
            var_export($privateHolder, true),
            'var_export() reaches private properties too, so making the fields private would not close it.',
        );
        self::assertStringNotContainsString($sentinel, print_r($privateHolder, true));
        self::assertSame($sentinel, $privateHolder->reveal());
    }

    #[DataProvider('credentials')]
    public function testJsonEncodeOfTheConfigurationHidesEveryCredential(string $sentinel): void
    {
        self::assertStringNotContainsString($sentinel, (string) json_encode($this->configuration()));
    }

    #[DataProvider('credentials')]
    public function testVarDumpOfTheSyncClientHidesEveryCredential(string $sentinel): void
    {
        self::assertStringNotContainsString($sentinel, self::varDump($this->syncClient()));
    }

    #[DataProvider('credentials')]
    public function testVarDumpOfTheAsyncClientHidesEveryCredential(string $sentinel): void
    {
        self::assertStringNotContainsString($sentinel, self::varDump($this->asyncClient()));
    }

    #[DataProvider('credentials')]
    public function testJsonEncodeOfTheSyncClientHidesEveryCredential(string $sentinel): void
    {
        self::assertStringNotContainsString($sentinel, (string) json_encode($this->syncClient()));
    }

    #[DataProvider('credentials')]
    public function testJsonEncodeOfTheAsyncClientHidesEveryCredential(string $sentinel): void
    {
        self::assertStringNotContainsString($sentinel, (string) json_encode($this->asyncClient()));
    }

    /**
     * The client's debug view must hide the HTTP stack rather than merely not mention
     * the configuration twice: the Guzzle options built from an mTLS configuration
     * carry the key passphrase, and the auth middleware holds the client secret.
     */
    public function testTheClientDebugViewSummarisesTheHttpStackInsteadOfDumpingIt(): void
    {
        $debug = $this->syncClient()->__debugInfo();

        self::assertSame(GuzzleClient::class, $debug['httpClient']);
        self::assertSame([JobApi::class], $debug['apis']);
        self::assertInstanceOf(CamundaConfiguration::class, $debug['configuration']);
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

    /**
     * Built through the real wiring — no HTTP-client override — so the Guzzle options
     * really do carry the mTLS passphrase and the auth middleware really does hold the
     * client secret. One API group is resolved so the cache is populated too.
     */
    private function syncClient(): CamundaClient
    {
        $client = CamundaClient::fromConfiguration($this->configuration());
        $client->api(JobApi::class);

        return $client;
    }

    private function asyncClient(): CamundaAsyncClient
    {
        $client = CamundaAsyncClient::fromConfiguration($this->configuration());
        $client->api(JobApi::class);

        return $client;
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
