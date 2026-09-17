# Camunda\Orchestration\Api\AuthorizationApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**createAuthorization()**](AuthorizationApi.md#createAuthorization) | **POST** /authorizations | Create authorization |
| [**deleteAuthorization()**](AuthorizationApi.md#deleteAuthorization) | **DELETE** /authorizations/{authorizationKey} | Delete authorization |
| [**getAuthorization()**](AuthorizationApi.md#getAuthorization) | **GET** /authorizations/{authorizationKey} | Get authorization |
| [**searchAuthorizations()**](AuthorizationApi.md#searchAuthorizations) | **POST** /authorizations/search | Search authorizations |
| [**updateAuthorization()**](AuthorizationApi.md#updateAuthorization) | **PUT** /authorizations/{authorizationKey} | Update authorization |


## `createAuthorization()`

```php
createAuthorization($authorizationRequest): \Camunda\Orchestration\Api\Model\AuthorizationCreateResult
```

Create authorization

Create the authorization.

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


$apiInstance = new Camunda\Orchestration\Api\Api\AuthorizationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorizationRequest = new \Camunda\Orchestration\Api\Model\AuthorizationIdBasedRequest(); // \Camunda\Orchestration\Api\Model\AuthorizationRequest

try {
    $result = $apiInstance->createAuthorization($authorizationRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AuthorizationApi->createAuthorization: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorizationRequest** | [**\Camunda\Orchestration\Api\Model\AuthorizationRequest**](../Model/AuthorizationRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\AuthorizationCreateResult**](../Model/AuthorizationCreateResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `deleteAuthorization()`

```php
deleteAuthorization($authorizationKey)
```

Delete authorization

Deletes the authorization with the given key.

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


$apiInstance = new Camunda\Orchestration\Api\Api\AuthorizationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorizationKey = 'authorizationKey_example'; // string | The key of the authorization to delete.

try {
    $apiInstance->deleteAuthorization($authorizationKey);
} catch (Exception $e) {
    echo 'Exception when calling AuthorizationApi->deleteAuthorization: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorizationKey** | **string**| The key of the authorization to delete. | |

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

## `getAuthorization()`

```php
getAuthorization($authorizationKey): \Camunda\Orchestration\Api\Model\AuthorizationResult
```

Get authorization

Get authorization by the given key.

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


$apiInstance = new Camunda\Orchestration\Api\Api\AuthorizationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorizationKey = 'authorizationKey_example'; // string | The key of the authorization to get.

try {
    $result = $apiInstance->getAuthorization($authorizationKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AuthorizationApi->getAuthorization: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorizationKey** | **string**| The key of the authorization to get. | |

### Return type

[**\Camunda\Orchestration\Api\Model\AuthorizationResult**](../Model/AuthorizationResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchAuthorizations()`

```php
searchAuthorizations($authorizationSearchQuery): \Camunda\Orchestration\Api\Model\AuthorizationSearchResult
```

Search authorizations

Search for authorizations based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\AuthorizationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorizationSearchQuery = new \Camunda\Orchestration\Api\Model\AuthorizationSearchQuery(); // \Camunda\Orchestration\Api\Model\AuthorizationSearchQuery

try {
    $result = $apiInstance->searchAuthorizations($authorizationSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AuthorizationApi->searchAuthorizations: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorizationSearchQuery** | [**\Camunda\Orchestration\Api\Model\AuthorizationSearchQuery**](../Model/AuthorizationSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\AuthorizationSearchResult**](../Model/AuthorizationSearchResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `updateAuthorization()`

```php
updateAuthorization($authorizationKey, $authorizationRequest)
```

Update authorization

Update the authorization with the given key.

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


$apiInstance = new Camunda\Orchestration\Api\Api\AuthorizationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorizationKey = 'authorizationKey_example'; // string | The key of the authorization to delete.
$authorizationRequest = new \Camunda\Orchestration\Api\Model\AuthorizationIdBasedRequest(); // \Camunda\Orchestration\Api\Model\AuthorizationRequest

try {
    $apiInstance->updateAuthorization($authorizationKey, $authorizationRequest);
} catch (Exception $e) {
    echo 'Exception when calling AuthorizationApi->updateAuthorization: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorizationKey** | **string**| The key of the authorization to delete. | |
| **authorizationRequest** | [**\Camunda\Orchestration\Api\Model\AuthorizationRequest**](../Model/AuthorizationRequest.md)|  | |

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
