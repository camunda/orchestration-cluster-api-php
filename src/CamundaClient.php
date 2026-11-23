<?php

declare(strict_types=1);

namespace Camunda\Orchestration;

use Camunda\Orchestration\Api\Api\ResourceApi;
use Camunda\Orchestration\Api\Configuration as ApiConfiguration;
use Camunda\Orchestration\Api\Model\DeploymentResult;
use Camunda\Orchestration\Api\Model\ProblemDetail;
use Camunda\Orchestration\Auth\AuthProvider;
use Camunda\Orchestration\Auth\AuthProviderFactory;
use Camunda\Orchestration\Auth\OAuthClientCredentialsProvider;
use Camunda\Orchestration\Config\CamundaConfiguration;
use Camunda\Orchestration\Config\ConfigResolver;
use Camunda\Orchestration\Exception\ConfigurationException;
use Camunda\Orchestration\Http\AuthMiddleware;
use Camunda\Orchestration\Http\ProcessGuardedClient;
use Camunda\Orchestration\Worker\JobWorker;
use Camunda\Orchestration\Worker\JobWorkerOptions;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use Http\Discovery\Psr18ClientDiscovery;

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
    use GeneratedOperations;

    private ApiConfiguration $apiConfig;

    /** @var array<class-string, object> */
    private array $apiCache = [];

    /** PID of the process that built this client (issue #190). */
    private readonly int $ownerPid;

    /** PID of the process that last got the cross-process warning, so it warns once per process. */
    private ?int $warnedPid = null;

    /**
     * @param (\Closure(): ClientInterface)|null $httpClientFactory Builds a new, independent HTTP
     *   client equivalent to `$httpClient`. Forked job workers call it in each child process so
     *   that the child never reuses the parent's connections (issue #187). When omitted, a child
     *   keeps using `$httpClient`.
     */
    public function __construct(
        private readonly CamundaConfiguration $config,
        private readonly ClientInterface $httpClient,
        private readonly ?\Closure $httpClientFactory = null,
    ) {
        $this->ownerPid = (int) getmypid();
        $this->apiConfig = (new ApiConfiguration())
            ->setHost($config->restAddress)
            ->setUserAgent('camunda-orchestration-php/' . Version::VALUE)
            ->setBooleanFormatForQueryString(ApiConfiguration::BOOLEAN_FORMAT_STRING);
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
        if ($httpClient !== null) {
            return new self($config, $httpClient);
        }

        $auth = AuthProviderFactory::fromConfiguration($config);
        return new self(
            $config,
            self::buildHttpClient($config, $auth),
            static fn (): ClientInterface => self::buildHttpClient($config, self::childAuth($auth)),
        );
    }

    /**
     * A client for use in a forked child process (issue #187).
     *
     * A forked child inherits the parent's HTTP client, including its open keep-alive
     * connections. Using them from several processes interleaves requests and responses on one
     * socket. The returned client has the same configuration but its own HTTP transport and so
     * its own connections, and a copy of the current API configuration. If this client was built around a caller-supplied HTTP client and no
     * factory, there is nothing to rebuild from and `$this` is returned.
     *
     * Call it in the child, after `pcntl_fork()`.
     */
    public function forChildProcess(): self
    {
        if ($this->httpClientFactory === null) {
            return $this;
        }

        $child = new self($this->config, ($this->httpClientFactory)(), $this->httpClientFactory);
        // Keep changes made through api(...)->getConfig() (e.g. an overridden host).
        $child->apiConfig = clone $this->apiConfig;

        return $child;
    }

    /**
     * The auth provider a child process uses: the OAuth provider keeps its cached token but
     * gets a new token HTTP client, so a refresh in the child does not reuse the parent's
     * connection either.
     */
    private static function childAuth(AuthProvider $auth): AuthProvider
    {
        return $auth instanceof OAuthClientCredentialsProvider
            ? $auth->withTokenHttpClient(Psr18ClientDiscovery::find())
            : $auth;
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
     * When this client was built with an HTTP client factory (e.g. via {@see self::fromEnvironment()}
     * or {@see self::fromConfiguration()} without a custom HTTP client), sending a request through it
     * (including through an API group retrieved before a fork) from a different
     * process than the one that constructed it raises one {@see \E_USER_WARNING} per process so
     * forked children sharing the parent's connection are caught early. Clients built around a
     * caller-supplied HTTP client with no factory are excluded because {@see self::forChildProcess()}
     * returns `$this` for them.
     *
     * @template T of object
     * @param class-string<T> $apiClass
     * @return T
     */
    public function api(string $apiClass): object
    {
        if (!isset($this->apiCache[$apiClass])) {
            $transport = $this->httpClientFactory === null
                ? $this->httpClient
                : new ProcessGuardedClient($this->httpClient, $this->checkOwningProcess(...));
            /** @var object $instance */
            $instance = new $apiClass($transport, $this->apiConfig);
            $this->apiCache[$apiClass] = $instance;
        }
        /** @var T */
        return $this->apiCache[$apiClass];
    }

    /** Warn once per process when a request is sent from a process other than the owner. */
    private function checkOwningProcess(): void
    {
        $pid = (int) getmypid();
        if ($this->ownerPid !== $pid && $this->warnedPid !== $pid) {
            $this->warnForeignProcess($pid);
        }
    }

    /**
     * Warn that this client is used in a process other than the one that built it, typically a
     * forked job-worker child using a client captured from the parent (issue #190). Its HTTP
     * transport, and its keep-alive connections, are the parent's, so concurrent children can
     * read each other's responses. Raised once per client per process; the request still goes on.
     */
    private function warnForeignProcess(int $pid): void
    {
        $this->warnedPid = $pid;
        trigger_error(sprintf(
            'A CamundaClient built in process %d is being used in process %d (a forked child?). '
            . 'It sends requests over the HTTP connections inherited from the parent, so concurrent '
            . 'children can read each other\'s responses (e.g. spurious 404s). In a job handler use '
            . '$action->client(); elsewhere call $client->forChildProcess() in the child.',
            $this->ownerPid,
            $pid,
        ), \E_USER_WARNING);
    }

    /** The resolved configuration backing this client. */
    public function configuration(): CamundaConfiguration
    {
        return $this->config;
    }

    /**
     * A dump-safe view of the client (issue #111).
     *
     * The underlying Guzzle client is summarised by class name rather than dumped: its
     * options carry the mTLS key passphrase, which `var_dump()` would otherwise reach
     * through this object. The configuration redacts its own credentials.
     *
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return [
            'configuration' => $this->config,
            'httpClient' => $this->httpClient::class,
            'apis' => array_keys($this->apiCache),
        ];
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

        $tenantId = $this->config->defaultTenantId();

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
