<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance\Runtime;

use Camunda\Orchestration\Api\Api\ClusterApi;
use Camunda\Orchestration\Api\Model\DeploymentResult;
use Camunda\Orchestration\CamundaAsyncClient;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Config\CamundaConfiguration;
use Camunda\Orchestration\Config\ConfigResolver;
use Camunda\Orchestration\Exception\ConfigurationException;
use Camunda\Orchestration\Tests\Support\EnvironmentSandbox;
use Camunda\Orchestration\Tests\Support\RecordingGuzzle;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * How the sync and async clients assemble their default Guzzle client from a configuration:
 * auth middleware, mTLS options and environment-based construction.
 */
final class ClientHttpWiringTest extends TestCase
{
    private EnvironmentSandbox $env;

    protected function setUp(): void
    {
        $this->env = new EnvironmentSandbox();
        $this->env->enter();
    }

    protected function tearDown(): void
    {
        $this->env->leave();
    }

    /**
     * @return iterable<string, array{class-string<CamundaClient|CamundaAsyncClient>}>
     */
    public static function clientClasses(): iterable
    {
        yield 'sync' => [CamundaClient::class];
        yield 'async' => [CamundaAsyncClient::class];
    }

    /**
     * @param class-string<CamundaClient|CamundaAsyncClient> $clientClass
     */
    #[DataProvider('clientClasses')]
    public function testDefaultHttpClientStampsBasicAuthOnEveryRequest(string $clientClass): void
    {
        $client = $clientClass::fromConfiguration(new CamundaConfiguration(
            restAddress: 'https://cluster.example.test/v2',
            authStrategy: 'BASIC',
            basicAuthUsername: 'demo',
            basicAuthPassword: 'secret',
        ));
        $sent = $this->interceptTransport($this->guzzleOf($client), [new Response(200, [], '{}')]);

        $this->guzzleOf($client)->request('GET', 'https://cluster.example.test/v2/topology');

        self::assertCount(1, $sent);
        $request = $sent[0];
        self::assertInstanceOf(RequestInterface::class, $request);
        self::assertSame('Basic ' . base64_encode('demo:secret'), $request->getHeaderLine('Authorization'));
    }

    /**
     * @param class-string<CamundaClient|CamundaAsyncClient> $clientClass
     */
    #[DataProvider('clientClasses')]
    public function testDefaultHttpClientIsAnonymousForNoneStrategy(string $clientClass): void
    {
        $client = $clientClass::fromConfiguration(new CamundaConfiguration('https://c.test/v2', 'NONE'));
        $sent = $this->interceptTransport($this->guzzleOf($client), [new Response(200, [], '{}')]);

        $this->guzzleOf($client)->request('GET', 'https://c.test/v2/topology');

        $request = $sent[0];
        self::assertInstanceOf(RequestInterface::class, $request);
        self::assertFalse($request->hasHeader('Authorization'));
    }

    /**
     * @param class-string<CamundaClient|CamundaAsyncClient> $clientClass
     */
    #[DataProvider('clientClasses')]
    public function testDefaultHttpClientReturnsErrorStatusesInsteadOfThrowing(string $clientClass): void
    {
        $client = $clientClass::fromConfiguration(new CamundaConfiguration('https://c.test/v2', 'NONE'));
        $this->interceptTransport($this->guzzleOf($client), [new Response(503, [], '{"title":"x"}')]);

        $response = $this->guzzleOf($client)->request('GET', 'https://c.test/v2/topology');

        self::assertSame(503, $response->getStatusCode());
    }

    /**
     * @param class-string<CamundaClient|CamundaAsyncClient> $clientClass
     */
    #[DataProvider('clientClasses')]
    public function testMtlsOptionsAreWiredIntoTheDefaultHttpClient(string $clientClass): void
    {
        $client = $clientClass::fromConfiguration(new CamundaConfiguration(
            restAddress: 'https://c.test/v2',
            authStrategy: 'NONE',
            mtlsCertPath: '/certs/client.pem',
            mtlsKeyPath: '/certs/client.key',
            mtlsCaPath: '/certs/ca.pem',
        ));

        $options = $this->guzzleOptions($this->guzzleOf($client));

        self::assertSame('/certs/client.pem', $options['cert']);
        self::assertSame('/certs/client.key', $options['ssl_key']);
        self::assertSame('/certs/ca.pem', $options['verify']);
    }

