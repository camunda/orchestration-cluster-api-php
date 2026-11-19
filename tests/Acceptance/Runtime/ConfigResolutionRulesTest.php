<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance\Runtime;

use Camunda\Orchestration\Config\ConfigResolver;
use Camunda\Orchestration\Exception\ConfigurationException;
use Camunda\Orchestration\Tests\Support\EnvironmentSandbox;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Failure paths and precedence rules of {@see ConfigResolver}: invalid and missing configuration,
 * override > environment > default precedence, legacy aliases, blank handling, mTLS options and
 * `.env` loading.
 */
final class ConfigResolutionRulesTest extends TestCase
{
    private EnvironmentSandbox $env;
    private string $originalCwd;

    /** @var list<string> */
    private array $cleanup = [];

    protected function setUp(): void
    {
        $this->env = new EnvironmentSandbox();
        $this->env->enter();
        $cwd = getcwd();
        self::assertIsString($cwd);
        $this->originalCwd = $cwd;
    }

    protected function tearDown(): void
    {
        chdir($this->originalCwd);
        $this->env->leave();
        foreach (array_reverse($this->cleanup) as $path) {
            is_dir($path) ? rmdir($path) : @unlink($path);
        }
    }

    // ------------------------------------------------------------------ precedence

    public function testOverridesBeatEnvironmentWhichBeatsDefaults(): void
    {
        $env = ['CAMUNDA_REST_ADDRESS' => 'http://from-env', 'CAMUNDA_WORKER_NAME' => 'env-name'];

        $config = ConfigResolver::resolve(['CAMUNDA_REST_ADDRESS' => 'http://from-override'], $env);

        self::assertSame('http://from-override/v2', $config->restAddress);
        self::assertSame('env-name', $config->workerName);
        self::assertSame('warn', $config->logLevel, 'unset keys fall back to the built-in default');
    }

    public function testProcessEnvironmentIsUsedOnlyWhenNoEnvironmentMapIsPassed(): void
    {
        $this->env->set('CAMUNDA_REST_ADDRESS', 'http://from-process');

        self::assertSame('http://from-process/v2', ConfigResolver::resolve()->restAddress);
        self::assertSame(
            'http://localhost:8080/v2',
            ConfigResolver::resolve(environment: [])->restAddress,
            'an explicit (even empty) environment map must isolate the resolver from the process environment',
        );
    }

    public function testUnrelatedProcessVariablesAreIgnored(): void
    {
        putenv('SOMETHING_ELSE=1');
        try {
            self::assertSame('NONE', ConfigResolver::resolve()->authStrategy);
        } finally {
            putenv('SOMETHING_ELSE');
        }
    }

    public function testLegacyRestAddressAliasIsUsedOnlyWhenTheCurrentKeyIsUnsetOrBlank(): void
    {
        self::assertSame(
            'http://zeebe-alias/v2',
            ConfigResolver::resolve(environment: ['ZEEBE_REST_ADDRESS' => 'http://zeebe-alias'])->restAddress,
        );
        self::assertSame(
            'http://current/v2',
            ConfigResolver::resolve(environment: [
                'CAMUNDA_REST_ADDRESS' => 'http://current',
                'ZEEBE_REST_ADDRESS' => 'http://zeebe-alias',
            ])->restAddress,
        );
        self::assertSame(
            'http://zeebe-alias/v2',
            ConfigResolver::resolve(environment: [
                'CAMUNDA_REST_ADDRESS' => '   ',
                'ZEEBE_REST_ADDRESS' => 'http://zeebe-alias',
            ])->restAddress,
        );
    }

