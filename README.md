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

    $result = $client->deployResourcesFromFiles('order-process.bpmn');
    // ...
}
```

<!-- snippet-source: examples/readme.php | regions: ReadmeAsyncClient -->
```php
function readme_async_client(): void
{
    $client = CamundaAsyncClient::fromEnvironment();

    $client->deployResourcesFromFilesAsync('order-process.bpmn')
        ->then(static function ($result): void {
            // handle the DeploymentResult once the request resolves
        })
        ->wait();
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

<!-- snippet-source: examples/readme.php | regions: ReadmeDeployResources -->
```php
function readme_deploy_resources(): void
{
    $client = CamundaClient::fromEnvironment();

    $result = $client->deployResourcesFromFiles('order-process.bpmn', 'pricing.dmn');
    // $result is a DeploymentResult (or ProblemDetail on a handled error).
}
```

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

## Accessing every operation

Every one of the 243 API operations is exposed as a method directly on the client — the
flat facade — so you rarely need to reach for an API group:

```php
$client = CamundaClient::fromEnvironment();

$topology = $client->getTopology();
$result   = $client->createProcessInstance($instruction);

// The async client exposes the same surface, returning promises:
$async = CamundaAsyncClient::fromEnvironment();
$async->getTopology()->then(fn ($topology) => /* ... */);
```

Beyond the ergonomic helpers and the flat facade, every API group is also reachable
through the typed `api()` accessor:

```php
use Camunda\Orchestration\Api\Api\ProcessInstanceApi;

$processInstances = $client->api(ProcessInstanceApi::class);
$result = $processInstances->createProcessInstance($instruction);
```

See the [`examples/`](examples/) directory for compilable, static-analysed usage of the most common operations.

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) and [AGENTS.md](AGENTS.md) for the development workflow, the generation pipeline, and the semantic-type machinery.

## License

Apache-2.0. See [LICENSE](LICENSE).
