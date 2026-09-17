<?php

declare(strict_types=1);

namespace Camunda\Orchestration;

use Camunda\Orchestration\Api\Api\ResourceApi;
use Camunda\Orchestration\Api\Configuration as ApiConfiguration;
use Camunda\Orchestration\Auth\AuthProvider;
use Camunda\Orchestration\Auth\AuthProviderFactory;
use Camunda\Orchestration\Config\CamundaConfiguration;
use Camunda\Orchestration\Config\ConfigResolver;
use Camunda\Orchestration\Exception\ConfigurationException;
use Camunda\Orchestration\Http\AuthMiddleware;
use Camunda\Orchestration\Http\OperationHost;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * The Camunda 8 Orchestration Cluster SDK — asynchronous client.
 *
 * `CamundaAsyncClient` mirrors {@see CamundaClient} but returns Guzzle
 * {@see PromiseInterface} instances instead of blocking. Every generated API group
 * exposes `...Async` methods; access them through the typed `api()` accessor:
 *
 * ```php
 * $client = CamundaAsyncClient::fromEnvironment();
 * $client->api(ProcessInstanceApi::class)
 *     ->createProcessInstanceAsync($request)
 *     ->then(fn ($result) => printf("started %s\n", $result->getProcessInstanceKey()));
 * $client->wait();
 * ```
 */
final class CamundaAsyncClient
{
    use ApiAccessors;
    use GeneratedAsyncOperations;

    private readonly ApiConfiguration $apiConfig;

    /** @var array<class-string, object> */
    private array $apiCache = [];

    public function __construct(
        private readonly CamundaConfiguration $config,
        private readonly ClientInterface $httpClient,
    ) {
        $this->apiConfig = (new ApiConfiguration())
            ->setHost($config->restAddress)
            ->setOperationHostVariables(OperationHost::variables($config->restAddress))
            ->setUserAgent('camunda-orchestration-php/' . Version::VALUE);
    }

    /**
     * Build an async client from the ambient environment (and optional overrides).
     *
     * @param array<string, string> $overrides Configuration overrides (highest precedence).
     */
    public static function fromEnvironment(
        array $overrides = [],
        ?ClientInterface $httpClient = null,
    ): self {
        return self::fromConfiguration(ConfigResolver::resolve($overrides), $httpClient);
    }

    /**
     * Build an async client from an already-resolved configuration.
     */
    public static function fromConfiguration(
        CamundaConfiguration $config,
        ?ClientInterface $httpClient = null,
    ): self {
        $auth = AuthProviderFactory::fromConfiguration($config);
        $client = $httpClient ?? self::buildHttpClient($config, $auth);
        return new self($config, $client);
    }

    private static function buildHttpClient(CamundaConfiguration $config, AuthProvider $auth): ClientInterface
    {
        $stack = HandlerStack::create();
        $stack->push(new AuthMiddleware($auth), 'camunda_auth');

        /** @var array<string, mixed> $options */
        $options = [
            'handler' => $stack,
            'http_errors' => false,
        ];

        if ($config->mtlsCertPath !== null) {
            $options['cert'] = $config->mtlsKeyPassphrase !== null
                ? [$config->mtlsCertPath, $config->mtlsKeyPassphrase]
                : $config->mtlsCertPath;
        }
        if ($config->mtlsKeyPath !== null) {
            $options['ssl_key'] = $config->mtlsKeyPassphrase !== null
                ? [$config->mtlsKeyPath, $config->mtlsKeyPassphrase]
                : $config->mtlsKeyPath;
        }
        if ($config->mtlsCaPath !== null) {
            $options['verify'] = $config->mtlsCaPath;
        }

        return new GuzzleClient($options);
    }

    /**
     * Return the lazily-constructed, shared instance of a generated API group.
     *
     * @template T of object
     * @param class-string<T> $apiClass
     * @return T
     */
    public function api(string $apiClass): object
    {
        if (!isset($this->apiCache[$apiClass])) {
            /** @var object $instance */
            $instance = new $apiClass($this->httpClient, $this->apiConfig);
            $this->apiCache[$apiClass] = $instance;
        }
        /** @var T */
        return $this->apiCache[$apiClass];
    }

    /** The resolved configuration backing this client. */
    public function configuration(): CamundaConfiguration
    {
        return $this->config;
    }

    /**
     * Deploy one or more BPMN/DMN/Form resources from the filesystem, asynchronously.
     *
     * @param string ...$paths Absolute or relative paths to resource files.
     * @return PromiseInterface Resolves to a `DeploymentResult`.
     * @throws ConfigurationException If a path cannot be read.
     */
    public function deployResourcesFromFilesAsync(string ...$paths): PromiseInterface
    {
        if ($paths === []) {
            throw new ConfigurationException('deployResourcesFromFilesAsync() requires at least one file path.');
        }
        $resources = [];
        foreach ($paths as $path) {
            if (!is_file($path) || !is_readable($path)) {
                throw new ConfigurationException("Cannot read deployment resource: $path");
            }
            $resources[] = new \SplFileObject($path, 'r');
        }

        $tenantId = $this->config->tenantIds[0] ?? null;

        /** @var ResourceApi $resourceApi */
        $resourceApi = $this->api(ResourceApi::class);
        return $resourceApi->createDeploymentAsync($resources, $tenantId);
    }
}
