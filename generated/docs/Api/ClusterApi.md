# Camunda\Orchestration\Api\ClusterApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**cancelClusterRebalance()**](ClusterApi.md#cancelClusterRebalance) | **DELETE** /cluster/v2/rebalance | Stop the running rebalance |
| [**getClusterRebalance()**](ClusterApi.md#getClusterRebalance) | **GET** /cluster/v2/rebalance | Report the cluster&#39;s current leadership balance |
| [**getClusterStatus()**](ClusterApi.md#getClusterStatus) | **GET** /cluster/v2/status | Get the status of the whole cluster |
| [**getClusterTopology()**](ClusterApi.md#getClusterTopology) | **GET** /cluster/v2/topology | Get the topology of the whole cluster |
| [**getClusterUpgradeStatus()**](ClusterApi.md#getClusterUpgradeStatus) | **GET** /cluster/v2/status/upgrade | Get the upgrade-readiness status of the whole cluster |
| [**getStatus()**](ClusterApi.md#getStatus) | **GET** /status | Get physical tenant status |
| [**getTopology()**](ClusterApi.md#getTopology) | **GET** /topology | Get cluster topology |
| [**triggerClusterRebalance()**](ClusterApi.md#triggerClusterRebalance) | **POST** /cluster/v2/rebalance | Trigger a cluster-wide leadership rebalance |


## `cancelClusterRebalance()`

```php
cancelClusterRebalance(): \Camunda\Orchestration\Api\Model\RebalanceCancellationResponse
```
### URI(s):
- {schema}://{host}:{port} 
    - Variables:
      - host: The hostname of the Orchestration Cluster REST Gateway.
        - Default value: localhost

      - port: The port of the Orchestration Cluster REST API server.
        - Default value: 8080

      - schema: The schema of the Orchestration Cluster REST API server.
        - Default value: http

Stop the running rebalance

Asks the running rebalance to stop once the transfer in flight has finished. Partitions already transferred keep their new leaders, and those the rebalance had not yet reached keep their current ones.  Cancellation requests are idempotent and always accepted. The `wasRunning` response field can be used to distinguish a cancellation that found a running rebalance from one that did not.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure HTTP basic authorization: basicAuth
$config = Camunda\Orchestration\Api\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');

// Configure Bearer (JWT) authorization: bearerAuth
$config = Camunda\Orchestration\Api\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new Camunda\Orchestration\Api\Api\ClusterApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->cancelClusterRebalance($hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ClusterApi->cancelClusterRebalance: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\RebalanceCancellationResponse**](../Model/RebalanceCancellationResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getClusterRebalance()`

```php
getClusterRebalance(): \Camunda\Orchestration\Api\Model\ClusterBalanceResponse
```
### URI(s):
- {schema}://{host}:{port} 
    - Variables:
      - host: The hostname of the Orchestration Cluster REST Gateway.
        - Default value: localhost

      - port: The port of the Orchestration Cluster REST API server.
        - Default value: 8080

      - schema: The schema of the Orchestration Cluster REST API server.
        - Default value: http

Report the cluster's current leadership balance

Reports whether the cluster is currently balanced, the current leadership state of every partition, and what became of the last rebalance to finish. The last completed rebalance is held in memory by the coordinating broker, so none will be reported if the coordinator has moved or restarted since the last rebalance.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure HTTP basic authorization: basicAuth
$config = Camunda\Orchestration\Api\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');

// Configure Bearer (JWT) authorization: bearerAuth
$config = Camunda\Orchestration\Api\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new Camunda\Orchestration\Api\Api\ClusterApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->getClusterRebalance($hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ClusterApi->getClusterRebalance: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterBalanceResponse**](../Model/ClusterBalanceResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getClusterStatus()`

```php
getClusterStatus(): \Camunda\Orchestration\Api\Model\ClusterStatusResponse
```
### URI(s):
- {schema}://{host}:{port} 
    - Variables:
      - host: The hostname of the Orchestration Cluster REST Gateway.
        - Default value: localhost

      - port: The port of the Orchestration Cluster REST API server.
        - Default value: 8080

      - schema: The schema of the Orchestration Cluster REST API server.
        - Default value: http

Get the status of the whole cluster

Checks the health status of the whole cluster, aggregated over all physical tenants. Returns `HEALTHY` when every physical tenant is healthy, `DOWN` when no physical tenant can process work, and `DEGRADED` in every other case. No per-tenant detail is reported; use `GET /cluster/v2/topology` for that.  This endpoint is public and requires no authentication, unlike `PATCH /cluster/v2/mode` below, which needs cluster-admin credentials.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new Camunda\Orchestration\Api\Api\ClusterApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->getClusterStatus($hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ClusterApi->getClusterStatus: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterStatusResponse**](../Model/ClusterStatusResponse.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getClusterTopology()`

```php
getClusterTopology(): \Camunda\Orchestration\Api\Model\ClusterTopologyResponse
```
### URI(s):
- {schema}://{host}:{port} 
    - Variables:
      - host: The hostname of the Orchestration Cluster REST Gateway.
        - Default value: localhost

      - port: The port of the Orchestration Cluster REST API server.
        - Default value: 8080

      - schema: The schema of the Orchestration Cluster REST API server.
        - Default value: http

Get the topology of the whole cluster

Obtains the topology of the whole cluster, aggregated over all physical tenants. Cluster-level information is reported once; partition layout, replication and per-partition role, health and state are reported per physical tenant.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here. Use `GET /v2/topology` for the topology of a single physical tenant.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure HTTP basic authorization: basicAuth
$config = Camunda\Orchestration\Api\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');

// Configure Bearer (JWT) authorization: bearerAuth
$config = Camunda\Orchestration\Api\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new Camunda\Orchestration\Api\Api\ClusterApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->getClusterTopology($hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ClusterApi->getClusterTopology: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterTopologyResponse**](../Model/ClusterTopologyResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getClusterUpgradeStatus()`

```php
getClusterUpgradeStatus(): \Camunda\Orchestration\Api\Model\ClusterUpgradeStatusResponse
```
### URI(s):
- {schema}://{host}:{port} 
    - Variables:
      - host: The hostname of the Orchestration Cluster REST Gateway.
        - Default value: localhost

      - port: The port of the Orchestration Cluster REST API server.
        - Default value: 8080

      - schema: The schema of the Orchestration Cluster REST API server.
        - Default value: http

Get the upgrade-readiness status of the whole cluster

Reports one overall upgrade-readiness status for the whole cluster, folded over every physical tenant and condition. `MIGRATED` only once every known condition has migrated for every known physical tenant; `MIGRATION_IN_PROGRESS` when at least one is confirmed not yet migrated; `UNKNOWN` otherwise (including before anything has been reported yet). No per-tenant or per-condition detail is reported here; see the `upgradeReadiness` actuator endpoint for that.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new Camunda\Orchestration\Api\Api\ClusterApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->getClusterUpgradeStatus($hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ClusterApi->getClusterUpgradeStatus: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterUpgradeStatusResponse**](../Model/ClusterUpgradeStatusResponse.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getStatus()`

```php
getStatus()
```

Get physical tenant status

Checks the health status of the default physical tenant by verifying if there's at least one partition of its group with a healthy leader. This endpoint is scoped to the default physical tenant only: it is available unprefixed and at `/physical-tenants/default/v2/status`, but not for any other physical tenant id (`/physical-tenants/{id}/v2/status` returns 404 for every other id, whether or not a physical tenant with that id exists). On a cluster with only the default physical tenant this endpoint answers the same question as `/cluster/v2/status`, though not with the same response: `/cluster/v2/status` reports its status in a body and so also distinguishes a degraded tenant from a healthy one. Use `/cluster/v2/status` for the aggregated status of the whole cluster, or `/physical-tenants/{id}/v2/topology` for the health of a specific physical tenant's partitions.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new Camunda\Orchestration\Api\Api\ClusterApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $apiInstance->getStatus();
} catch (Exception $e) {
    echo 'Exception when calling ClusterApi->getStatus: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getTopology()`

```php
getTopology(): \Camunda\Orchestration\Api\Model\TopologyResponse
```

Get cluster topology

Obtains the current topology of the cluster the gateway is part of.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure HTTP basic authorization: basicAuth
$config = Camunda\Orchestration\Api\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');

// Configure Bearer (JWT) authorization: bearerAuth
$config = Camunda\Orchestration\Api\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new Camunda\Orchestration\Api\Api\ClusterApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->getTopology();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ClusterApi->getTopology: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\Camunda\Orchestration\Api\Model\TopologyResponse**](../Model/TopologyResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `triggerClusterRebalance()`

```php
triggerClusterRebalance($dryRun, $clusterRebalanceRequest): \Camunda\Orchestration\Api\Model\ClusterBalanceResponse
```
### URI(s):
- {schema}://{host}:{port} 
    - Variables:
      - host: The hostname of the Orchestration Cluster REST Gateway.
        - Default value: localhost

      - port: The port of the Orchestration Cluster REST API server.
        - Default value: 8080

      - schema: The schema of the Orchestration Cluster REST API server.
        - Default value: http

Trigger a cluster-wide leadership rebalance

Transfers leadership of every partition that is not led by its highest-priority replica towards that replica, one partition at a time. Returns as soon as the rebalance has been accepted (poll `GET /cluster/v2/rebalance` to monitor progress).  Each rebalance can specify overrides for the configured rebalance settings (e.g. maximum replication lag to allow). An absent request body means \"use the configured settings\".  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure HTTP basic authorization: basicAuth
$config = Camunda\Orchestration\Api\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');

// Configure Bearer (JWT) authorization: bearerAuth
$config = Camunda\Orchestration\Api\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new Camunda\Orchestration\Api\Api\ClusterApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$dryRun = false; // bool | If true, report the plan the rebalance would carry out without pausing any partition or transferring any leadership.
$clusterRebalanceRequest = new \Camunda\Orchestration\Api\Model\ClusterRebalanceRequest(); // \Camunda\Orchestration\Api\Model\ClusterRebalanceRequest

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->triggerClusterRebalance($dryRun, $clusterRebalanceRequest, $hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ClusterApi->triggerClusterRebalance: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **dryRun** | **bool**| If true, report the plan the rebalance would carry out without pausing any partition or transferring any leadership. | [optional] [default to false] |
| **clusterRebalanceRequest** | [**\Camunda\Orchestration\Api\Model\ClusterRebalanceRequest**](../Model/ClusterRebalanceRequest.md)|  | [optional] |
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterBalanceResponse**](../Model/ClusterBalanceResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