    public function testLegacyOAuthClientAliasesAreAcceptedAndMayBeMixedWithCurrentKeys(): void
    {
        $legacy = ConfigResolver::resolve(environment: [
            'CAMUNDA_CLIENT_AUTH_CLIENTID' => 'legacy-id',
            'CAMUNDA_CLIENT_AUTH_CLIENTSECRET' => 'legacy-secret',
        ]);
        self::assertSame('OAUTH', $legacy->authStrategy);
        self::assertSame('legacy-id', $legacy->clientId);
        self::assertSame('legacy-secret', $legacy->clientSecret);

        $mixed = ConfigResolver::resolve(environment: [
            'CAMUNDA_CLIENT_ID' => 'current-id',
            'CAMUNDA_CLIENT_AUTH_CLIENTID' => 'legacy-id',
            'CAMUNDA_CLIENT_AUTH_CLIENTSECRET' => 'legacy-secret',
        ]);
        self::assertSame('current-id', $mixed->clientId, 'the current key wins over its legacy alias');
        self::assertSame('legacy-secret', $mixed->clientSecret);
    }

    // ------------------------------------------------------------------ blanks

    public function testBlankAndWhitespaceValuesCountAsUnsetAndValuesAreTrimmed(): void
    {
        $config = ConfigResolver::resolve(environment: [
            'CAMUNDA_CLIENT_ID' => '   ',
            'CAMUNDA_CLIENT_SECRET' => '',
            'CAMUNDA_BASIC_AUTH_USERNAME' => ' demo ',
            'CAMUNDA_BASIC_AUTH_PASSWORD' => "\tpw\n",
            'CAMUNDA_WORKER_NAME' => '  ',
            'CAMUNDA_MTLS_CA_PATH' => '',
        ]);

        self::assertSame('BASIC', $config->authStrategy);
        self::assertNull($config->clientId);
        self::assertNull($config->clientSecret);
        self::assertSame('demo', $config->basicAuthUsername);
        self::assertSame('pw', $config->basicAuthPassword);
        self::assertNull($config->workerName);
        self::assertNull($config->mtlsCaPath);
    }

    // ------------------------------------------------------------------ auth strategy

    public function testExplicitStrategyIsCaseInsensitiveAndTrimmed(): void
    {
        $config = ConfigResolver::resolve(environment: [
            'CAMUNDA_AUTH_STRATEGY' => ' oauth ',
            'CAMUNDA_CLIENT_ID' => 'id',
            'CAMUNDA_CLIENT_SECRET' => 'secret',
        ]);

        self::assertSame('OAUTH', $config->authStrategy);
    }

    public function testUnknownStrategyIsRejectedWithoutLeakingCredentials(): void
    {
        try {
            ConfigResolver::resolve(environment: [
                'CAMUNDA_AUTH_STRATEGY' => 'KERBEROS',
                'CAMUNDA_CLIENT_ID' => 'the-client-id',
                'CAMUNDA_CLIENT_SECRET' => 'the-client-secret',
                'CAMUNDA_BASIC_AUTH_PASSWORD' => 'the-basic-password',
            ]);
            self::fail('Expected a ConfigurationException.');
        } catch (ConfigurationException $e) {
            self::assertStringContainsString('NONE, OAUTH, BASIC', $e->getMessage());
            self::assertStringContainsString('KERBEROS', $e->getMessage());
            foreach (['the-client-secret', 'the-basic-password'] as $secret) {
                self::assertStringNotContainsString($secret, (string) $e);
            }
        }
    }

    /**
     * @param array<string, string> $environment
     */
    #[DataProvider('incompleteCredentials')]
    public function testExplicitStrategyWithMissingCredentialsIsRejected(array $environment, string $expected): void
    {
        try {
            ConfigResolver::resolve(environment: $environment);
            self::fail('Expected a ConfigurationException.');
        } catch (ConfigurationException $e) {
            self::assertStringContainsString($expected, $e->getMessage());
            foreach ($environment as $key => $value) {
                if (str_contains($key, 'SECRET') || str_contains($key, 'PASSWORD')) {
                    self::assertStringNotContainsString($value, $e->getMessage());
                }
            }
        }
    }

