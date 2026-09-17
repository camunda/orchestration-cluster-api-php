# Camunda\Orchestration\Api\GlobalListenerApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**createGlobalTaskListener()**](GlobalListenerApi.md#createGlobalTaskListener) | **POST** /global-task-listeners | Create global user task listener |
| [**deleteGlobalTaskListener()**](GlobalListenerApi.md#deleteGlobalTaskListener) | **DELETE** /global-task-listeners/{id} | Delete global user task listener |
| [**getGlobalTaskListener()**](GlobalListenerApi.md#getGlobalTaskListener) | **GET** /global-task-listeners/{id} | Get global user task listener |
| [**searchGlobalTaskListeners()**](GlobalListenerApi.md#searchGlobalTaskListeners) | **POST** /global-task-listeners/search | Search global user task listeners |
| [**updateGlobalTaskListener()**](GlobalListenerApi.md#updateGlobalTaskListener) | **PUT** /global-task-listeners/{id} | Update global user task listener |


## `createGlobalTaskListener()`

```php
createGlobalTaskListener($createGlobalTaskListenerRequest): \Camunda\Orchestration\Api\Model\GlobalTaskListenerResult
```

Create global user task listener

Create a new global user task listener.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GlobalListenerApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$createGlobalTaskListenerRequest = new \Camunda\Orchestration\Api\Model\CreateGlobalTaskListenerRequest(); // \Camunda\Orchestration\Api\Model\CreateGlobalTaskListenerRequest

try {
    $result = $apiInstance->createGlobalTaskListener($createGlobalTaskListenerRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling GlobalListenerApi->createGlobalTaskListener: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **createGlobalTaskListenerRequest** | [**\Camunda\Orchestration\Api\Model\CreateGlobalTaskListenerRequest**](../Model/CreateGlobalTaskListenerRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\GlobalTaskListenerResult**](../Model/GlobalTaskListenerResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `deleteGlobalTaskListener()`

```php
deleteGlobalTaskListener($id)
```

Delete global user task listener

Deletes a global user task listener.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GlobalListenerApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id = 'id_example'; // string | The id of the global user task listener to delete.

try {
    $apiInstance->deleteGlobalTaskListener($id);
} catch (Exception $e) {
    echo 'Exception when calling GlobalListenerApi->deleteGlobalTaskListener: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**| The id of the global user task listener to delete. | |

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

## `getGlobalTaskListener()`

```php
getGlobalTaskListener($id): \Camunda\Orchestration\Api\Model\GlobalTaskListenerResult
```

Get global user task listener

Get a global user task listener by its id.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GlobalListenerApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id = 'id_example'; // string | The id of the global user task listener.

try {
    $result = $apiInstance->getGlobalTaskListener($id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling GlobalListenerApi->getGlobalTaskListener: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**| The id of the global user task listener. | |

### Return type

[**\Camunda\Orchestration\Api\Model\GlobalTaskListenerResult**](../Model/GlobalTaskListenerResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchGlobalTaskListeners()`

```php
searchGlobalTaskListeners($globalTaskListenerSearchQueryRequest): \Camunda\Orchestration\Api\Model\GlobalTaskListenerSearchQueryResult
```

Search global user task listeners

Search for global user task listeners based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GlobalListenerApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$globalTaskListenerSearchQueryRequest = new \Camunda\Orchestration\Api\Model\GlobalTaskListenerSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\GlobalTaskListenerSearchQueryRequest

try {
    $result = $apiInstance->searchGlobalTaskListeners($globalTaskListenerSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling GlobalListenerApi->searchGlobalTaskListeners: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **globalTaskListenerSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\GlobalTaskListenerSearchQueryRequest**](../Model/GlobalTaskListenerSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\GlobalTaskListenerSearchQueryResult**](../Model/GlobalTaskListenerSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `updateGlobalTaskListener()`

```php
updateGlobalTaskListener($id, $updateGlobalTaskListenerRequest): \Camunda\Orchestration\Api\Model\GlobalTaskListenerResult
```

Update global user task listener

Updates a global user task listener.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GlobalListenerApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id = 'id_example'; // string | The id of the global user task listener to update.
$updateGlobalTaskListenerRequest = new \Camunda\Orchestration\Api\Model\UpdateGlobalTaskListenerRequest(); // \Camunda\Orchestration\Api\Model\UpdateGlobalTaskListenerRequest

try {
    $result = $apiInstance->updateGlobalTaskListener($id, $updateGlobalTaskListenerRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling GlobalListenerApi->updateGlobalTaskListener: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**| The id of the global user task listener to update. | |
| **updateGlobalTaskListenerRequest** | [**\Camunda\Orchestration\Api\Model\UpdateGlobalTaskListenerRequest**](../Model/UpdateGlobalTaskListenerRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\GlobalTaskListenerResult**](../Model/GlobalTaskListenerResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
