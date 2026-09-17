# Camunda\Orchestration\Api\ClusterVariableApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**createGlobalClusterVariable()**](ClusterVariableApi.md#createGlobalClusterVariable) | **POST** /cluster-variables/global | Create a global-scoped cluster variable |
| [**createTenantClusterVariable()**](ClusterVariableApi.md#createTenantClusterVariable) | **POST** /cluster-variables/tenants/{tenantId} | Create a tenant-scoped cluster variable |
| [**deleteGlobalClusterVariable()**](ClusterVariableApi.md#deleteGlobalClusterVariable) | **DELETE** /cluster-variables/global/{name} | Delete a global-scoped cluster variable |
| [**deleteTenantClusterVariable()**](ClusterVariableApi.md#deleteTenantClusterVariable) | **DELETE** /cluster-variables/tenants/{tenantId}/{name} | Delete a tenant-scoped cluster variable |
| [**getGlobalClusterVariable()**](ClusterVariableApi.md#getGlobalClusterVariable) | **GET** /cluster-variables/global/{name} | Get a global-scoped cluster variable |
| [**getTenantClusterVariable()**](ClusterVariableApi.md#getTenantClusterVariable) | **GET** /cluster-variables/tenants/{tenantId}/{name} | Get a tenant-scoped cluster variable |
| [**searchClusterVariables()**](ClusterVariableApi.md#searchClusterVariables) | **POST** /cluster-variables/search |  |
| [**updateGlobalClusterVariable()**](ClusterVariableApi.md#updateGlobalClusterVariable) | **PUT** /cluster-variables/global/{name} | Update a global-scoped cluster variable |
| [**updateTenantClusterVariable()**](ClusterVariableApi.md#updateTenantClusterVariable) | **PUT** /cluster-variables/tenants/{tenantId}/{name} | Update a tenant-scoped cluster variable |


## `createGlobalClusterVariable()`

```php
createGlobalClusterVariable($createClusterVariableRequest): \Camunda\Orchestration\Api\Model\ClusterVariableResult
```

Create a global-scoped cluster variable

Create a global-scoped cluster variable.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ClusterVariableApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$createClusterVariableRequest = new \Camunda\Orchestration\Api\Model\CreateClusterVariableRequest(); // \Camunda\Orchestration\Api\Model\CreateClusterVariableRequest

try {
    $result = $apiInstance->createGlobalClusterVariable($createClusterVariableRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ClusterVariableApi->createGlobalClusterVariable: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **createClusterVariableRequest** | [**\Camunda\Orchestration\Api\Model\CreateClusterVariableRequest**](../Model/CreateClusterVariableRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterVariableResult**](../Model/ClusterVariableResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `createTenantClusterVariable()`

```php
createTenantClusterVariable($tenantId, $createClusterVariableRequest): \Camunda\Orchestration\Api\Model\ClusterVariableResult
```

Create a tenant-scoped cluster variable

Create a new cluster variable for the given tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ClusterVariableApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The tenant ID
$createClusterVariableRequest = new \Camunda\Orchestration\Api\Model\CreateClusterVariableRequest(); // \Camunda\Orchestration\Api\Model\CreateClusterVariableRequest

try {
    $result = $apiInstance->createTenantClusterVariable($tenantId, $createClusterVariableRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ClusterVariableApi->createTenantClusterVariable: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The tenant ID | |
| **createClusterVariableRequest** | [**\Camunda\Orchestration\Api\Model\CreateClusterVariableRequest**](../Model/CreateClusterVariableRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterVariableResult**](../Model/ClusterVariableResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `deleteGlobalClusterVariable()`

```php
deleteGlobalClusterVariable($name)
```

Delete a global-scoped cluster variable

Delete a global-scoped cluster variable.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ClusterVariableApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$name = 'name_example'; // string | The name of the cluster variable

try {
    $apiInstance->deleteGlobalClusterVariable($name);
} catch (Exception $e) {
    echo 'Exception when calling ClusterVariableApi->deleteGlobalClusterVariable: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **name** | **string**| The name of the cluster variable | |

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

## `deleteTenantClusterVariable()`

```php
deleteTenantClusterVariable($tenantId, $name)
```

Delete a tenant-scoped cluster variable

Delete a tenant-scoped cluster variable.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ClusterVariableApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The tenant ID
$name = 'name_example'; // string | The name of the cluster variable

try {
    $apiInstance->deleteTenantClusterVariable($tenantId, $name);
} catch (Exception $e) {
    echo 'Exception when calling ClusterVariableApi->deleteTenantClusterVariable: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The tenant ID | |
| **name** | **string**| The name of the cluster variable | |

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

## `getGlobalClusterVariable()`

```php
getGlobalClusterVariable($name): \Camunda\Orchestration\Api\Model\ClusterVariableResult
```

Get a global-scoped cluster variable

Get a global-scoped cluster variable.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ClusterVariableApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$name = 'name_example'; // string | The name of the cluster variable

try {
    $result = $apiInstance->getGlobalClusterVariable($name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ClusterVariableApi->getGlobalClusterVariable: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **name** | **string**| The name of the cluster variable | |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterVariableResult**](../Model/ClusterVariableResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getTenantClusterVariable()`

```php
getTenantClusterVariable($tenantId, $name): \Camunda\Orchestration\Api\Model\ClusterVariableResult
```

Get a tenant-scoped cluster variable

Get a tenant-scoped cluster variable.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ClusterVariableApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The tenant ID
$name = 'name_example'; // string | The name of the cluster variable

try {
    $result = $apiInstance->getTenantClusterVariable($tenantId, $name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ClusterVariableApi->getTenantClusterVariable: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The tenant ID | |
| **name** | **string**| The name of the cluster variable | |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterVariableResult**](../Model/ClusterVariableResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchClusterVariables()`

```php
searchClusterVariables($truncateValues, $clusterVariableSearchQueryRequest): \Camunda\Orchestration\Api\Model\ClusterVariableSearchQueryResult
```



Search for cluster variables based on given criteria. By default, long variable values in the response are truncated.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ClusterVariableApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$truncateValues = True; // bool | When true (default), long variable values in the response are truncated. When false, full variable values are returned.
$clusterVariableSearchQueryRequest = new \Camunda\Orchestration\Api\Model\ClusterVariableSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\ClusterVariableSearchQueryRequest

try {
    $result = $apiInstance->searchClusterVariables($truncateValues, $clusterVariableSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ClusterVariableApi->searchClusterVariables: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **truncateValues** | **bool**| When true (default), long variable values in the response are truncated. When false, full variable values are returned. | [optional] |
| **clusterVariableSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\ClusterVariableSearchQueryRequest**](../Model/ClusterVariableSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterVariableSearchQueryResult**](../Model/ClusterVariableSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `updateGlobalClusterVariable()`

```php
updateGlobalClusterVariable($name, $updateClusterVariableRequest): \Camunda\Orchestration\Api\Model\ClusterVariableResult
```

Update a global-scoped cluster variable

Updates the value of an existing global cluster variable. The variable must exist, otherwise a 404 error is returned.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ClusterVariableApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$name = 'name_example'; // string | The name of the cluster variable
$updateClusterVariableRequest = new \Camunda\Orchestration\Api\Model\UpdateClusterVariableRequest(); // \Camunda\Orchestration\Api\Model\UpdateClusterVariableRequest

try {
    $result = $apiInstance->updateGlobalClusterVariable($name, $updateClusterVariableRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ClusterVariableApi->updateGlobalClusterVariable: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **name** | **string**| The name of the cluster variable | |
| **updateClusterVariableRequest** | [**\Camunda\Orchestration\Api\Model\UpdateClusterVariableRequest**](../Model/UpdateClusterVariableRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterVariableResult**](../Model/ClusterVariableResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `updateTenantClusterVariable()`

```php
updateTenantClusterVariable($tenantId, $name, $updateClusterVariableRequest): \Camunda\Orchestration\Api\Model\ClusterVariableResult
```

Update a tenant-scoped cluster variable

Updates the value of an existing tenant-scoped cluster variable. The variable must exist, otherwise a 404 error is returned.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ClusterVariableApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The tenant ID
$name = 'name_example'; // string | The name of the cluster variable
$updateClusterVariableRequest = new \Camunda\Orchestration\Api\Model\UpdateClusterVariableRequest(); // \Camunda\Orchestration\Api\Model\UpdateClusterVariableRequest

try {
    $result = $apiInstance->updateTenantClusterVariable($tenantId, $name, $updateClusterVariableRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ClusterVariableApi->updateTenantClusterVariable: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The tenant ID | |
| **name** | **string**| The name of the cluster variable | |
| **updateClusterVariableRequest** | [**\Camunda\Orchestration\Api\Model\UpdateClusterVariableRequest**](../Model/UpdateClusterVariableRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\ClusterVariableResult**](../Model/ClusterVariableResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
