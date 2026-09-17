<?php

declare(strict_types=1);

namespace Camunda\Orchestration;

use Camunda\Orchestration\Api\Api\ResourceApi;
use Camunda\Orchestration\Api\Configuration as ApiConfiguration;
use Camunda\Orchestration\Api\Model\DeploymentResult;
use Camunda\Orchestration\Api\Model\ProblemDetail;
use Camunda\Orchestration\Auth\AuthProvider;
use Camunda\Orchestration\Auth\AuthProviderFactory;
use Camunda\Orchestration\Config\CamundaConfiguration;
use Camunda\Orchestration\Config\ConfigResolver;
use Camunda\Orchestration\Exception\ConfigurationException;
use Camunda\Orchestration\Http\AuthMiddleware;
use Camunda\Orchestration\Worker\JobWorker;
use Camunda\Orchestration\Worker\JobWorkerOptions;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;

/**
 * The Camunda 8 Orchestration Cluster SDK — synchronous client.
 *
 * `CamundaClient` is the ergonomic entry point. It resolves configuration, wires
 * authentication and the HTTP stack, and exposes:
 *
 *   - typed accessors for every API group (e.g. `$client->processInstance()`), and
 *   - ergonomic helpers (`deployResourcesFromFiles()`, `createJobWorker()`).
 *
 * Zero-config usage reads the standard `CAMUNDA_*` environment variables:
 *
 * ```php
 * $client = CamundaClient::fromEnvironment();
 * $deployment = $client->deployResourcesFromFiles('process.bpmn');
 * ```
 */
final class CamundaClient
{
    use ApiAccessors;

    private readonly ApiConfiguration $apiConfig;

    /** @var array<class-string, object> */
    private array $apiCache = [];

    public function __construct(
        private readonly CamundaConfiguration $config,
        private readonly ClientInterface $httpClient,
    ) {
        $this->apiConfig = (new ApiConfiguration())
            ->setHost($config->restAddress)
            ->setUserAgent('camunda-orchestration-php/' . Version::VALUE);
    }

    /**
     * Build a client from the ambient environment (and optional overrides).
     *
     * @param array<string, string> $overrides Configuration overrides (highest precedence).
     * @param ClientInterface|null $httpClient Custom Guzzle client. When omitted, one is built
     *   with authentication, mTLS and base-URI wiring derived from the configuration.
     */
    public static function fromEnvironment(
        array $overrides = [],
        ?ClientInterface $httpClient = null,
    ): self {
        $config = ConfigResolver::resolve($overrides);
        return self::fromConfiguration($config, $httpClient);
    }

    /**
     * Build a client from an already-resolved configuration.
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

    // ---------------------------------------------------------------------------
    // Ergonomic helpers
    // ---------------------------------------------------------------------------

    /**
     * Deploy one or more BPMN/DMN/Form resources from the filesystem in a single call.
     *
     * @param string ...$paths Absolute or relative paths to resource files.
     * @throws ConfigurationException If a path cannot be read.
     */
    public function deployResourcesFromFiles(string ...$paths): DeploymentResult|ProblemDetail
    {
        if ($paths === []) {
            throw new ConfigurationException('deployResourcesFromFiles() requires at least one file path.');
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
        return $resourceApi->createDeployment($resources, $tenantId);
    }

    /**
     * Create a long-polling job worker bound to this client.
     */
    public function createJobWorker(JobWorkerOptions $options): JobWorker
    {
        return new JobWorker($this, $options);
    }
}
