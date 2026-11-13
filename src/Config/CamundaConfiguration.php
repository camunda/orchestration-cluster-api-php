<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Config;

/**
 * Immutable, fully-resolved Camunda SDK configuration.
 *
 * Produced by {@see ConfigResolver}. All defaults, environment merging, aliasing and
 * auth-strategy auto-detection happen in the resolver; by the time you hold one of
 * these, every value is final.
 */
final class CamundaConfiguration implements \JsonSerializable
{
    /** Shown wherever a credential would otherwise be dumped (issue #111). */
    private const REDACTED = '***';

    /**
     * @param 'NONE'|'OAUTH'|'BASIC' $authStrategy
     * @param list<string> $tenantIds
     */
    public function __construct(
        public readonly string $restAddress,
        public readonly string $authStrategy,
        public readonly string $oauthUrl = 'https://login.cloud.camunda.io/oauth/token',
        public readonly string $tokenAudience = 'zeebe.camunda.io',
        public readonly ?string $clientId = null,
        public readonly ?string $clientSecret = null,
        public readonly ?string $basicAuthUsername = null,
        public readonly ?string $basicAuthPassword = null,
        public readonly array $tenantIds = [],
        public readonly ?string $mtlsCertPath = null,
        public readonly ?string $mtlsKeyPath = null,
        public readonly ?string $mtlsCaPath = null,
        public readonly ?string $mtlsKeyPassphrase = null,
        public readonly string $logLevel = 'warn',
        public readonly int $workerMaxJobs = 32,
        public readonly int $workerTimeoutMs = 60000,
        public readonly int $workerRequestTimeoutMs = 10000,
        public readonly ?string $workerName = null,
    ) {
    }

    /**
     * The configuration as `var_dump()` and `print_r()` show it, with every credential
     * replaced by a fixed marker. Symfony's VarDumper (and so Symfony's and Laravel's
     * `dump()`/`dd()`) ignores this hook and still shows the real properties.
     *
     * A credential that is not set stays `null`, so "missing" and "redacted" remain
     * distinguishable when you are debugging a configuration problem.
     *
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return [
            'restAddress' => $this->restAddress,
            'authStrategy' => $this->authStrategy,
            'oauthUrl' => $this->oauthUrl,
            'tokenAudience' => $this->tokenAudience,
            'clientId' => $this->clientId,
            'clientSecret' => self::redact($this->clientSecret),
            'basicAuthUsername' => $this->basicAuthUsername,
            'basicAuthPassword' => self::redact($this->basicAuthPassword),
            'tenantIds' => $this->tenantIds,
            'mtlsCertPath' => $this->mtlsCertPath,
            'mtlsKeyPath' => $this->mtlsKeyPath,
            'mtlsCaPath' => $this->mtlsCaPath,
            'mtlsKeyPassphrase' => self::redact($this->mtlsKeyPassphrase),
            'logLevel' => $this->logLevel,
            'workerMaxJobs' => $this->workerMaxJobs,
            'workerTimeoutMs' => $this->workerTimeoutMs,
            'workerRequestTimeoutMs' => $this->workerRequestTimeoutMs,
            'workerName' => $this->workerName,
        ];
    }

    /**
     * The same redacted view for `json_encode()`, which structured loggers and crash
     * reporters reach for.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->__debugInfo();
    }

    /** Keep "not set" and "set but hidden" distinguishable. */
    private static function redact(?string $value): ?string
    {
        return $value === null ? null : self::REDACTED;
    }
}