    /** @return iterable<string, array{array<string, string>, string}> */
    public static function incompleteCredentials(): iterable
    {
        yield 'OAUTH without any credentials' => [
            ['CAMUNDA_AUTH_STRATEGY' => 'OAUTH'],
            'CAMUNDA_CLIENT_ID and CAMUNDA_CLIENT_SECRET are required',
        ];
        yield 'OAUTH with only the client id' => [
            ['CAMUNDA_AUTH_STRATEGY' => 'OAUTH', 'CAMUNDA_CLIENT_ID' => 'id'],
            'CAMUNDA_CLIENT_ID and CAMUNDA_CLIENT_SECRET are required',
        ];
        yield 'OAUTH with only the client secret' => [
            ['CAMUNDA_AUTH_STRATEGY' => 'OAUTH', 'CAMUNDA_CLIENT_SECRET' => 'shh-secret'],
            'CAMUNDA_CLIENT_ID and CAMUNDA_CLIENT_SECRET are required',
        ];
        yield 'BASIC without any credentials' => [
            ['CAMUNDA_AUTH_STRATEGY' => 'BASIC'],
            'CAMUNDA_BASIC_AUTH_USERNAME and CAMUNDA_BASIC_AUTH_PASSWORD are required',
        ];
        yield 'BASIC with only the username' => [
            ['CAMUNDA_AUTH_STRATEGY' => 'BASIC', 'CAMUNDA_BASIC_AUTH_USERNAME' => 'demo'],
            'CAMUNDA_BASIC_AUTH_USERNAME and CAMUNDA_BASIC_AUTH_PASSWORD are required',
        ];
        yield 'BASIC with only the password' => [
            ['CAMUNDA_AUTH_STRATEGY' => 'BASIC', 'CAMUNDA_BASIC_AUTH_PASSWORD' => 'shh-password'],
            'CAMUNDA_BASIC_AUTH_USERNAME and CAMUNDA_BASIC_AUTH_PASSWORD are required',
        ];
    }

    public function testBothCredentialFamiliesWithoutAStrategyAreAmbiguous(): void
    {
        $environment = [
            'CAMUNDA_CLIENT_ID' => 'id',
            'CAMUNDA_CLIENT_SECRET' => 'oauth-secret-value',
            'CAMUNDA_BASIC_AUTH_USERNAME' => 'demo',
            'CAMUNDA_BASIC_AUTH_PASSWORD' => 'basic-password-value',
        ];

        try {
            ConfigResolver::resolve(environment: $environment);
            self::fail('Expected a ConfigurationException.');
        } catch (ConfigurationException $e) {
            self::assertStringContainsString('CAMUNDA_AUTH_STRATEGY is not configured', $e->getMessage());
            self::assertStringNotContainsString('oauth-secret-value', $e->getMessage());
            self::assertStringNotContainsString('basic-password-value', $e->getMessage());
        }

        self::assertSame('OAUTH', ConfigResolver::resolve(environment: $environment + ['CAMUNDA_AUTH_STRATEGY' => 'OAUTH'])->authStrategy);
        self::assertSame('BASIC', ConfigResolver::resolve(environment: $environment + ['CAMUNDA_AUTH_STRATEGY' => 'BASIC'])->authStrategy);
    }

    public function testPartialCredentialsWithoutAStrategyAreNotAutoDetected(): void
    {
        self::assertSame('NONE', ConfigResolver::resolve(environment: ['CAMUNDA_CLIENT_ID' => 'id'])->authStrategy);
        self::assertSame('NONE', ConfigResolver::resolve(environment: ['CAMUNDA_CLIENT_SECRET' => 's'])->authStrategy);
        self::assertSame('NONE', ConfigResolver::resolve(environment: ['CAMUNDA_BASIC_AUTH_USERNAME' => 'u'])->authStrategy);
        self::assertSame('NONE', ConfigResolver::resolve(environment: ['CAMUNDA_BASIC_AUTH_PASSWORD' => 'p'])->authStrategy);
    }

