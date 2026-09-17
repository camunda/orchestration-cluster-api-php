<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Config;

use Camunda\Orchestration\Exception\ConfigurationException;

/**
 * Resolves {@see CamundaConfiguration} from an environment map (optionally overlaid
 * with an explicit override map and a `.env` file), mirroring the resolution rules
 * shared across the Camunda Orchestration Cluster SDKs.
 *
 * Resolution order (highest precedence first):
 *   1. explicit overrides passed to {@see resolve()}
 *   2. process environment (and `.env` when CAMUNDA_LOAD_ENVFILE is set)
 *   3. built-in defaults
 *
 * Auth strategy is taken from CAMUNDA_AUTH_STRATEGY when set, otherwise auto-detected:
 * OAuth credentials => OAUTH, basic credentials => BASIC, neither => NONE. If both are
 * present the strategy must be set explicitly.
 */
final class ConfigResolver
{
    private const DEFAULT_REST_ADDRESS = 'http://localhost:8080/v2';
    private const DEFAULT_OAUTH_URL = 'https://login.cloud.camunda.io/oauth/token';
    private const DEFAULT_AUDIENCE = 'zeebe.camunda.io';

    /** Environment keys the SDK understands. */
    public const CONFIG_KEYS = [
        'ZEEBE_REST_ADDRESS',
        'CAMUNDA_REST_ADDRESS',
        'CAMUNDA_TOKEN_AUDIENCE',
        'CAMUNDA_OAUTH_URL',
        'CAMUNDA_AUTH_STRATEGY',
        'CAMUNDA_BASIC_AUTH_USERNAME',
        'CAMUNDA_BASIC_AUTH_PASSWORD',
        'CAMUNDA_CLIENT_ID',
        'CAMUNDA_CLIENT_SECRET',
        'CAMUNDA_CLIENT_AUTH_CLIENTID',
        'CAMUNDA_CLIENT_AUTH_CLIENTSECRET',
        'CAMUNDA_SDK_LOG_LEVEL',
        'CAMUNDA_TENANT_ID',
        'CAMUNDA_TENANT_IDS',
        'CAMUNDA_WORKER_TIMEOUT',
        'CAMUNDA_WORKER_MAX_CONCURRENT_JOBS',
        'CAMUNDA_WORKER_REQUEST_TIMEOUT',
        'CAMUNDA_WORKER_NAME',
        'CAMUNDA_MTLS_CERT_PATH',
        'CAMUNDA_MTLS_KEY_PATH',
        'CAMUNDA_MTLS_CA_PATH',
        'CAMUNDA_MTLS_KEY_PASSPHRASE',
        'CAMUNDA_LOAD_ENVFILE',
    ];

    /**
     * @param array<string, string> $overrides Explicit configuration (highest precedence).
     * @param array<string, string>|null $environment Environment map; defaults to getenv().
     */
    public static function resolve(array $overrides = [], ?array $environment = null): CamundaConfiguration
    {
        $env = $environment ?? self::readEnv();
        $merged = self::merge($env, $overrides);

        $restAddress = self::normalizeRestAddress(
            self::first($merged, 'CAMUNDA_REST_ADDRESS', 'ZEEBE_REST_ADDRESS') ?? self::DEFAULT_REST_ADDRESS
        );

        $clientId = self::first($merged, 'CAMUNDA_CLIENT_ID', 'CAMUNDA_CLIENT_AUTH_CLIENTID');
        $clientSecret = self::first($merged, 'CAMUNDA_CLIENT_SECRET', 'CAMUNDA_CLIENT_AUTH_CLIENTSECRET');
        $basicUser = self::value($merged, 'CAMUNDA_BASIC_AUTH_USERNAME');
        $basicPass = self::value($merged, 'CAMUNDA_BASIC_AUTH_PASSWORD');

        $strategy = self::resolveStrategy($merged, $clientId, $clientSecret, $basicUser, $basicPass);
        self::validateStrategy($strategy, $clientId, $clientSecret, $basicUser, $basicPass);

        return new CamundaConfiguration(
            restAddress: $restAddress,
            authStrategy: $strategy,
            oauthUrl: self::value($merged, 'CAMUNDA_OAUTH_URL') ?? self::DEFAULT_OAUTH_URL,
            tokenAudience: self::value($merged, 'CAMUNDA_TOKEN_AUDIENCE') ?? self::DEFAULT_AUDIENCE,
            clientId: $clientId,
            clientSecret: $clientSecret,
            basicAuthUsername: $basicUser,
            basicAuthPassword: $basicPass,
            tenantIds: self::resolveTenantIds($merged),
            mtlsCertPath: self::value($merged, 'CAMUNDA_MTLS_CERT_PATH'),
            mtlsKeyPath: self::value($merged, 'CAMUNDA_MTLS_KEY_PATH'),
            mtlsCaPath: self::value($merged, 'CAMUNDA_MTLS_CA_PATH'),
            mtlsKeyPassphrase: self::value($merged, 'CAMUNDA_MTLS_KEY_PASSPHRASE'),
            logLevel: self::value($merged, 'CAMUNDA_SDK_LOG_LEVEL') ?? 'warn',
            workerMaxJobs: self::intValue($merged, 'CAMUNDA_WORKER_MAX_CONCURRENT_JOBS', 32),
            workerTimeoutMs: self::intValue($merged, 'CAMUNDA_WORKER_TIMEOUT', 60000),
            workerRequestTimeoutMs: self::intValue($merged, 'CAMUNDA_WORKER_REQUEST_TIMEOUT', 10000),
            workerName: self::value($merged, 'CAMUNDA_WORKER_NAME'),
        );
    }

