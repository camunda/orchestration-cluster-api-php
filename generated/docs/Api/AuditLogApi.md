# Camunda\Orchestration\Api\AuditLogApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getAuditLog()**](AuditLogApi.md#getAuditLog) | **GET** /audit-logs/{auditLogKey} | Get audit log |
| [**searchAuditLogs()**](AuditLogApi.md#searchAuditLogs) | **POST** /audit-logs/search | Search audit logs |


## `getAuditLog()`

```php
getAuditLog($auditLogKey): \Camunda\Orchestration\Api\Model\AuditLogResult
```

Get audit log

Get an audit log entry by auditLogKey.

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


$apiInstance = new Camunda\Orchestration\Api\Api\AuditLogApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$auditLogKey = 'auditLogKey_example'; // string | The audit log key.

try {
    $result = $apiInstance->getAuditLog($auditLogKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AuditLogApi->getAuditLog: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **auditLogKey** | **string**| The audit log key. | |

### Return type

[**\Camunda\Orchestration\Api\Model\AuditLogResult**](../Model/AuditLogResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchAuditLogs()`

```php
searchAuditLogs($auditLogSearchQueryRequest): \Camunda\Orchestration\Api\Model\AuditLogSearchQueryResult
```

Search audit logs

Search for audit logs based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\AuditLogApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$auditLogSearchQueryRequest = new \Camunda\Orchestration\Api\Model\AuditLogSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\AuditLogSearchQueryRequest

try {
    $result = $apiInstance->searchAuditLogs($auditLogSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AuditLogApi->searchAuditLogs: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **auditLogSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\AuditLogSearchQueryRequest**](../Model/AuditLogSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\AuditLogSearchQueryResult**](../Model/AuditLogSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