    public function testOAuthEndpointAndAudienceOverridesAndDefaults(): void
    {
        $defaults = ConfigResolver::resolve(environment: []);
        self::assertSame('https://login.cloud.camunda.io/oauth/token', $defaults->oauthUrl);
        self::assertSame('zeebe.camunda.io', $defaults->tokenAudience);

        $custom = ConfigResolver::resolve(environment: [
            'CAMUNDA_OAUTH_URL' => 'https://idp.test/token',
            'CAMUNDA_TOKEN_AUDIENCE' => 'my-aud',
        ]);
        self::assertSame('https://idp.test/token', $custom->oauthUrl);
        self::assertSame('my-aud', $custom->tokenAudience);
    }

    // ------------------------------------------------------------------ rest address

    #[DataProvider('restAddresses')]
    public function testRestAddressNormalisation(string $given, string $expected): void
    {
        self::assertSame($expected, ConfigResolver::resolve(environment: ['CAMUNDA_REST_ADDRESS' => $given])->restAddress);
    }

    /** @return iterable<string, array{string, string}> */
    public static function restAddresses(): iterable
    {
        yield 'bare host' => ['http://localhost:8080', 'http://localhost:8080/v2'];
        yield 'trailing slash' => ['http://localhost:8080/', 'http://localhost:8080/v2'];
        yield 'several trailing slashes' => ['http://localhost:8080///', 'http://localhost:8080/v2'];
        yield 'already versioned' => ['http://localhost:8080/v2', 'http://localhost:8080/v2'];
        yield 'versioned with trailing slash' => ['http://localhost:8080/v2/', 'http://localhost:8080/v2'];
        yield 'another API version is respected' => ['http://localhost:8080/v3', 'http://localhost:8080/v3'];
        yield 'context path' => ['https://gw.example.test/camunda', 'https://gw.example.test/camunda/v2'];
        yield 'surrounding whitespace' => ['  https://c.example.test:8443  ', 'https://c.example.test:8443/v2'];
    }

    // ------------------------------------------------------------------ tenants

    public function testTenantIdsListBeatsTheSingleTenantIdAndBlanksAreDropped(): void
    {
        $config = ConfigResolver::resolve(environment: [
            'CAMUNDA_TENANT_ID' => 'single',
            'CAMUNDA_TENANT_IDS' => ' a ,, b ,  ,c',
        ]);
        self::assertSame(['a', 'b', 'c'], $config->tenantIds);
    }

    public function testSingleTenantIdIsUsedWhenTheListIsUnsetOrBlank(): void
    {
        self::assertSame(['single'], ConfigResolver::resolve(environment: ['CAMUNDA_TENANT_ID' => 'single'])->tenantIds);
        self::assertSame(['single'], ConfigResolver::resolve(environment: [
            'CAMUNDA_TENANT_ID' => 'single',
            'CAMUNDA_TENANT_IDS' => '  ',
        ])->tenantIds);
        self::assertSame([], ConfigResolver::resolve(environment: [])->tenantIds);
    }

    public function testTenantListOfOnlySeparatorsYieldsNoTenants(): void
    {
        self::assertSame([], ConfigResolver::resolve(environment: ['CAMUNDA_TENANT_IDS' => ' , ,'])->tenantIds);
    }

    // ------------------------------------------------------------------ numbers

    public function testWorkerNumbersUseDefaultsWhenUnset(): void
    {
        $config = ConfigResolver::resolve(environment: []);

        self::assertSame(32, $config->workerMaxJobs);
        self::assertSame(60000, $config->workerTimeoutMs);
        self::assertSame(10000, $config->workerRequestTimeoutMs);
        self::assertNull($config->workerName);
    }