    /**
     * @param class-string<CamundaClient|CamundaAsyncClient> $clientClass
     */
    #[DataProvider('clientClasses')]
    public function testMtlsKeyPassphraseIsPairedWithBothCertAndKey(string $clientClass): void
    {
        $client = $clientClass::fromConfiguration(new CamundaConfiguration(
            restAddress: 'https://c.test/v2',
            authStrategy: 'NONE',
            mtlsCertPath: '/certs/client.pem',
            mtlsKeyPath: '/certs/client.key',
            mtlsKeyPassphrase: 'p4ss',
        ));

        $options = $this->guzzleOptions($this->guzzleOf($client));

        self::assertSame(['/certs/client.pem', 'p4ss'], $options['cert']);
        self::assertSame(['/certs/client.key', 'p4ss'], $options['ssl_key']);
        self::assertTrue($options['verify'], 'without a CA path Guzzle keeps its default peer verification');
    }

    /**
     * @param class-string<CamundaClient|CamundaAsyncClient> $clientClass
     */
    #[DataProvider('clientClasses')]
    public function testWithoutMtlsConfigurationNoTlsOptionsAreSet(string $clientClass): void
    {
        $client = $clientClass::fromConfiguration(new CamundaConfiguration('https://c.test/v2', 'NONE'));

        $options = $this->guzzleOptions($this->guzzleOf($client));

        self::assertArrayNotHasKey('cert', $options);
        self::assertArrayNotHasKey('ssl_key', $options);
        self::assertTrue($options['verify'], 'peer verification must stay on by default (Guzzle default)');
    }

    /**
     * @param class-string<CamundaClient|CamundaAsyncClient> $clientClass
     */
    #[DataProvider('clientClasses')]
    public function testFromEnvironmentResolvesOverridesOverTheProcessEnvironment(string $clientClass): void
    {
        $this->env->set('CAMUNDA_REST_ADDRESS', 'https://from-env.example.test');
        $this->env->set('CAMUNDA_AUTH_STRATEGY', 'NONE');

        $client = $clientClass::fromEnvironment(['CAMUNDA_REST_ADDRESS' => 'https://override.example.test']);

        self::assertSame('https://override.example.test/v2', $client->configuration()->restAddress);
        self::assertSame('NONE', $client->configuration()->authStrategy);
    }

    /**
     * @param class-string<CamundaClient|CamundaAsyncClient> $clientClass
     */
    #[DataProvider('clientClasses')]
    public function testFromEnvironmentPropagatesInvalidConfiguration(string $clientClass): void
    {
        $this->expectException(ConfigurationException::class);

        $clientClass::fromEnvironment(['CAMUNDA_AUTH_STRATEGY' => 'KERBEROS']);
    }

    /**
     * @param class-string<CamundaClient|CamundaAsyncClient> $clientClass
     */
    #[DataProvider('clientClasses')]
    public function testApiGroupInstancesAreSharedAndConfigurationIsExposed(string $clientClass): void
    {
        $config = ConfigResolver::resolve(overrides: ['CAMUNDA_AUTH_STRATEGY' => 'NONE'], environment: []);
        $client = $clientClass::fromConfiguration($config, new GuzzleClient(['handler' => new MockHandler([])]));

        self::assertSame($config, $client->configuration());
        self::assertSame($client->api(ClusterApi::class), $client->api(ClusterApi::class));
    }

