# Camunda\Orchestration\Api\SystemApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getSystemConfiguration()**](SystemApi.md#getSystemConfiguration) | **GET** /system/configuration | System configuration (alpha) |
| [**getUsageMetrics()**](SystemApi.md#getUsageMetrics) | **GET** /system/usage-metrics | Get usage metrics |


## `getSystemConfiguration()`

```php
getSystemConfiguration(): \Camunda\Orchestration\Api\Model\SystemConfigurationResponse
```

System configuration (alpha)

Returns the current system configuration. The response is an envelope that groups settings by feature area.  This endpoint is an alpha feature and may be subject to change in future releases.

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


$apiInstance = new Camunda\Orchestration\Api\Api\SystemApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->getSystemConfiguration();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SystemApi->getSystemConfiguration: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\Camunda\Orchestration\Api\Model\SystemConfigurationResponse**](../Model/SystemConfigurationResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getUsageMetrics()`

```php
getUsageMetrics($startTime, $endTime, $tenantId, $withTenants): \Camunda\Orchestration\Api\Model\UsageMetricsResponse
```

Get usage metrics

Retrieve the usage metrics based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\SystemApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$startTime = 2025-06-07T13:14:15Z; // \DateTime | The start date for usage metrics, including this date. Value in ISO 8601 format.
$endTime = 2025-06-07T13:14:15Z; // \DateTime | The end date for usage metrics, including this date. Value in ISO 8601 format.
$tenantId = 'tenantId_example'; // string | Restrict results to a specific tenant ID. If not provided, results for all tenants are returned.
$withTenants = false; // bool | Whether to return tenant metrics in addition to the total metrics or not. Default false.

try {
    $result = $apiInstance->getUsageMetrics($startTime, $endTime, $tenantId, $withTenants);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SystemApi->getUsageMetrics: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **startTime** | **\DateTime**| The start date for usage metrics, including this date. Value in ISO 8601 format. | |
| **endTime** | **\DateTime**| The end date for usage metrics, including this date. Value in ISO 8601 format. | |
| **tenantId** | **string**| Restrict results to a specific tenant ID. If not provided, results for all tenants are returned. | [optional] |
| **withTenants** | **bool**| Whether to return tenant metrics in addition to the total metrics or not. Default false. | [optional] [default to false] |

### Return type

[**\Camunda\Orchestration\Api\Model\UsageMetricsResponse**](../Model/UsageMetricsResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
