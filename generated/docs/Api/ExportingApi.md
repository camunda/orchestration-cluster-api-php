# Camunda\Orchestration\Api\ExportingApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getClusterExportingStatus()**](ExportingApi.md#getClusterExportingStatus) | **GET** /cluster/v2/exporting | Get exporting status of the whole cluster |
| [**getExportingStatus()**](ExportingApi.md#getExportingStatus) | **GET** /exporting | Get exporting status |
| [**pauseClusterExporting()**](ExportingApi.md#pauseClusterExporting) | **POST** /cluster/v2/exporting/pause | Pause exporting across the whole cluster |
| [**pauseExporting()**](ExportingApi.md#pauseExporting) | **POST** /exporting/pause | Pause exporting |
| [**resumeClusterExporting()**](ExportingApi.md#resumeClusterExporting) | **POST** /cluster/v2/exporting/resume | Resume exporting across the whole cluster |
| [**resumeExporting()**](ExportingApi.md#resumeExporting) | **POST** /exporting/resume | Resume exporting |


## `getClusterExportingStatus()`

```php
getClusterExportingStatus(): \Camunda\Orchestration\Api\Model\ExportingStatusResponse
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

Get exporting status of the whole cluster

Returns the exporting status of the whole cluster, folded over the exporting status of every physical tenant. Only `PAUSED` and `SOFT_PAUSED` confirm that exporting is paused cluster-wide; every other value means at least one physical tenant is not paused, so callers should keep polling. A physical tenant that itself reports `MIXED` makes the whole cluster `MIXED`.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ExportingApi(
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
    $result = $apiInstance->getClusterExportingStatus($hostIndex, $variables);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ExportingApi->getClusterExportingStatus: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.
| hostIndex | null|int | Host index. Defaults to null. If null, then the library will use $this->hostIndex instead | [optional] |
| variables | array | Associative array of variables to pass to the host. Defaults to empty array. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ExportingStatusResponse**](../Model/ExportingStatusResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getExportingStatus()`

```php
getExportingStatus(): \Camunda\Orchestration\Api\Model\ExportingStatusResponse
```

Get exporting status

Returns the exporting status of the physical tenant, aggregated over every replica of every one of its partitions.  Because pause and resume are applied to all replicas, the status is only a single phase if every replica reports that phase; otherwise it is `MIXED`, which means a pause or resume is still in flight or was only partially applied. Backup tooling should treat only `PAUSED` and `SOFT_PAUSED` as confirmation that exporting is paused.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ExportingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->getExportingStatus();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ExportingApi->getExportingStatus: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\Camunda\Orchestration\Api\Model\ExportingStatusResponse**](../Model/ExportingStatusResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `pauseClusterExporting()`

```php
pauseClusterExporting($soft)
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

Pause exporting across the whole cluster

Pauses exporting on every physical tenant of the cluster in one call. With `soft=true`, every physical tenant is soft-paused instead.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ExportingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$soft = false; // bool | If true, soft-pauses exporting instead of a hard pause.

$hostIndex = 0;
$variables = [
    'host' => 'YOUR_VALUE',
    'port' => 'YOUR_VALUE',
    'schema' => 'YOUR_VALUE',
];

try {
    $apiInstance->pauseClusterExporting($soft, $hostIndex, $variables);
} catch (Exception $e) {
    echo 'Exception when calling ExportingApi->pauseClusterExporting: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **soft** | **bool**| If true, soft-pauses exporting instead of a hard pause. | [optional] [default to false] |
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

## `pauseExporting()`

```php
pauseExporting($soft)
```

Pause exporting

Pauses exporting on all partitions of the physical tenant. While paused, exported records are not committed, so the log is not compacted for the affected partitions.  With `soft=true`, exporting continues to run but its position is not committed, so the state after resuming is identical to a hard pause; use this variant when exporting must keep progressing (e.g. to avoid falling behind) while still preventing log compaction, such as during a backup.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ExportingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$soft = false; // bool | If true, soft-pauses exporting instead of a hard pause.

try {
    $apiInstance->pauseExporting($soft);
} catch (Exception $e) {
    echo 'Exception when calling ExportingApi->pauseExporting: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **soft** | **bool**| If true, soft-pauses exporting instead of a hard pause. | [optional] [default to false] |

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

## `resumeClusterExporting()`

```php
resumeClusterExporting()
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

Resume exporting across the whole cluster

Resumes exporting on every physical tenant of the cluster in one call, after a pause or soft pause.  Requires the cluster-admin security chain. Although this operation lists `bearerAuth` / `basicAuth` like the rest of the Orchestration Cluster API, it does not accept an Orchestration Cluster user's credentials — only the separate cluster-admin credentials are valid here.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ExportingApi(
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
    $apiInstance->resumeClusterExporting($hostIndex, $variables);
} catch (Exception $e) {
    echo 'Exception when calling ExportingApi->resumeClusterExporting: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.
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

## `resumeExporting()`

```php
resumeExporting()
```

Resume exporting

Resumes exporting on all partitions of the physical tenant after a pause or soft pause.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ExportingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $apiInstance->resumeExporting();
} catch (Exception $e) {
    echo 'Exception when calling ExportingApi->resumeExporting: ', $e->getMessage(), PHP_EOL;
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
