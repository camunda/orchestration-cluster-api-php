<?php

/**
 * Compilable usage examples for client configuration and authentication.
 */

declare(strict_types=1);

namespace Camunda\Orchestration\Examples;

use Camunda\Orchestration\Auth\AuthProviderFactory;
use Camunda\Orchestration\CamundaAsyncClient;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Config\CamundaConfiguration;
use Camunda\Orchestration\Http\AuthMiddleware;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;

// region ZeroConfigClient
function zero_config_client(): CamundaClient
{
    // Reads CAMUNDA_REST_ADDRESS / ZEEBE_REST_ADDRESS and auto-detects the auth
    // strategy (NONE / BASIC / OAUTH) from the environment.
    return CamundaClient::fromEnvironment();
}
// endregion ZeroConfigClient

// region OAuthClient
function oauth_client(): CamundaClient
{
    $config = new CamundaConfiguration(
        restAddress: 'https://my-cluster.example.com/v2',
        authStrategy: 'OAUTH',
        clientId: 'my-client-id',
        clientSecret: 'my-client-secret',
        tokenAudience: 'zeebe.camunda.io',
    );

    return CamundaClient::fromConfiguration($config);
}
// endregion OAuthClient

// region BasicAuthClient
function basic_auth_client(): CamundaClient
{
    return CamundaClient::fromEnvironment([
        'CAMUNDA_REST_ADDRESS' => 'http://localhost:8080',
        'CAMUNDA_AUTH_STRATEGY' => 'BASIC',
        'CAMUNDA_BASIC_AUTH_USERNAME' => 'demo',
        'CAMUNDA_BASIC_AUTH_PASSWORD' => 'demo',
    ]);
}
// endregion BasicAuthClient

// region SelfManagedOAuth
function self_managed_oauth_client(): CamundaClient
{
    return CamundaClient::fromEnvironment([
        'CAMUNDA_REST_ADDRESS' => 'http://localhost:8080',
        'CAMUNDA_AUTH_STRATEGY' => 'OAUTH',
        'CAMUNDA_CLIENT_ID' => 'zeebe',
        'CAMUNDA_CLIENT_SECRET' => 'secret',
        'CAMUNDA_OAUTH_URL' => 'http://localhost:18080/auth/realms/camunda-platform/protocol/openid-connect/token',
        'CAMUNDA_TOKEN_AUDIENCE' => 'zeebe-api',
    ]);
}
// endregion SelfManagedOAuth

// region AsyncClient
function async_client(): CamundaAsyncClient
{
    return CamundaAsyncClient::fromEnvironment();
}
// endregion AsyncClient

// region EnvFileClient
function env_file_client(): CamundaClient
{
    // Set CAMUNDA_LOAD_ENVFILE=true (or a path) before starting PHP. Real
    // environment variables and explicit overrides still take precedence.
    return CamundaClient::fromEnvironment();
}
// endregion EnvFileClient

// region MtlsClient
function mtls_client(): CamundaClient
{
    return CamundaClient::fromConfiguration(new CamundaConfiguration(
        restAddress: 'https://my-cluster.example.com/v2',
        authStrategy: 'OAUTH',
        clientId: 'my-client-id',
        clientSecret: 'my-client-secret',
        mtlsCertPath: '/run/secrets/client.crt',
        mtlsKeyPath: '/run/secrets/client.key',
        mtlsCaPath: '/run/secrets/cluster-ca.pem',
    ));
}
// endregion MtlsClient

// region CustomHttpClient
function custom_http_client(CamundaConfiguration $configuration): CamundaClient
{
    // Supplying a Guzzle client replaces the SDK-built stack. Add the SDK auth
    // middleware and any proxy, tracing, or mTLS options your application needs.
    $stack = HandlerStack::create();
    $stack->push(new AuthMiddleware(AuthProviderFactory::fromConfiguration($configuration)), 'camunda_auth');

    return CamundaClient::fromConfiguration(
        $configuration,
        new GuzzleClient(['handler' => $stack, 'http_errors' => false]),
    );
}
// endregion CustomHttpClient
