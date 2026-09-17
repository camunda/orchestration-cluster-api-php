# Camunda\Orchestration\Api\BatchOperationApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**cancelBatchOperation()**](BatchOperationApi.md#cancelBatchOperation) | **POST** /batch-operations/{batchOperationKey}/cancellation | Cancel Batch operation |
| [**getBatchOperation()**](BatchOperationApi.md#getBatchOperation) | **GET** /batch-operations/{batchOperationKey} | Get batch operation |
| [**resumeBatchOperation()**](BatchOperationApi.md#resumeBatchOperation) | **POST** /batch-operations/{batchOperationKey}/resumption | Resume Batch operation |
| [**searchBatchOperationItems()**](BatchOperationApi.md#searchBatchOperationItems) | **POST** /batch-operation-items/search | Search batch operation items |
| [**searchBatchOperations()**](BatchOperationApi.md#searchBatchOperations) | **POST** /batch-operations/search | Search batch operations |
| [**suspendBatchOperation()**](BatchOperationApi.md#suspendBatchOperation) | **POST** /batch-operations/{batchOperationKey}/suspension | Suspend Batch operation |


## `cancelBatchOperation()`

```php
cancelBatchOperation($batchOperationKey)
```

Cancel Batch operation

Cancels a running batch operation. This is done asynchronously, the progress can be tracked using the batch operation status endpoint (/batch-operations/{batchOperationKey}).

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


$apiInstance = new Camunda\Orchestration\Api\Api\BatchOperationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$batchOperationKey = 'batchOperationKey_example'; // string | The key (or operate legacy ID) of the batch operation.

try {
    $apiInstance->cancelBatchOperation($batchOperationKey);
} catch (Exception $e) {
    echo 'Exception when calling BatchOperationApi->cancelBatchOperation: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **batchOperationKey** | **string**| The key (or operate legacy ID) of the batch operation. | |

### Return type

void (empty response body)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getBatchOperation()`

```php
getBatchOperation($batchOperationKey): \Camunda\Orchestration\Api\Model\BatchOperationResponse
```

Get batch operation

Get batch operation by key.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BatchOperationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$batchOperationKey = 'batchOperationKey_example'; // string | The key (or operate legacy ID) of the batch operation.

try {
    $result = $apiInstance->getBatchOperation($batchOperationKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BatchOperationApi->getBatchOperation: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **batchOperationKey** | **string**| The key (or operate legacy ID) of the batch operation. | |

### Return type

[**\Camunda\Orchestration\Api\Model\BatchOperationResponse**](../Model/BatchOperationResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `resumeBatchOperation()`

```php
resumeBatchOperation($batchOperationKey)
```

Resume Batch operation

Resumes a suspended batch operation. This is done asynchronously, the progress can be tracked using the batch operation status endpoint (/batch-operations/{batchOperationKey}).

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


$apiInstance = new Camunda\Orchestration\Api\Api\BatchOperationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$batchOperationKey = 'batchOperationKey_example'; // string | The key (or operate legacy ID) of the batch operation.

try {
    $apiInstance->resumeBatchOperation($batchOperationKey);
} catch (Exception $e) {
    echo 'Exception when calling BatchOperationApi->resumeBatchOperation: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **batchOperationKey** | **string**| The key (or operate legacy ID) of the batch operation. | |

### Return type

void (empty response body)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchBatchOperationItems()`

```php
searchBatchOperationItems($batchOperationItemSearchQuery): \Camunda\Orchestration\Api\Model\BatchOperationItemSearchQueryResult
```

Search batch operation items

Search for batch operation items based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BatchOperationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$batchOperationItemSearchQuery = new \Camunda\Orchestration\Api\Model\BatchOperationItemSearchQuery(); // \Camunda\Orchestration\Api\Model\BatchOperationItemSearchQuery

try {
    $result = $apiInstance->searchBatchOperationItems($batchOperationItemSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BatchOperationApi->searchBatchOperationItems: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **batchOperationItemSearchQuery** | [**\Camunda\Orchestration\Api\Model\BatchOperationItemSearchQuery**](../Model/BatchOperationItemSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\BatchOperationItemSearchQueryResult**](../Model/BatchOperationItemSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchBatchOperations()`

```php
searchBatchOperations($batchOperationSearchQuery): \Camunda\Orchestration\Api\Model\BatchOperationSearchQueryResult
```

Search batch operations

Search for batch operations based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\BatchOperationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$batchOperationSearchQuery = new \Camunda\Orchestration\Api\Model\BatchOperationSearchQuery(); // \Camunda\Orchestration\Api\Model\BatchOperationSearchQuery

try {
    $result = $apiInstance->searchBatchOperations($batchOperationSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling BatchOperationApi->searchBatchOperations: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **batchOperationSearchQuery** | [**\Camunda\Orchestration\Api\Model\BatchOperationSearchQuery**](../Model/BatchOperationSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\BatchOperationSearchQueryResult**](../Model/BatchOperationSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `suspendBatchOperation()`

```php
suspendBatchOperation($batchOperationKey)
```

Suspend Batch operation

Suspends a running batch operation. This is done asynchronously, the progress can be tracked using the batch operation status endpoint (/batch-operations/{batchOperationKey}).

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


$apiInstance = new Camunda\Orchestration\Api\Api\BatchOperationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$batchOperationKey = 'batchOperationKey_example'; // string | The key (or operate legacy ID) of the batch operation.

try {
    $apiInstance->suspendBatchOperation($batchOperationKey);
} catch (Exception $e) {
    echo 'Exception when calling BatchOperationApi->suspendBatchOperation: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **batchOperationKey** | **string**| The key (or operate legacy ID) of the batch operation. | |

### Return type

void (empty response body)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
