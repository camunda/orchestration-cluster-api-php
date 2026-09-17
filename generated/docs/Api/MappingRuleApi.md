# Camunda\Orchestration\Api\MappingRuleApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**createMappingRule()**](MappingRuleApi.md#createMappingRule) | **POST** /mapping-rules | Create mapping rule |
| [**deleteMappingRule()**](MappingRuleApi.md#deleteMappingRule) | **DELETE** /mapping-rules/{mappingRuleId} | Delete a mapping rule |
| [**getMappingRule()**](MappingRuleApi.md#getMappingRule) | **GET** /mapping-rules/{mappingRuleId} | Get a mapping rule |
| [**searchMappingRule()**](MappingRuleApi.md#searchMappingRule) | **POST** /mapping-rules/search | Search mapping rules |
| [**updateMappingRule()**](MappingRuleApi.md#updateMappingRule) | **PUT** /mapping-rules/{mappingRuleId} | Update mapping rule |


## `createMappingRule()`

```php
createMappingRule($mappingRuleCreateRequest): \Camunda\Orchestration\Api\Model\MappingRuleCreateResult
```

Create mapping rule

Create a new mapping rule

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


$apiInstance = new Camunda\Orchestration\Api\Api\MappingRuleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$mappingRuleCreateRequest = new \Camunda\Orchestration\Api\Model\MappingRuleCreateRequest(); // \Camunda\Orchestration\Api\Model\MappingRuleCreateRequest

try {
    $result = $apiInstance->createMappingRule($mappingRuleCreateRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling MappingRuleApi->createMappingRule: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **mappingRuleCreateRequest** | [**\Camunda\Orchestration\Api\Model\MappingRuleCreateRequest**](../Model/MappingRuleCreateRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\MappingRuleCreateResult**](../Model/MappingRuleCreateResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `deleteMappingRule()`

```php
deleteMappingRule($mappingRuleId)
```

Delete a mapping rule

Deletes the mapping rule with the given ID.

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


$apiInstance = new Camunda\Orchestration\Api\Api\MappingRuleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$mappingRuleId = 'mappingRuleId_example'; // string | The ID of the mapping rule to delete.

try {
    $apiInstance->deleteMappingRule($mappingRuleId);
} catch (Exception $e) {
    echo 'Exception when calling MappingRuleApi->deleteMappingRule: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **mappingRuleId** | **string**| The ID of the mapping rule to delete. | |

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

## `getMappingRule()`

```php
getMappingRule($mappingRuleId): \Camunda\Orchestration\Api\Model\MappingRuleResult
```

Get a mapping rule

Gets the mapping rule with the given ID.

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


$apiInstance = new Camunda\Orchestration\Api\Api\MappingRuleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$mappingRuleId = 'mappingRuleId_example'; // string | The ID of the mapping rule to get.

try {
    $result = $apiInstance->getMappingRule($mappingRuleId);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling MappingRuleApi->getMappingRule: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **mappingRuleId** | **string**| The ID of the mapping rule to get. | |

### Return type

[**\Camunda\Orchestration\Api\Model\MappingRuleResult**](../Model/MappingRuleResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchMappingRule()`

```php
searchMappingRule($mappingRuleSearchQueryRequest): \Camunda\Orchestration\Api\Model\MappingRuleSearchQueryResult
```

Search mapping rules

Search for mapping rules based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\MappingRuleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$mappingRuleSearchQueryRequest = new \Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest

try {
    $result = $apiInstance->searchMappingRule($mappingRuleSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling MappingRuleApi->searchMappingRule: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **mappingRuleSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest**](../Model/MappingRuleSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\MappingRuleSearchQueryResult**](../Model/MappingRuleSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `updateMappingRule()`

```php
updateMappingRule($mappingRuleId, $mappingRuleUpdateRequest): \Camunda\Orchestration\Api\Model\MappingRuleUpdateResult
```

Update mapping rule

Update a mapping rule.

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


$apiInstance = new Camunda\Orchestration\Api\Api\MappingRuleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$mappingRuleId = 'mappingRuleId_example'; // string | The ID of the mapping rule to update.
$mappingRuleUpdateRequest = new \Camunda\Orchestration\Api\Model\MappingRuleUpdateRequest(); // \Camunda\Orchestration\Api\Model\MappingRuleUpdateRequest

try {
    $result = $apiInstance->updateMappingRule($mappingRuleId, $mappingRuleUpdateRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling MappingRuleApi->updateMappingRule: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **mappingRuleId** | **string**| The ID of the mapping rule to update. | |
| **mappingRuleUpdateRequest** | [**\Camunda\Orchestration\Api\Model\MappingRuleUpdateRequest**](../Model/MappingRuleUpdateRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\MappingRuleUpdateResult**](../Model/MappingRuleUpdateResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
