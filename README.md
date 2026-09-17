# Camunda Orchestration Cluster API – PHP SDK (Technical Preview)

<!-- WARNING: The content and specific structure of this file drives Docusaurus generation in camunda-docs. Also, code examples are injected during build. Please refer to AGENTS.md before editing. -->
<!-- docs:cut:start -->
[![Packagist Version](https://img.shields.io/packagist/v/camunda/orchestration-cluster-api)](https://packagist.org/packages/camunda/orchestration-cluster-api)
[![PHP Version](https://img.shields.io/packagist/php-v/camunda/orchestration-cluster-api)](https://packagist.org/packages/camunda/orchestration-cluster-api)
<!-- docs:cut:end -->

A fully typed PHP client for the [Camunda 8 Orchestration Cluster REST API](https://docs.camunda.io/docs/apis-tools/camunda-api-rest/camunda-api-rest-overview/). Fully compliant with the Camunda OpenAPI spec, with a hand-written runtime for authentication, configuration, and job workers.

- **Sync and async** — `CamundaClient` (synchronous) and `CamundaAsyncClient` (promise-based)
- **Semantic value objects** — distinct types for every identifier, checked at `PHPStan` level `max`
- **Zero-config** — reads `CAMUNDA_*` environment variables (12-factor style)
- **Job workers** — long-poll workers with optional `pcntl` process forking
- **OAuth & Basic auth** — pluggable authentication with automatic token management
- **PSR standards** — PSR-4 autoloading, PSR-18/PSR-7 HTTP via Guzzle

## Support status

This is a **Technical Preview** of the PHP client for the Camunda 8 Orchestration Cluster
API, provided for evaluation and feedback. It gives you a stable foundation to build on
now, with a clear path to full support: we do not intend to make breaking changes to the
application integration surface, but we do not guarantee that we will not. The SDK will
become fully supported with an SLA in a future release.

We don't anticipate major changes — and
[your feedback](https://github.com/camunda/orchestration-cluster-api-php/issues) is what
helps close that gap.

> As a Technical Preview, the API surface may still evolve before it is declared fully
> supported. Pin a specific version if you need stability.

## Installing the SDK to your project

### Requirements

- PHP 8.2 or later
- [Composer](https://getcomposer.org/)
- `ext-json`; `ext-pcntl` is optional (enables forked job workers)

### Stable release (recommended for production)

The stable version tracks the latest supported Camunda server release.

```bash
composer require camunda/orchestration-cluster-api
```

### Versioning

This SDK has a different release cadence from the Camunda server. The major version of the SDK signals a 1:1 type coherence with the server API for a Camunda minor release.

SDK version `n.y.z` → server version `8.n`, so the type surface of SDK version `10.y.z` matches the API surface of Camunda `8.10`.

Using the matching SDK major version for the server minor version provides the strongest guarantees about runtime reliability.

## Using the SDK

The SDK provides two clients with matching surfaces:

- **`CamundaClient`** — synchronous. Every method blocks until the response arrives. Use it in scripts, CLI tools, and traditional request/response applications.
- **`CamundaAsyncClient`** — asynchronous. Every operation returns a Guzzle `PromiseInterface`. Use it when you want to issue concurrent requests.

<!-- snippet-source: examples/readme.php | regions: ReadmeSyncClient -->
```php
function readme_sync_client(): void
{
    $client = CamundaClient::fromEnvironment();

    $result = $client->deployResourcesFromFiles(__DIR__ . '/resources/order-process.bpmn');
    // ...
}
```

<!-- snippet-source: examples/readme.php | regions: ReadmeAsyncClient -->
```php
function readme_async_client(): void
{
    $client = CamundaAsyncClient::fromEnvironment();

    $client->deployResourcesFromFilesAsync(__DIR__ . '/resources/order-process.bpmn')
        ->then(static function ($result): void {
            // handle the DeploymentResult once the request resolves
        })
        ->wait();
}
```

### Parallel async reads

<!-- snippet-source: examples/readme.php | regions: ReadmeParallelAsyncReads -->
```php
function parallel_async_reads(CamundaAsyncClient $client): void
{
    // Requests are issued before either promise is awaited.
    $topologyPromise = $client->getTopology();
    $definitionsPromise = $client->searchProcessDefinitions();

    $topology = $topologyPromise->wait();
    $definitions = $definitionsPromise->wait();

    if ($topology instanceof TopologyResponse) {
        printf("Connected to %d broker(s).\n", count($topology->getBrokers()));
    }
    if ($definitions instanceof ProcessDefinitionSearchQueryResult) {
        printf("Found %d process definitions.\n", count($definitions->getItems()));
    }
}
```

## Semantic Types

The SDK uses distinct value objects for identifiers like `ProcessDefinitionId`, `ProcessInstanceKey`, `JobKey`, `TenantId`, and so on, defined in the `Camunda\Orchestration\Semantic` namespace.

### Why they exist

Camunda's API has many operations that accept string keys. Without semantic types it is easy to accidentally pass a process-instance key where a process-definition id is expected. When everything is a `string`, static analysis cannot help you.

Semantic types make these identifiers **distinct at the type level**. PHPStan flags an error if you pass the wrong identifier type, catching bugs before runtime. Each value object validates its format on construction and implements `Stringable` and `JsonSerializable`, so it serializes transparently to and from JSON.

### How to use them

<!-- snippet-source: examples/readme.php | regions: ReadmeSemanticTypes -->
```php
function readme_semantic_types(): void
{
    // Identifiers are distinct value objects — you cannot accidentally pass a
    // process-definition id where a tenant id is expected.
    $instruction = (new ProcessInstanceCreationInstructionById())
        ->setProcessDefinitionId(ProcessDefinitionId::of('order-process'))
        ->setVariables(['orderId' => 'ORD-42']);

    // Value objects validate their format on construction and stringify cleanly.
    $definitionId = new ProcessDefinitionId('order-process');
    echo (string) $definitionId, "\n";
}
```

When you construct a model from an array, raw strings are automatically lifted into their semantic value objects, so you can stay ergonomic where you want to.

## Configuration

### Zero-config from the environment

The client reads the standard `CAMUNDA_*` environment variables and auto-detects the authentication strategy (`NONE`, `BASIC`, or `OAUTH`).

<!-- snippet-source: examples/readme.php | regions: ReadmeZeroConfig -->
```php
function readme_zero_config(): void
{
    // Reads CAMUNDA_REST_ADDRESS and auto-detects the auth strategy from the
    // ambient environment (NONE / BASIC / OAUTH).
    $client = CamundaClient::fromEnvironment();
}
```

### Programmatic configuration

<!-- snippet-source: examples/readme.php | regions: ReadmeProgrammaticConfig -->
```php
function readme_programmatic_config(): void
{
    $config = new CamundaConfiguration(
        restAddress: 'https://my-cluster.example.com/v2',
        authStrategy: 'OAUTH',
        clientId: 'my-client-id',
        clientSecret: 'my-client-secret',
    );

    $client = CamundaClient::fromConfiguration($config);
}
```

### Basic auth

<!-- snippet-source: examples/readme.php | regions: ReadmeBasicAuth -->
```php
function readme_basic_auth(): void
{
    $client = CamundaClient::fromEnvironment([
        'CAMUNDA_AUTH_STRATEGY' => 'BASIC',
        'CAMUNDA_BASIC_AUTH_USERNAME' => 'demo',
        'CAMUNDA_BASIC_AUTH_PASSWORD' => 'demo',
    ]);
}
```

### Loading a `.env` file

Set `CAMUNDA_LOAD_ENVFILE=true` to read `.env` in the working directory, or set
it to an explicit path. This optional capability requires
[`vlucas/phpdotenv`](https://packagist.org/packages/vlucas/phpdotenv).

<!-- snippet-source: examples/readme.php | regions: ReadmeEnvFileClient -->
```php
function env_file_client(): CamundaClient
{
    // Set CAMUNDA_LOAD_ENVFILE=true (or a path) before starting PHP. Real
    // environment variables and explicit overrides still take precedence.
    return CamundaClient::fromEnvironment();
}
```

### Mutual TLS

<!-- snippet-source: examples/readme.php | regions: ReadmeMtlsClient -->
```php
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
```

### Custom Guzzle middleware

<!-- snippet-source: examples/readme.php | regions: ReadmeCustomHttpClient -->
```php
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
```

### Supported environment variables

<!-- BEGIN_CONFIG_REFERENCE -->

| Variable | Default | Description |
| --- | --- | --- |
| `CAMUNDA_REST_ADDRESS` | `http://localhost:8080/v2` | Cluster REST endpoint. `/v2` is appended automatically when absent. |
| `ZEEBE_REST_ADDRESS` | — | Legacy alias for `CAMUNDA_REST_ADDRESS` (used only when the latter is unset). |
| `CAMUNDA_AUTH_STRATEGY` | — | `NONE`, `BASIC`, or `OAUTH`. Auto-detected from the supplied credentials when unset. |
| `CAMUNDA_CLIENT_ID` | — | OAuth client id. |
| `CAMUNDA_CLIENT_SECRET` | — | OAuth client secret. |
| `CAMUNDA_CLIENT_AUTH_CLIENTID` | — | Legacy alias for `CAMUNDA_CLIENT_ID`. |
| `CAMUNDA_CLIENT_AUTH_CLIENTSECRET` | — | Legacy alias for `CAMUNDA_CLIENT_SECRET`. |
| `CAMUNDA_OAUTH_URL` | `https://login.cloud.camunda.io/oauth/token` | OAuth token endpoint. |
| `CAMUNDA_TOKEN_AUDIENCE` | `zeebe.camunda.io` | OAuth token audience. |
| `CAMUNDA_BASIC_AUTH_USERNAME` | — | Basic-auth username. |
| `CAMUNDA_BASIC_AUTH_PASSWORD` | — | Basic-auth password. |
| `CAMUNDA_TENANT_ID` | — | Default tenant id applied to tenant-aware operations. |
| `CAMUNDA_TENANT_IDS` | — | Comma-separated default tenant ids (e.g. for job activation). |
| `CAMUNDA_SDK_LOG_LEVEL` | `warn` | SDK log level (`error`, `warn`, `info`, `debug`). |
| `CAMUNDA_WORKER_MAX_CONCURRENT_JOBS` | `32` | Default maximum number of jobs a worker activates at once. |
| `CAMUNDA_WORKER_TIMEOUT` | `60000` | Default job activation timeout, in milliseconds. |
| `CAMUNDA_WORKER_REQUEST_TIMEOUT` | `10000` | Default long-poll request timeout, in milliseconds. |
| `CAMUNDA_WORKER_NAME` | — | Default worker name reported when activating jobs. |
| `CAMUNDA_MTLS_CERT_PATH` | — | Path to the client certificate for mutual TLS. |
| `CAMUNDA_MTLS_KEY_PATH` | — | Path to the client private key for mutual TLS. |
| `CAMUNDA_MTLS_CA_PATH` | — | Path to the CA bundle used to verify the server certificate. |
| `CAMUNDA_MTLS_KEY_PASSPHRASE` | — | Passphrase protecting the mTLS client key, if any. |
| `CAMUNDA_LOAD_ENVFILE` | — | Load configuration from a `.env` file. Set to `true` or a file path. |

<!-- END_CONFIG_REFERENCE -->

## Deploying resources

Pass a readable filesystem path for every BPMN, DMN, or Form resource. Anchor
paths with `__DIR__` so deployment is independent of the shell's current
working directory. The repository includes [runnable deployment
resources](examples/resources/); replace those paths with your application's
models.

<!-- snippet-source: examples/readme.php | regions: ReadmeDeployResources -->
```php
function readme_deploy_resources(): void
{
    $client = CamundaClient::fromEnvironment();

    $result = $client->deployResourcesFromFiles(
        __DIR__ . '/resources/order-process.bpmn',
        __DIR__ . '/resources/pricing.dmn',
    );
    // $result is a DeploymentResult (or ProblemDetail on a handled error).
}
```

`deployResourcesFromFiles()` returns a `DeploymentResult` on success and a
`ProblemDetail` for a handled API error. It throws a `ConfigurationException`
when a local resource path cannot be read.

## Job workers

A job worker long-polls for jobs of a given type and dispatches each to your handler. Returning an array auto-completes the job with those variables; you can also complete, fail, or raise a BPMN error explicitly via the handler's `JobActionClient`.

<!-- snippet-source: examples/readme.php | regions: ReadmeJobWorker -->
```php
function readme_job_worker(): void
{
    $client = CamundaClient::fromEnvironment();

    $worker = $client->createJobWorker(new JobWorkerOptions(
        type: 'payment-processing',
        maxJobs: 5,
        timeoutMs: 30_000,
    ));

    $worker->run(function (ActivatedJobResult $job, JobActionClient $action): array {
        // ... perform the work ...
        return ['status' => 'paid'];
    });
}
```

When `ext-pcntl` is available and `forked: true` is set, each job is processed in its own child process, bounded to `maxJobs` concurrent children.

### Object-oriented handlers

<!-- snippet-source: examples/readme.php | regions: ReadmeObjectJobHandler -->
```php
final class PaymentJobHandler implements JobHandler
{
    public function handle(ActivatedJobResult $job, JobActionClient $action): ?array
    {
        $variables = $job->getVariables();
        if (!isset($variables['paymentId'])) {
            $action->error('MISSING_PAYMENT_ID', 'The payment job has no payment id.');
            return null;
        }

        return ['paymentStatus' => 'approved'];
    }
}

function object_job_handler(CamundaClient $client): void
{
    $worker = $client->createJobWorker(new JobWorkerOptions(type: 'process-payment'));
    $worker->run(new PaymentJobHandler());
}
```

## Accessing every operation

Every one of the 243 API operations is exposed as a method directly on the client — the
flat facade — so you rarely need to reach for an API group:

<!-- snippet-source: examples/readme.php | regions: ReadmeFlatFacade -->
```php
function readme_flat_facade(): void
{
    $client = CamundaClient::fromEnvironment();
    $instruction = new ProcessInstanceCreationInstructionById([
        'processDefinitionId' => 'order-process',
    ]);

    $topology = $client->getTopology();
    $result = $client->createProcessInstance($instruction);

    $async = CamundaAsyncClient::fromEnvironment();
    $async->getTopology()
        ->then(static function ($asyncTopology): void {
            // handle the asynchronous topology response
        })
        ->wait();
}
```

Beyond the ergonomic helpers and the flat facade, every API group is also reachable
through the typed `api()` accessor:

<!-- snippet-source: examples/readme.php | regions: ReadmeApiAccessor -->
```php
function readme_api_accessor(): void
{
    $client = CamundaClient::fromEnvironment();
    $instruction = new ProcessInstanceCreationInstructionById([
        'processDefinitionId' => 'order-process',
    ]);

    $processInstances = $client->api(ProcessInstanceApi::class);
    $result = $processInstances->createProcessInstance($instruction);
}
```

See the [`examples/`](examples/) directory for compilable, static-analysed usage of every REST API operation.

## Advanced runnable examples

The [`examples/advanced/`](examples/advanced/) directory contains self-verifying
local-cluster scenarios for a test drive, resilient worker behavior, idempotent
message correlation, and PHP process-forking.

## Live example showcase

With a disposable Camunda cluster already running, execute the six scenario
showcase and create its HTML proof report:

```sh
CAMUNDA_REST_ADDRESS=http://localhost:8080 \
CAMUNDA_AUTH_STRATEGY=NONE \
  make example-showcase
```

The showcase runs focused workflow scenarios for deployment, process lifecycle,
job workers, user tasks, incidents, message correlation, and management
operations. The report indexes every source-backed snippet alongside the live
scenario that demonstrates its API area. It intentionally performs mutations
and cluster-management requests; never run it against a shared, staging, or
production cluster.

Run one scenario with `make example-showcase SCENARIO=<name>`. Open
[`docs/example-validation.html`](docs/example-validation.html) after a run for
the six outcomes and a searchable gallery of all source-backed snippets.

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) and [AGENTS.md](AGENTS.md) for the development workflow, the generation pipeline, and the semantic-type machinery.

## License

Apache-2.0. See [LICENSE](LICENSE).