    public function testWorkerNumbersAreParsedFromDigits(): void
    {
        $config = ConfigResolver::resolve(environment: [
            'CAMUNDA_WORKER_MAX_CONCURRENT_JOBS' => ' 5 ',
            'CAMUNDA_WORKER_TIMEOUT' => '1500',
            'CAMUNDA_WORKER_REQUEST_TIMEOUT' => '0',
            'CAMUNDA_WORKER_NAME' => 'billing-worker',
        ]);

        self::assertSame(5, $config->workerMaxJobs);
        self::assertSame(1500, $config->workerTimeoutMs);
        self::assertSame(0, $config->workerRequestTimeoutMs);
        self::assertSame('billing-worker', $config->workerName);
    }

    #[DataProvider('malformedNumbers')]
    public function testMalformedWorkerNumbersFallBackToTheDefault(string $raw): void
    {
        $config = ConfigResolver::resolve(environment: [
            'CAMUNDA_WORKER_MAX_CONCURRENT_JOBS' => $raw,
            'CAMUNDA_WORKER_TIMEOUT' => $raw,
            'CAMUNDA_WORKER_REQUEST_TIMEOUT' => $raw,
        ]);

        self::assertSame(32, $config->workerMaxJobs);
        self::assertSame(60000, $config->workerTimeoutMs);
        self::assertSame(10000, $config->workerRequestTimeoutMs);
    }

    /** @return iterable<string, array{string}> */
    public static function malformedNumbers(): iterable
    {
        yield 'letters' => ['abc'];
        yield 'negative' => ['-5'];
        yield 'decimal' => ['1.5'];
        yield 'scientific' => ['1e3'];
        yield 'signed' => ['+7'];
        yield 'unit suffix' => ['10s'];
        yield 'blank' => [''];
    }

    // ------------------------------------------------------------------ mTLS & misc

    public function testMtlsOptionsAreResolved(): void
    {
        $config = ConfigResolver::resolve(environment: [
            'CAMUNDA_MTLS_CERT_PATH' => '/c/client.pem',
            'CAMUNDA_MTLS_KEY_PATH' => '/c/client.key',
            'CAMUNDA_MTLS_CA_PATH' => '/c/ca.pem',
            'CAMUNDA_MTLS_KEY_PASSPHRASE' => 'phrase',
        ]);

        self::assertSame('/c/client.pem', $config->mtlsCertPath);
        self::assertSame('/c/client.key', $config->mtlsKeyPath);
        self::assertSame('/c/ca.pem', $config->mtlsCaPath);
        self::assertSame('phrase', $config->mtlsKeyPassphrase);
    }

    public function testMtlsOptionsDefaultToNullAndMayBeSetIndependently(): void
    {
        $none = ConfigResolver::resolve(environment: []);
        self::assertNull($none->mtlsCertPath);
        self::assertNull($none->mtlsKeyPath);
        self::assertNull($none->mtlsCaPath);
        self::assertNull($none->mtlsKeyPassphrase);

        $caOnly = ConfigResolver::resolve(environment: ['CAMUNDA_MTLS_CA_PATH' => '/c/ca.pem']);
        self::assertSame('/c/ca.pem', $caOnly->mtlsCaPath);
        self::assertNull($caOnly->mtlsCertPath);
    }

    public function testLogLevelDefaultsToWarnAndCanBeOverridden(): void
    {
        self::assertSame('warn', ConfigResolver::resolve(environment: [])->logLevel);
        self::assertSame('debug', ConfigResolver::resolve(environment: ['CAMUNDA_SDK_LOG_LEVEL' => 'debug'])->logLevel);
    }

    // ------------------------------------------------------------------ .env loading

    public function testEnvFileFlagTrueLoadsDotEnvFromTheWorkingDirectory(): void
    {
        $dir = $this->makeTempDir();
        file_put_contents("$dir/.env", "CAMUNDA_REST_ADDRESS=http://dotenv-cwd\nCAMUNDA_WORKER_NAME=\"quoted name\"\n");
        $this->cleanup[] = "$dir/.env";
        chdir($dir);
        $this->env->set('CAMUNDA_LOAD_ENVFILE', 'true');

        $config = ConfigResolver::resolve();

        self::assertSame('http://dotenv-cwd/v2', $config->restAddress);
        self::assertSame('quoted name', $config->workerName);
    }

