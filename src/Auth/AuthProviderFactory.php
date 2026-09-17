<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Auth;

use Camunda\Orchestration\Config\CamundaConfiguration;
use Http\Discovery\Psr17Factory;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;

/**
 * Builds the {@see AuthProvider} that matches a resolved configuration's auth strategy.
 */
final class AuthProviderFactory
{
    /**
     * @param ClientInterface|null $tokenHttpClient Optional PSR-18 client for the OAuth token
     *   exchange. Auto-discovered when omitted.
     */
    public static function fromConfiguration(
        CamundaConfiguration $config,
        ?ClientInterface $tokenHttpClient = null,
    ): AuthProvider {
        return match ($config->authStrategy) {
            'BASIC' => new BasicAuthProvider(
                (string) $config->basicAuthUsername,
                (string) $config->basicAuthPassword,
            ),
            'OAUTH' => self::oauth($config, $tokenHttpClient),
            default => new NoneAuthProvider(),
        };
    }

    private static function oauth(
        CamundaConfiguration $config,
        ?ClientInterface $tokenHttpClient,
    ): OAuthClientCredentialsProvider {
        $psr17 = new Psr17Factory();
        return new OAuthClientCredentialsProvider(
            httpClient: $tokenHttpClient ?? Psr18ClientDiscovery::find(),
            requestFactory: $psr17,
            streamFactory: $psr17,
            tokenUrl: $config->oauthUrl,
            clientId: (string) $config->clientId,
            clientSecret: (string) $config->clientSecret,
            audience: $config->tokenAudience,
        );
    }
}