    /** @return array<string, string> */
    private static function readEnv(): array
    {
        $out = [];
        foreach (self::CONFIG_KEYS as $key) {
            $val = getenv($key);
            if ($val !== false) {
                $out[$key] = $val;
            }
        }

        $loadEnvfile = trim($out['CAMUNDA_LOAD_ENVFILE'] ?? '');
        if ($loadEnvfile !== '') {
            foreach (self::dotenvValues($loadEnvfile) as $key => $value) {
                // Real environment wins over .env values.
                $out[$key] ??= $value;
            }
        }

        return $out;
    }

    /**
     * @return array<string, string>
     */
    private static function dotenvValues(string $loadEnvfile): array
    {
        if (!class_exists(\Dotenv\Dotenv::class)) {
            return [];
        }
        $lower = strtolower($loadEnvfile);
        $path = in_array($lower, ['true', '1', 'yes'], true)
            ? getcwd() . '/.env'
            : $loadEnvfile;
        if (!is_file($path)) {
            return [];
        }

        try {
            /** @var array<string, string> $parsed */
            $parsed = \Dotenv\Dotenv::parse((string) file_get_contents($path));
            return $parsed;
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * @param array<string, string> $env
     * @param array<string, string> $overrides
     * @return array<string, string>
     */
    private static function merge(array $env, array $overrides): array
    {
        foreach ($overrides as $key => $value) {
            $env[$key] = $value;
        }
        return $env;
    }

    private static function normalizeRestAddress(string $address): string
    {
        $address = rtrim($address, '/');
        if (!preg_match('#/v\d+$#', $address)) {
            $address .= '/v2';
        }
        return $address;
    }

    /**
     * @param array<string, string> $merged
     * @return 'NONE'|'OAUTH'|'BASIC'
     */
    private static function resolveStrategy(
        array $merged,
        ?string $clientId,
        ?string $clientSecret,
        ?string $basicUser,
        ?string $basicPass,
    ): string {
        $explicit = strtoupper(trim($merged['CAMUNDA_AUTH_STRATEGY'] ?? ''));
        if ($explicit !== '') {
            if (!in_array($explicit, ['NONE', 'OAUTH', 'BASIC'], true)) {
                throw new ConfigurationException(
                    "CAMUNDA_AUTH_STRATEGY must be one of NONE, OAUTH, BASIC (got \"$explicit\")."
                );
            }
            /** @var 'NONE'|'OAUTH'|'BASIC' $explicit */
            return $explicit;
        }

        $hasOauth = $clientId !== null && $clientSecret !== null;
        $hasBasic = $basicUser !== null && $basicPass !== null;

        if ($hasOauth && $hasBasic) {
            throw new ConfigurationException(
                'Both OAuth (CAMUNDA_CLIENT_ID/SECRET) and Basic auth '
                . '(CAMUNDA_BASIC_AUTH_USERNAME/PASSWORD) credentials are set, but '
                . 'CAMUNDA_AUTH_STRATEGY is not configured. Set it to OAUTH or BASIC.'
            );
        }
        if ($hasOauth) {
            return 'OAUTH';
        }
        if ($hasBasic) {
            return 'BASIC';
        }
        return 'NONE';
    }

    private static function validateStrategy(
        string $strategy,
        ?string $clientId,
        ?string $clientSecret,
        ?string $basicUser,
        ?string $basicPass,
    ): void {
        if ($strategy === 'OAUTH' && ($clientId === null || $clientSecret === null)) {
            throw new ConfigurationException(
                'CAMUNDA_CLIENT_ID and CAMUNDA_CLIENT_SECRET are required when CAMUNDA_AUTH_STRATEGY=OAUTH.'
            );
        }
        if ($strategy === 'BASIC' && ($basicUser === null || $basicPass === null)) {
            throw new ConfigurationException(
                'CAMUNDA_BASIC_AUTH_USERNAME and CAMUNDA_BASIC_AUTH_PASSWORD are required when '
                . 'CAMUNDA_AUTH_STRATEGY=BASIC.'
            );
        }
    }

    /**
     * @param array<string, string> $merged
     * @return list<string>
     */
    private static function resolveTenantIds(array $merged): array
    {
        $ids = self::value($merged, 'CAMUNDA_TENANT_IDS');
        if ($ids !== null) {
            return array_values(array_filter(array_map('trim', explode(',', $ids)), static fn ($v) => $v !== ''));
        }
        $single = self::value($merged, 'CAMUNDA_TENANT_ID');
        return $single !== null ? [$single] : [];
    }

    /**
     * @param array<string, string> $merged
     */
    private static function first(array $merged, string ...$keys): ?string
    {
        foreach ($keys as $key) {
            $val = self::value($merged, $key);
            if ($val !== null) {
                return $val;
            }
        }
        return null;
    }

    /**
     * @param array<string, string> $merged
     */
    private static function value(array $merged, string $key): ?string
    {
        if (!array_key_exists($key, $merged)) {
            return null;
        }
        $val = trim($merged[$key]);
        return $val === '' ? null : $val;
    }

    /**
     * @param array<string, string> $merged
     */
    private static function intValue(array $merged, string $key, int $default): int
    {
        $val = self::value($merged, $key);
        return $val !== null && ctype_digit($val) ? (int) $val : $default;
    }
}
