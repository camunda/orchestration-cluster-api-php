<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance\Runtime;

use Camunda\Orchestration\Auth\AuthProviderFactory;
use Camunda\Orchestration\Auth\BasicAuthProvider;
use Camunda\Orchestration\Auth\NoneAuthProvider;
use Camunda\Orchestration\Auth\OAuthClientCredentialsProvider;
use Camunda\Orchestration\Config\CamundaConfiguration;
use Camunda\Orchestration\Config\ConfigResolver;
use Camunda\Orchestration\Tests\Support\QueueHttpClient;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class AuthProviderFactoryTest extends TestCase
{
    public function testNoneStrategyYieldsAnonymousAccess(): void
    {
        $provider = AuthProviderFactory::fromConfiguration(new CamundaConfiguration('http://h/v2', 'NONE'));

        self::assertInstanceOf(NoneAuthProvider::class, $provider);
        self::assertSame([], $provider->getHeaders());
    }

    public function testBasicStrategyYieldsBasicHeader(): void
    {
        $provider = AuthProviderFactory::fromConfiguration(new CamundaConfiguration(
            restAddress: 'http://h/v2',
            authStrategy: 'BASIC',
            basicAuthUsername: 'demo',
            basicAuthPassword: 'pa:ss',
        ));

        self::assertInstanceOf(BasicAuthProvider::class, $provider);
        self::assertSame(['Authorization' => 'Basic ' . base64_encode('demo:pa:ss')], $provider->getHeaders());
    }

    public function testBasicHeaderEncodesNonAsciiCredentialsAsRawBytes(): void
    {
        $provider = new BasicAuthProvider('ünï', 'päss wörd');

        self::assertSame('Basic ' . base64_encode('ünï:päss wörd'), $provider->getHeaders()['Authorization']);
    }

    public function testOAuthStrategyBuildsAProviderThatUsesTheConfiguredEndpointAndCredentials(): void
    {
        $config = ConfigResolver::resolve(environment: [
            'CAMUNDA_CLIENT_ID' => 'my-client',
            'CAMUNDA_CLIENT_SECRET' => 'my-secret',
            'CAMUNDA_OAUTH_URL' => 'https://idp.example.test/token',
            'CAMUNDA_TOKEN_AUDIENCE' => 'my-audience',
        ]);
        $http = new QueueHttpClient(new Response(200, [], '{"access_token":"abc","expires_in":3600}'));

        $provider = AuthProviderFactory::fromConfiguration($config, $http);

        self::assertInstanceOf(OAuthClientCredentialsProvider::class, $provider);
        self::assertSame('Bearer abc', $provider->getHeaders()['Authorization']);
        self::assertCount(1, $http->requests);
        self::assertSame('https://idp.example.test/token', (string) $http->requests[0]->getUri());
        parse_str((string) $http->requests[0]->getBody(), $form);
        self::assertSame('my-client', $form['client_id']);
        self::assertSame('my-secret', $form['client_secret']);
        self::assertSame('my-audience', $form['audience']);
    }

    public function testOAuthStrategyUsesDefaultEndpointAndAudienceWhenUnset(): void
    {
        $config = ConfigResolver::resolve(environment: [
            'CAMUNDA_CLIENT_ID' => 'c',
            'CAMUNDA_CLIENT_SECRET' => 's',
        ]);
        $http = new QueueHttpClient(new Response(200, [], '{"access_token":"abc"}'));

        AuthProviderFactory::fromConfiguration($config, $http)->getHeaders();

        self::assertSame('https://login.cloud.camunda.io/oauth/token', (string) $http->requests[0]->getUri());
        parse_str((string) $http->requests[0]->getBody(), $form);
        self::assertSame('zeebe.camunda.io', $form['audience']);
    }

    public function testOAuthProviderDiscoversAPsr18ClientWhenNoneIsInjected(): void
    {
        $config = ConfigResolver::resolve(environment: [
            'CAMUNDA_CLIENT_ID' => 'c',
            'CAMUNDA_CLIENT_SECRET' => 's',
        ]);

        // Building must not touch the network: the token is only requested on first use.
        self::assertInstanceOf(
            OAuthClientCredentialsProvider::class,
            AuthProviderFactory::fromConfiguration($config),
        );
    }
}