    public function testDeployResourcesFromFilesRejectsEmptyAndUnreadablePaths(): void
    {
        $client = CamundaClient::fromConfiguration(new CamundaConfiguration('https://c.test/v2', 'NONE'));

        try {
            $client->deployResourcesFromFiles();
            self::fail('Expected a ConfigurationException for no paths.');
        } catch (ConfigurationException $e) {
            self::assertStringContainsString('at least one file path', $e->getMessage());
        }

        try {
            $client->deployResourcesFromFiles('/definitely/not/there.bpmn');
            self::fail('Expected a ConfigurationException for an unreadable path.');
        } catch (ConfigurationException $e) {
            self::assertStringContainsString('/definitely/not/there.bpmn', $e->getMessage());
        }
    }

    public function testAsyncDeployResourcesRejectsEmptyAndUnreadablePaths(): void
    {
        $client = CamundaAsyncClient::fromConfiguration(new CamundaConfiguration('https://c.test/v2', 'NONE'));

        try {
            $client->deployResourcesFromFilesAsync();
            self::fail('Expected a ConfigurationException for no paths.');
        } catch (ConfigurationException $e) {
            self::assertStringContainsString('at least one file path', $e->getMessage());
        }

        try {
            $client->deployResourcesFromFilesAsync('/definitely/not/there.bpmn');
            self::fail('Expected a ConfigurationException for an unreadable path.');
        } catch (ConfigurationException $e) {
            self::assertStringContainsString('/definitely/not/there.bpmn', $e->getMessage());
        }
    }

    public function testAsyncDeploymentSendsTheFilesWithTheDefaultTenantAndResolvesToTheResult(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'orchestration-bpmn-');
        self::assertIsString($path);
        file_put_contents($path, '<definitions id="async-deploy" />');
        $http = new RecordingGuzzle();
        $client = CamundaAsyncClient::fromConfiguration(
            ConfigResolver::resolve(
                overrides: ['CAMUNDA_AUTH_STRATEGY' => 'NONE', 'CAMUNDA_TENANT_ID' => 'acme'],
                environment: [],
            ),
            $http->guzzle([new Response(
                200,
                ['Content-Type' => 'application/json'],
                (string) json_encode(['deploymentKey' => '2251799813685249', 'tenantId' => 'acme', 'deployments' => []]),
            )]),
        );

        try {
            $result = $client->deployResourcesFromFilesAsync($path)->wait();
        } finally {
            unlink($path);
        }

        self::assertInstanceOf(DeploymentResult::class, $result);
        self::assertCount(1, $http->requests);
        $body = (string) $http->requests[0]->getBody();
        self::assertStringContainsString('async-deploy', $body);
        self::assertStringContainsString("\r\nacme\r\n", $body);
    }

    /**
     * The default client is built privately; read it back from the SDK client.
     */
    private function guzzleOf(CamundaClient|CamundaAsyncClient $client): ClientInterface
    {
        $property = new \ReflectionProperty($client, 'httpClient');
        $guzzle = $property->getValue($client);
        self::assertInstanceOf(ClientInterface::class, $guzzle);

        return $guzzle;
    }

    /**
     * @return array<string, mixed>
     */
    private function guzzleOptions(ClientInterface $guzzle): array
    {
        self::assertInstanceOf(GuzzleClient::class, $guzzle);
        $property = new \ReflectionProperty(GuzzleClient::class, 'config');
        /** @var array<string, mixed> $config */
        $config = $property->getValue($guzzle);

        return $config;
    }

    /**
     * Swap the real transport at the bottom of the default handler stack for a mock, keeping the
     * middleware the SDK pushed (auth) in place, and return the list that records sent requests.
     *
     * @param list<Response> $responses
     * @return \ArrayObject<int, RequestInterface>
     */
    private function interceptTransport(ClientInterface $guzzle, array $responses): \ArrayObject
    {
        $config = $this->guzzleOptions($guzzle);
        $stack = $config['handler'];
        self::assertInstanceOf(HandlerStack::class, $stack);
        $stack->setHandler(new MockHandler($responses));

        /** @var \ArrayObject<int, RequestInterface> $sent */
        $sent = new \ArrayObject();
        $stack->push(static function (callable $next) use ($sent): callable {
            return static function (RequestInterface $request, array $options) use ($next, $sent) {
                $sent->append($request);

                return $next($request, $options);
            };
        }, 'record');

        return $sent;
    }
}
