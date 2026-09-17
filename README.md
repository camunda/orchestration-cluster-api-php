# Camunda Orchestration Cluster API – PHP SDK

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

| Variable | Description |
| --- | --- |
| `CAMUNDA_REST_ADDRESS` / `ZEEBE_REST_ADDRESS` | Cluster REST endpoint. `/v2` is appended automatically. |
| `CAMUNDA_AUTH_STRATEGY` | `NONE`, `BASIC`, or `OAUTH` (auto-detected when unset). |
| `CAMUNDA_CLIENT_ID` / `CAMUNDA_CLIENT_SECRET` | OAuth client credentials. |
| `CAMUNDA_BASIC_AUTH_USERNAME` / `CAMUNDA_BASIC_AUTH_PASSWORD` | Basic auth credentials. |
| `CAMUNDA_OAUTH_URL` | Token endpoint. Defaults to the Camunda SaaS login URL. |
| `CAMUNDA_TOKEN_AUDIENCE` | OAuth audience. Defaults to `zeebe.camunda.io`. |
| `CAMUNDA_TENANT_ID` / `CAMUNDA_TENANT_IDS` | Default tenant(s). |
| `CAMUNDA_MTLS_CERT_PATH` / `CAMUNDA_MTLS_KEY_PATH` / `CAMUNDA_MTLS_CA_PATH` | Mutual-TLS material. |
| `CAMUNDA_WORKER_*` | Job-worker defaults (`MAX_CONCURRENT_JOBS`, `TIMEOUT`, `REQUEST_TIMEOUT`, `NAME`). |

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

Beyond the ergonomic helpers, every API group is reachable through the typed `api()` accessor:

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