    #[DataProvider('truthyFlags')]
    public function testEnvFileFlagAcceptsTruthyWords(string $flag): void
    {
        $dir = $this->makeTempDir();
        file_put_contents("$dir/.env", "CAMUNDA_WORKER_NAME=from-dotenv\n");
        $this->cleanup[] = "$dir/.env";
        chdir($dir);
        $this->env->set('CAMUNDA_LOAD_ENVFILE', $flag);

        self::assertSame('from-dotenv', ConfigResolver::resolve()->workerName);
    }

    /** @return iterable<string, array{string}> */
    public static function truthyFlags(): iterable
    {
        yield 'TRUE' => ['TRUE'];
        yield '1' => ['1'];
        yield 'yes' => ['yes'];
    }

    public function testEnvFileFlagFalseDoesNotReadDotEnv(): void
    {
        $dir = $this->makeTempDir();
        file_put_contents("$dir/.env", "CAMUNDA_WORKER_NAME=from-dotenv\n");
        $this->cleanup[] = "$dir/.env";
        chdir($dir);
        $this->env->set('CAMUNDA_LOAD_ENVFILE', 'false');

        // "false" is not a truthy word, so it is treated as a path, which does not exist.
        self::assertNull(ConfigResolver::resolve()->workerName);
    }

    public function testMissingEnvFileIsIgnored(): void
    {
        $this->env->set('CAMUNDA_LOAD_ENVFILE', '/definitely/not/here/.env');

        self::assertSame('http://localhost:8080/v2', ConfigResolver::resolve()->restAddress);
    }

    public function testUnparsableEnvFileIsIgnoredRatherThanFailingStartup(): void
    {
        $path = $this->makeTempFile("this is not = a valid\ndotenv line ===\n\"unterminated\n");
        $this->env->set('CAMUNDA_LOAD_ENVFILE', $path);

        self::assertSame('http://localhost:8080/v2', ConfigResolver::resolve()->restAddress);
    }

    public function testExplicitOverridesAndProcessEnvironmentBeatDotEnvValues(): void
    {
        $path = $this->makeTempFile("CAMUNDA_REST_ADDRESS=http://from-file\nCAMUNDA_WORKER_NAME=file-name\nCAMUNDA_SDK_LOG_LEVEL=debug\n");
        $this->env->set('CAMUNDA_LOAD_ENVFILE', $path);
        $this->env->set('CAMUNDA_WORKER_NAME', 'process-name');

        $config = ConfigResolver::resolve(['CAMUNDA_SDK_LOG_LEVEL' => 'error']);

        self::assertSame('http://from-file/v2', $config->restAddress, 'file fills what the process env leaves unset');
        self::assertSame('process-name', $config->workerName, 'process env beats the file');
        self::assertSame('error', $config->logLevel, 'explicit overrides beat everything');
    }

    public function testPassingAnExplicitEnvironmentMapNeverReadsDotEnv(): void
    {
        $path = $this->makeTempFile("CAMUNDA_WORKER_NAME=from-file\n");
        $this->env->set('CAMUNDA_LOAD_ENVFILE', $path);

        self::assertNull(ConfigResolver::resolve(environment: [])->workerName);
    }

    private function makeTempFile(string $contents): string
    {
        $path = tempnam(sys_get_temp_dir(), 'orchestration-env-');
        self::assertNotFalse($path);
        file_put_contents($path, $contents);
        $this->cleanup[] = $path;

        return $path;
    }

    private function makeTempDir(): string
    {
        $path = sys_get_temp_dir() . '/orchestration-cwd-' . bin2hex(random_bytes(6));
        self::assertTrue(mkdir($path));
        $this->cleanup[] = $path;

        return $path;
    }
}
