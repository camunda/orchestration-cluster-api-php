# Camunda\Orchestration\Api\BackupApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**deleteHistoryBackup()**](BackupApi.md#deleteHistoryBackup) | **DELETE** /backups/history/{backupId} | Delete history backup |
| [**deleteHistoryBackupAsClusterAdmin()**](BackupApi.md#deleteHistoryBackupAsClusterAdmin) | **DELETE** /cluster/v2/backups/history/{backupId} | Delete a history backup across physical tenants |
| [**deleteRuntimeBackup()**](BackupApi.md#deleteRuntimeBackup) | **DELETE** /backups/runtime/{backupId} | Delete runtime backup |
| [**deleteRuntimeBackupAsClusterAdmin()**](BackupApi.md#deleteRuntimeBackupAsClusterAdmin) | **DELETE** /cluster/v2/backups/runtime/{backupId} | Delete a runtime backup across physical tenants |
| [**deleteRuntimeBackupState()**](BackupApi.md#deleteRuntimeBackupState) | **DELETE** /backups/runtime/state | Delete runtime backup state |
| [**deleteRuntimeBackupStateAsClusterAdmin()**](BackupApi.md#deleteRuntimeBackupStateAsClusterAdmin) | **DELETE** /cluster/v2/backups/runtime/state | Delete runtime backup state across physical tenants |
| [**getHistoryBackup()**](BackupApi.md#getHistoryBackup) | **GET** /backups/history/{backupId} | Get history backup |
| [**getHistoryBackupAsClusterAdmin()**](BackupApi.md#getHistoryBackupAsClusterAdmin) | **GET** /cluster/v2/backups/history/{backupId} | Get a history backup across physical tenants |
| [**getRuntimeBackup()**](BackupApi.md#getRuntimeBackup) | **GET** /backups/runtime/{backupId} | Get runtime backup |
| [**getRuntimeBackupAsClusterAdmin()**](BackupApi.md#getRuntimeBackupAsClusterAdmin) | **GET** /cluster/v2/backups/runtime/{backupId} | Get a runtime backup across physical tenants |
| [**getRuntimeBackupState()**](BackupApi.md#getRuntimeBackupState) | **GET** /backups/runtime/state | Get runtime backup state |
| [**getRuntimeBackupStateAsClusterAdmin()**](BackupApi.md#getRuntimeBackupStateAsClusterAdmin) | **GET** /cluster/v2/backups/runtime/state | Get runtime backup state across physical tenants |
| [**listHistoryBackups()**](BackupApi.md#listHistoryBackups) | **GET** /backups/history | List history backups |
| [**listHistoryBackupsAsClusterAdmin()**](BackupApi.md#listHistoryBackupsAsClusterAdmin) | **GET** /cluster/v2/backups/history | List history backups across physical tenants |
| [**listRuntimeBackups()**](BackupApi.md#listRuntimeBackups) | **GET** /backups/runtime | List runtime backups |
| [**listRuntimeBackupsAsClusterAdmin()**](BackupApi.md#listRuntimeBackupsAsClusterAdmin) | **GET** /cluster/v2/backups/runtime | List runtime backups across physical tenants |
| [**syncRuntimeBackupState()**](BackupApi.md#syncRuntimeBackupState) | **POST** /backups/runtime/state/sync | Force-write runtime backup state |
| [**syncRuntimeBackupStateAsClusterAdmin()**](BackupApi.md#syncRuntimeBackupStateAsClusterAdmin) | **POST** /cluster/v2/backups/runtime/state/sync | Force-write runtime backup state across physical tenants |
| [**takeHistoryBackup()**](BackupApi.md#takeHistoryBackup) | **POST** /backups/history | Take a history backup |
| [**takeHistoryBackupAsClusterAdmin()**](BackupApi.md#takeHistoryBackupAsClusterAdmin) | **POST** /cluster/v2/backups/history | Take a history backup on one or every physical tenant |
| [**takeRuntimeBackup()**](BackupApi.md#takeRuntimeBackup) | **POST** /backups/runtime | Take a runtime backup |
| [**takeRuntimeBackupAsClusterAdmin()**](BackupApi.md#takeRuntimeBackupAsClusterAdmin) | **POST** /cluster/v2/backups/runtime | Take a runtime backup on one or every physical tenant |


## `deleteHistoryBackup()`

```php
deleteHistoryBackup($backupId)
```

Delete history backup

Deletes the history backup with the given id, by deleting every snapshot that makes it up.  Only available on clusters whose secondary storage is Elasticsearch or OpenSearch.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$backupId = 56; // int | The id of the backup.

try {
    $apiInstance->deleteHistoryBackup($backupId);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->deleteHistoryBackup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **backupId** | **int**| The id of the backup. | |

### Return type

void (empty response body)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `deleteHistoryBackupAsClusterAdmin()`

```php
deleteHistoryBackupAsClusterAdmin($backupId, $physicalTenantId)
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

Delete a history backup across physical tenants

Deletes the history backup with the given id from every physical tenant of the cluster, or from the one named by `physicalTenantId`. A tenant that does not hold the backup has already reached the requested end state, so it counts as deleted rather than as a failure.  The request is all-or-nothing: a physical tenant the backup cannot be deleted from fails the whole request, and the deletions that already succeeded on other tenants are not undone. Narrow the request with `physicalTenantId` to delete from the tenants that can still be reached.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here. Only available on clusters whose secondary storage is Elasticsearch or OpenSearch. Use `DELETE /v2/backups/history/{backupId}` to act as a single physical tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$backupId = 56; // int | The id of the backup.
$physicalTenantId = default; // string | The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster.

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $apiInstance->deleteHistoryBackupAsClusterAdmin($backupId, $physicalTenantId, $hostIndex, $variables);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->deleteHistoryBackupAsClusterAdmin: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **backupId** | **int**| The id of the backup. | |
| **physicalTenantId** | **string**| The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. | [optional] |
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

void (empty response body)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `deleteRuntimeBackup()`

```php
deleteRuntimeBackup($backupId)
```

Delete runtime backup

Deletes the runtime backup with the given id.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$backupId = 56; // int | The id of the backup.

try {
    $apiInstance->deleteRuntimeBackup($backupId);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->deleteRuntimeBackup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **backupId** | **int**| The id of the backup. | |

### Return type

void (empty response body)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `deleteRuntimeBackupAsClusterAdmin()`

```php
deleteRuntimeBackupAsClusterAdmin($backupId, $physicalTenantId)
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

Delete a runtime backup across physical tenants

Deletes the runtime backup with the given id from every physical tenant of the cluster, or from the one named by `physicalTenantId`. A tenant that does not hold the backup has already reached the requested end state, so it counts as deleted rather than as a failure — the same as deleting an unknown backup id through the per-physical-tenant endpoint.  The request is all-or-nothing: a physical tenant the backup cannot be deleted from fails the whole request, and the deletions that already succeeded on other tenants are not undone. Narrow the request with `physicalTenantId` to delete from the tenants that can still be reached.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here. Use `DELETE /v2/backups/runtime/{backupId}` to act as a single physical tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$backupId = 56; // int | The id of the backup.
$physicalTenantId = default; // string | The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster.

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $apiInstance->deleteRuntimeBackupAsClusterAdmin($backupId, $physicalTenantId, $hostIndex, $variables);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->deleteRuntimeBackupAsClusterAdmin: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **backupId** | **int**| The id of the backup. | |
| **physicalTenantId** | **string**| The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. | [optional] |
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

void (empty response body)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `deleteRuntimeBackupState()`

```php
deleteRuntimeBackupState()
```

Delete runtime backup state

Resets the runtime backup state of every partition of the physical tenant, clearing all checkpoint info, backup info, checkpoint metadata, and backup ranges. Used when switching backup stores.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $apiInstance->deleteRuntimeBackupState();
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->deleteRuntimeBackupState: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

void (empty response body)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `deleteRuntimeBackupStateAsClusterAdmin()`

```php
deleteRuntimeBackupStateAsClusterAdmin($physicalTenantId)
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

Delete runtime backup state across physical tenants

Resets the runtime backup state of every partition of every physical tenant of the cluster, or of the one named by `physicalTenantId`, clearing all checkpoint info, backup info, checkpoint metadata, and backup ranges. Used when switching backup stores.  The request is all-or-nothing: a physical tenant whose state cannot be reset fails the whole request, and the resets that already succeeded on other tenants are not undone. Narrow the request with `physicalTenantId` to reset the tenants that can still be reached.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here. Use `DELETE /v2/backups/runtime/state` to act as a single physical tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$physicalTenantId = default; // string | The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster.

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $apiInstance->deleteRuntimeBackupStateAsClusterAdmin($physicalTenantId, $hostIndex, $variables);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->deleteRuntimeBackupStateAsClusterAdmin: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **physicalTenantId** | **string**| The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. | [optional] |
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

void (empty response body)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getHistoryBackup()`

```php
getHistoryBackup($backupId): \Camunda\Orchestration\Api\Model\HistoryBackupInfo
```

Get history backup

Returns detailed status of the history backup with the given id.  Only available on clusters whose secondary storage is Elasticsearch or OpenSearch.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$backupId = 56; // int | The id of the backup.

try {
    $result = $apiInstance->getHistoryBackup($backupId);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->getHistoryBackup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **backupId** | **int**| The id of the backup. | |

### Return type

[**\Camunda\Orchestration\Api\Model\HistoryBackupInfo**](../Model/HistoryBackupInfo.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getHistoryBackupAsClusterAdmin()`

```php
getHistoryBackupAsClusterAdmin($backupId, $physicalTenantId): \Camunda\Orchestration\Api\Model\ClusterHistoryBackupInfo
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

Get a history backup across physical tenants

Reports what every physical tenant of the cluster, or the one named by `physicalTenantId`, holds for the given backup id. There is no aggregated cluster-level state: a tenant that was reached and does not hold this backup reports `NOT_FOUND`, which is a successful observation rather than a failure.  The request is all-or-nothing: a physical tenant whose state cannot be read fails the whole request. Narrow the request with `physicalTenantId` to read the tenants that can still be reached.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here. Only available on clusters whose secondary storage is Elasticsearch or OpenSearch. Use `GET /v2/backups/history/{backupId}` to act as a single physical tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$backupId = 56; // int | The id of the backup.
$physicalTenantId = default; // string | The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster.

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->getHistoryBackupAsClusterAdmin($backupId, $physicalTenantId, $hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->getHistoryBackupAsClusterAdmin: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **backupId** | **int**| The id of the backup. | |
| **physicalTenantId** | **string**| The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. | [optional] |
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterHistoryBackupInfo**](../Model/ClusterHistoryBackupInfo.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getRuntimeBackup()`

```php
getRuntimeBackup($backupId): \Camunda\Orchestration\Api\Model\BackupInfo
```

Get runtime backup

Returns detailed status of the runtime backup with the given id.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$backupId = 56; // int | The id of the backup.

try {
    $result = $apiInstance->getRuntimeBackup($backupId);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->getRuntimeBackup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **backupId** | **int**| The id of the backup. | |

### Return type

[**\Camunda\Orchestration\Api\Model\BackupInfo**](../Model/BackupInfo.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getRuntimeBackupAsClusterAdmin()`

```php
getRuntimeBackupAsClusterAdmin($backupId, $physicalTenantId): \Camunda\Orchestration\Api\Model\ClusterRuntimeBackupInfo
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

Get a runtime backup across physical tenants

Reports what every physical tenant of the cluster, or the one named by `physicalTenantId`, holds for the given backup id, plus the state aggregated over all of them. A tenant that was reached and does not hold this backup reports `DOES_NOT_EXIST`, which is a successful observation rather than a failure — so a backup only some tenants hold aggregates to `INCOMPLETE`, the same way a backup only some partitions hold does within one tenant.  The request is all-or-nothing: a physical tenant whose state cannot be read fails the whole request. Narrow the request with `physicalTenantId` to read the tenants that can still be reached.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here. Use `GET /v2/backups/runtime/{backupId}` to act as a single physical tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$backupId = 56; // int | The id of the backup.
$physicalTenantId = default; // string | The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster.

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->getRuntimeBackupAsClusterAdmin($backupId, $physicalTenantId, $hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->getRuntimeBackupAsClusterAdmin: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **backupId** | **int**| The id of the backup. | |
| **physicalTenantId** | **string**| The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. | [optional] |
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterRuntimeBackupInfo**](../Model/ClusterRuntimeBackupInfo.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getRuntimeBackupState()`

```php
getRuntimeBackupState(): \Camunda\Orchestration\Api\Model\RuntimeBackupState
```

Get runtime backup state

Returns the current checkpoint and backup state of every partition of the physical tenant. Unlike the `backupRuntime` actuator, this fails the whole request if the checkpoint state or the backup ranges cannot be retrieved from any partition, instead of silently returning an empty section.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->getRuntimeBackupState();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->getRuntimeBackupState: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\Camunda\Orchestration\Api\Model\RuntimeBackupState**](../Model/RuntimeBackupState.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getRuntimeBackupStateAsClusterAdmin()`

```php
getRuntimeBackupStateAsClusterAdmin($physicalTenantId): \Camunda\Orchestration\Api\Model\ClusterRuntimeBackupState
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

Get runtime backup state across physical tenants

Reports the checkpoint and backup state of every partition of every physical tenant of the cluster, or of the one named by `physicalTenantId`, grouped by physical tenant. Checkpoint ids and log positions only mean anything within one physical tenant's partitions, so nothing is aggregated across tenants.  The request is all-or-nothing: a physical tenant whose state cannot be read fails the whole request rather than contributing an empty section, which an operator making a delete or restore decision could not tell apart from \"nothing to report yet\". Narrow the request with `physicalTenantId` to read the tenants that can still be reached.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here. Use `GET /v2/backups/runtime/state` to act as a single physical tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$physicalTenantId = default; // string | The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster.

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->getRuntimeBackupStateAsClusterAdmin($physicalTenantId, $hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->getRuntimeBackupStateAsClusterAdmin: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **physicalTenantId** | **string**| The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. | [optional] |
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterRuntimeBackupState**](../Model/ClusterRuntimeBackupState.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `listHistoryBackups()`

```php
listHistoryBackups($prefix, $verbose): \Camunda\Orchestration\Api\Model\HistoryBackupInfo[]
```

List history backups

Returns a list of all available history backups of the physical tenant, with their state and additional info, most recent first by snapshot start time.  Only available on clusters whose secondary storage is Elasticsearch or OpenSearch.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$prefix = 'prefix_example'; // string | A prefix that backup ids must match, ending in a single '*'. If omitted, all backups are returned.
$verbose = true; // bool | Whether to ask the secondary storage for snapshot-level detail. Setting this to `false` makes the query cheaper, but the store then reports neither snapshot state nor start time, so both the per-snapshot `details` and the aggregated `state` are incomplete and the listing order is unspecified.

try {
    $result = $apiInstance->listHistoryBackups($prefix, $verbose);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->listHistoryBackups: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **prefix** | **string**| A prefix that backup ids must match, ending in a single &#39;*&#39;. If omitted, all backups are returned. | [optional] |
| **verbose** | **bool**| Whether to ask the secondary storage for snapshot-level detail. Setting this to &#x60;false&#x60; makes the query cheaper, but the store then reports neither snapshot state nor start time, so both the per-snapshot &#x60;details&#x60; and the aggregated &#x60;state&#x60; are incomplete and the listing order is unspecified. | [optional] [default to true] |

### Return type

[**\Camunda\Orchestration\Api\Model\HistoryBackupInfo[]**](../Model/HistoryBackupInfo.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `listHistoryBackupsAsClusterAdmin()`

```php
listHistoryBackupsAsClusterAdmin($physicalTenantId, $prefix, $verbose): \Camunda\Orchestration\Api\Model\ClusterHistoryBackupInfo[]
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

List history backups across physical tenants

Lists the history backups of every physical tenant of the cluster, or of the one named by `physicalTenantId`, grouped by backup id. A backup id that only some physical tenants hold is a supported outcome rather than a degraded one, so only the tenants that hold it are listed under it.  The request is all-or-nothing: a physical tenant whose backups cannot be read fails the whole request rather than silently dropping out of the listing. Narrow the request with `physicalTenantId` to list the backups of the tenants that can still be read.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here. Only available on clusters whose secondary storage is Elasticsearch or OpenSearch. Use `GET /v2/backups/history` to act as a single physical tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$physicalTenantId = default; // string | The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster.
$prefix = 'prefix_example'; // string | A prefix that backup ids must match, ending in a single '*'. If omitted, all backups are returned.
$verbose = true; // bool | Whether to ask the secondary storage for snapshot-level detail. Setting this to `false` makes the query cheaper, but the store then reports neither snapshot state nor start time, so both the per-snapshot `details` and the per-tenant `state` are incomplete and the listing order is unspecified.

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->listHistoryBackupsAsClusterAdmin($physicalTenantId, $prefix, $verbose, $hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->listHistoryBackupsAsClusterAdmin: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **physicalTenantId** | **string**| The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. | [optional] |
| **prefix** | **string**| A prefix that backup ids must match, ending in a single &#39;*&#39;. If omitted, all backups are returned. | [optional] |
| **verbose** | **bool**| Whether to ask the secondary storage for snapshot-level detail. Setting this to &#x60;false&#x60; makes the query cheaper, but the store then reports neither snapshot state nor start time, so both the per-snapshot &#x60;details&#x60; and the per-tenant &#x60;state&#x60; are incomplete and the listing order is unspecified. | [optional] [default to true] |
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterHistoryBackupInfo[]**](../Model/ClusterHistoryBackupInfo.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `listRuntimeBackups()`

```php
listRuntimeBackups($prefix): \Camunda\Orchestration\Api\Model\BackupInfo[]
```

List runtime backups

Returns a list of all available runtime backups of the physical tenant, with their state and additional info, sorted in descending order of backupId.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$prefix = 'prefix_example'; // string | A prefix that backup ids must match, ending in a single '*'. If omitted, all backups are returned.

try {
    $result = $apiInstance->listRuntimeBackups($prefix);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->listRuntimeBackups: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **prefix** | **string**| A prefix that backup ids must match, ending in a single &#39;*&#39;. If omitted, all backups are returned. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\BackupInfo[]**](../Model/BackupInfo.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `listRuntimeBackupsAsClusterAdmin()`

```php
listRuntimeBackupsAsClusterAdmin($physicalTenantId, $prefix): \Camunda\Orchestration\Api\Model\ClusterRuntimeBackupInfo[]
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

List runtime backups across physical tenants

Lists the runtime backups of every physical tenant of the cluster, or of the one named by `physicalTenantId`, grouped by backup id. Every group reports every targeted tenant, including the ones holding nothing for that id, so a backup only some tenants hold aggregates to `INCOMPLETE` here exactly as it does when looked up directly — the state of a listed group can be trusted to say whether the cluster can be restored from it. A backup id that only some physical tenants hold is a supported outcome rather than a degraded one; tenants that generate their own backup ids never share one, so in that mode each backup forms its own group and the other tenants report `DOES_NOT_EXIST` under it.  The request is all-or-nothing: a physical tenant whose backups cannot be read fails the whole request rather than silently dropping out of the listing. Narrow the request with `physicalTenantId` to list the backups of the tenants that can still be read.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here. Use `GET /v2/backups/runtime` to act as a single physical tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$physicalTenantId = default; // string | The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster.
$prefix = 'prefix_example'; // string | A prefix that backup ids must match, ending in a single '*'. If omitted, all backups are returned.

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->listRuntimeBackupsAsClusterAdmin($physicalTenantId, $prefix, $hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->listRuntimeBackupsAsClusterAdmin: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **physicalTenantId** | **string**| The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. | [optional] |
| **prefix** | **string**| A prefix that backup ids must match, ending in a single &#39;*&#39;. If omitted, all backups are returned. | [optional] |
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterRuntimeBackupInfo[]**](../Model/ClusterRuntimeBackupInfo.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `syncRuntimeBackupState()`

```php
syncRuntimeBackupState(): \Camunda\Orchestration\Api\Model\RuntimeBackupState
```

Force-write runtime backup state

Force-writes the checkpoint and backup metadata of every partition of the physical tenant to the backup store, independent of any backup being taken or confirmed, and returns the updated state.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->syncRuntimeBackupState();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->syncRuntimeBackupState: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\Camunda\Orchestration\Api\Model\RuntimeBackupState**](../Model/RuntimeBackupState.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `syncRuntimeBackupStateAsClusterAdmin()`

```php
syncRuntimeBackupStateAsClusterAdmin($physicalTenantId): \Camunda\Orchestration\Api\Model\ClusterRuntimeBackupState
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

Force-write runtime backup state across physical tenants

Force-writes the checkpoint and backup metadata of every partition of every physical tenant of the cluster, or of the one named by `physicalTenantId`, to that tenant's backup store, independent of any backup being taken or confirmed, and returns the updated state per physical tenant.  The request is all-or-nothing: a physical tenant whose metadata cannot be written fails the whole request, and the writes that already succeeded on other tenants are not undone. The operation is idempotent, so retrying the same call is the correct remedy. Narrow the request with `physicalTenantId` to write the tenants that can still be reached.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here. Use `POST /v2/backups/runtime/state/sync` to act as a single physical tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$physicalTenantId = default; // string | The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster.

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->syncRuntimeBackupStateAsClusterAdmin($physicalTenantId, $hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->syncRuntimeBackupStateAsClusterAdmin: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **physicalTenantId** | **string**| The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. | [optional] |
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterRuntimeBackupState**](../Model/ClusterRuntimeBackupState.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `takeHistoryBackup()`

```php
takeHistoryBackup($takeHistoryBackupRequest): \Camunda\Orchestration\Api\Model\TakeHistoryBackupResponse
```

Take a history backup

Triggers a backup of the physical tenant's history, by scheduling a snapshot of every secondary storage index it owns.  Unlike runtime backups, history backups have no generated-id mode: `backupId` is always required.  Only available on clusters whose secondary storage is Elasticsearch or OpenSearch.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$takeHistoryBackupRequest = new \Camunda\Orchestration\Api\Model\TakeHistoryBackupRequest(); // \Camunda\Orchestration\Api\Model\TakeHistoryBackupRequest

try {
    $result = $apiInstance->takeHistoryBackup($takeHistoryBackupRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->takeHistoryBackup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **takeHistoryBackupRequest** | [**\Camunda\Orchestration\Api\Model\TakeHistoryBackupRequest**](../Model/TakeHistoryBackupRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\TakeHistoryBackupResponse**](../Model/TakeHistoryBackupResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `takeHistoryBackupAsClusterAdmin()`

```php
takeHistoryBackupAsClusterAdmin($takeHistoryBackupRequest, $physicalTenantId): \Camunda\Orchestration\Api\Model\ClusterTakeHistoryBackupResponse
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

Take a history backup on one or every physical tenant

Triggers a history backup on every physical tenant of the cluster, or on the one named by `physicalTenantId`. Every targeted tenant uses the same caller-supplied `backupId`, but the backups are independent: they are neither coordinated nor rolled back together.  The request is all-or-nothing: the `backupId` is checked on every targeted tenant before any snapshot is scheduled, so a tenant that already holds this id, or that cannot be reached, fails the whole request and no backup is started anywhere. There is no aggregated cluster-level state in the response.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here. Only available on clusters whose secondary storage is Elasticsearch or OpenSearch. Use `POST /v2/backups/history` to act as a single physical tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$takeHistoryBackupRequest = new \Camunda\Orchestration\Api\Model\TakeHistoryBackupRequest(); // \Camunda\Orchestration\Api\Model\TakeHistoryBackupRequest
$physicalTenantId = default; // string | The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster.

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->takeHistoryBackupAsClusterAdmin($takeHistoryBackupRequest, $physicalTenantId, $hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->takeHistoryBackupAsClusterAdmin: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **takeHistoryBackupRequest** | [**\Camunda\Orchestration\Api\Model\TakeHistoryBackupRequest**](../Model/TakeHistoryBackupRequest.md)|  | |
| **physicalTenantId** | **string**| The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. | [optional] |
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterTakeHistoryBackupResponse**](../Model/ClusterTakeHistoryBackupResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `takeRuntimeBackup()`

```php
takeRuntimeBackup($takeRuntimeBackupRequest): \Camunda\Orchestration\Api\Model\TakeRuntimeBackupResponse
```

Take a runtime backup

Triggers a backup of runtime data on all partitions of the physical tenant.  The `backupId` must be omitted if continuous backups and/or a backup or checkpoint schedule is enabled for the physical tenant, as the id is generated automatically. Otherwise, `backupId` is required.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$takeRuntimeBackupRequest = new \Camunda\Orchestration\Api\Model\TakeRuntimeBackupRequest(); // \Camunda\Orchestration\Api\Model\TakeRuntimeBackupRequest

try {
    $result = $apiInstance->takeRuntimeBackup($takeRuntimeBackupRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->takeRuntimeBackup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **takeRuntimeBackupRequest** | [**\Camunda\Orchestration\Api\Model\TakeRuntimeBackupRequest**](../Model/TakeRuntimeBackupRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\TakeRuntimeBackupResponse**](../Model/TakeRuntimeBackupResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `takeRuntimeBackupAsClusterAdmin()`

```php
takeRuntimeBackupAsClusterAdmin($physicalTenantId, $takeRuntimeBackupRequest): \Camunda\Orchestration\Api\Model\ClusterTakeRuntimeBackupResponse
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

Take a runtime backup on one or every physical tenant

Triggers a runtime backup on every physical tenant of the cluster, or on the one named by `physicalTenantId`. A cluster-wide backup is a set of independent per-tenant backups, not an atomic snapshot of the cluster: they are neither coordinated nor rolled back together, and each tenant stores its own, so the same `backupId` can be used for all of them.  Every targeted physical tenant must be in the same backup-id mode. `backupId` must be omitted when every targeted tenant generates its own ids (because continuous backups and/or a backup or checkpoint schedule is enabled for it), and is required when none of them does. A cluster whose targeted tenants mix the two modes is rejected with 400 and has to be driven one tenant at a time through `POST /v2/backups/runtime`. In generated-id mode each tenant generates its own id, so the response reports an id per physical tenant rather than one for the cluster.  The trigger is all-or-error, and never silent about a partial trigger: if any targeted tenant cannot be triggered the response carries an error status, but its body still lists every targeted tenant — which ones were triggered, under which `backupId` to monitor or delete them, and why the others failed. Nothing is rolled back, so the backups that were triggered keep running and have to be deleted explicitly. A request rejected before any tenant was triggered answers with a problem detail instead, and nothing is running.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here. Use `POST /v2/backups/runtime` to act as a single physical tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BackupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$physicalTenantId = default; // string | The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster.
$takeRuntimeBackupRequest = new \Camunda\Orchestration\Api\Model\TakeRuntimeBackupRequest(); // \Camunda\Orchestration\Api\Model\TakeRuntimeBackupRequest

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $result = $apiInstance->takeRuntimeBackupAsClusterAdmin($physicalTenantId, $takeRuntimeBackupRequest, $hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BackupApi->takeRuntimeBackupAsClusterAdmin: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **physicalTenantId** | **string**| The physical tenant to apply the change to. When omitted, or when passed with an empty value, the change is applied to every physical tenant of the cluster. | [optional] |
| **takeRuntimeBackupRequest** | [**\Camunda\Orchestration\Api\Model\TakeRuntimeBackupRequest**](../Model/TakeRuntimeBackupRequest.md)|  | [optional] |
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterTakeRuntimeBackupResponse**](../Model/ClusterTakeRuntimeBackupResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
