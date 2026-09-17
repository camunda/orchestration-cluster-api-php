# Camunda\Orchestration\Api\RecoveryApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**changeClusterMode()**](RecoveryApi.md#changeClusterMode) | **PATCH** /mode | Change cluster mode |
| [**changeClusterModeAsClusterAdmin()**](RecoveryApi.md#changeClusterModeAsClusterAdmin) | **PATCH** /cluster/v2/mode | Change the cluster mode of one or every physical tenant |
| [**getRestoreStatus()**](RecoveryApi.md#getRestoreStatus) | **GET** /restore | Get the status of the restore that is currently in progress |
| [**restore()**](RecoveryApi.md#restore) | **POST** /restore | Restore from a backup |
| [**restoreAsClusterAdmin()**](RecoveryApi.md#restoreAsClusterAdmin) | **POST** /cluster/v2/restore | Restore one or every physical tenant from a backup |


## `changeClusterMode()`

```php
changeClusterMode($mode, $dryRun): \Camunda\Orchestration\Api\Model\ClusterModeChangeResponse
```

Change cluster mode

Transitions the cluster between processing and recovery mode. This is a non-blocking operation: the request is acknowledged once the change has been accepted, before the transition itself has completed. Entering recovery mode deactivates all partitions so that only a restricted set of read-only operations remains available; exiting recovery mode returns the cluster to normal processing. Returns the planned cluster change so its progress can be monitored via the topology.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RecoveryApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$mode = new \Camunda\Orchestration\Api\Model\\Camunda\Orchestration\Api\Model\Mode(); // \Camunda\Orchestration\Api\Model\Mode | The target cluster mode.
$dryRun = false; // bool | If true, the requested change is only validated and the resulting plan is returned, without applying it to the cluster.

try {
    $result = $apiInstance->changeClusterMode($mode, $dryRun);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RecoveryApi->changeClusterMode: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **mode** | [**\Camunda\Orchestration\Api\Model\Mode**](../Model/.md)| The target cluster mode. | |
| **dryRun** | **bool**| If true, the requested change is only validated and the resulting plan is returned, without applying it to the cluster. | [optional] [default to false] |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterModeChangeResponse**](../Model/ClusterModeChangeResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `changeClusterModeAsClusterAdmin()`

```php
changeClusterModeAsClusterAdmin($mode, $physicalTenantId, $dryRun): \Camunda\Orchestration\Api\Model\ClusterModeChangeResponse
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

Change the cluster mode of one or every physical tenant

Transitions physical tenants between processing and recovery mode.  If the `physicalTenantId` parameter is not provided, all available physical tenants are transitioned individually.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RecoveryApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$mode = new \Camunda\Orchestration\Api\Model\\Camunda\Orchestration\Api\Model\Mode(); // \Camunda\Orchestration\Api\Model\Mode | The target cluster mode.
$physicalTenantId = default; // string | The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster.
$dryRun = false; // bool | If true, the requested change is only validated and the resulting plan is returned, without applying it to the cluster.

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->changeClusterModeAsClusterAdmin($mode, $physicalTenantId, $dryRun, $hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RecoveryApi->changeClusterModeAsClusterAdmin: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **mode** | [**\Camunda\Orchestration\Api\Model\Mode**](../Model/.md)| The target cluster mode. | |
| **physicalTenantId** | **string**| The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. | [optional] |
| **dryRun** | **bool**| If true, the requested change is only validated and the resulting plan is returned, without applying it to the cluster. | [optional] [default to false] |
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterModeChangeResponse**](../Model/ClusterModeChangeResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getRestoreStatus()`

```php
getRestoreStatus(): \Camunda\Orchestration\Api\Model\RestoreStatusResponse
```

Get the status of the restore that is currently in progress

Returns the status of the restore that is currently in progress, reported per broker and per partition. There is at most one restore in flight at any time. Once the restore has finished this endpoint returns 404; the per-partition detail is not retained after completion.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RecoveryApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->getRestoreStatus();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RecoveryApi->getRestoreStatus: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\Camunda\Orchestration\Api\Model\RestoreStatusResponse**](../Model/RestoreStatusResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `restore()`

```php
restore($restoreRequest, $dryRun): \Camunda\Orchestration\Api\Model\ClusterRestoreResponse
```

Restore from a backup

Restores the cluster from a backup. The restore is described either by a single backup ID or by a time range (`from`/`to`) that selects the backups to restore. This endpoint is only accessible while the cluster is in recovery mode; requests are rejected otherwise. The request is validated and acknowledged, but the restore itself is performed asynchronously.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RecoveryApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$restoreRequest = new \Camunda\Orchestration\Api\Model\RestoreRequest(); // \Camunda\Orchestration\Api\Model\RestoreRequest
$dryRun = false; // bool | If true, the requested change is only validated and the resulting plan is returned, without applying it to the cluster.

try {
    $result = $apiInstance->restore($restoreRequest, $dryRun);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RecoveryApi->restore: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **restoreRequest** | [**\Camunda\Orchestration\Api\Model\RestoreRequest**](../Model/RestoreRequest.md)|  | |
| **dryRun** | **bool**| If true, the requested change is only validated and the resulting plan is returned, without applying it to the cluster. | [optional] [default to false] |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterRestoreResponse**](../Model/ClusterRestoreResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `restoreAsClusterAdmin()`

```php
restoreAsClusterAdmin($clusterRestoreRequest, $physicalTenantId, $dryRun): \Camunda\Orchestration\Api\Model\ClusterRestoreResponse
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

Restore one or every physical tenant from a backup

Restores physical tenants from backups. The restore is described either by a list of backup IDs or by a time range (`from`/`to`) that selects the backups to restore. Restores are only accepted while the targeted physical tenants are in recovery mode; requests are rejected otherwise. The request is validated and acknowledged, but the restore itself is performed asynchronously.  If the `physicalTenantId` parameter is provided, only that physical tenant is restored and `overrides` must be omitted.  If it is not provided, every physical tenant of the cluster is restored: those named in `overrides` with their own backup selection, all others with the selection at the top level of the request body.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RecoveryApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$clusterRestoreRequest = new \Camunda\Orchestration\Api\Model\ClusterRestoreRequest(); // \Camunda\Orchestration\Api\Model\ClusterRestoreRequest
$physicalTenantId = default; // string | The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster.
$dryRun = false; // bool | If true, the requested change is only validated and the resulting plan is returned, without applying it to the cluster.

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->restoreAsClusterAdmin($clusterRestoreRequest, $physicalTenantId, $dryRun, $hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RecoveryApi->restoreAsClusterAdmin: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **clusterRestoreRequest** | [**\Camunda\Orchestration\Api\Model\ClusterRestoreRequest**](../Model/ClusterRestoreRequest.md)|  | |
| **physicalTenantId** | **string**| The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. | [optional] |
| **dryRun** | **bool**| If true, the requested change is only validated and the resulting plan is returned, without applying it to the cluster. | [optional] [default to false] |
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterRestoreResponse**](../Model/ClusterRestoreResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
