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
final class CamundaConfiguration
{
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
}
