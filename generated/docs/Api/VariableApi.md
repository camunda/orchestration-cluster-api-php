# Camunda\Orchestration\Api\VariableApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getVariable()**](VariableApi.md#getVariable) | **GET** /variables/{variableKey} | Get variable |
| [**searchVariables()**](VariableApi.md#searchVariables) | **POST** /variables/search | Search variables |


## `getVariable()`

```php
getVariable($variableKey): \Camunda\Orchestration\Api\Model\VariableResult
```

Get variable

Get a variable by its key.  This endpoint returns both process-level and local (element-scoped) variables. The variable's scopeKey indicates whether it's a process-level variable or scoped to a specific element instance.

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


$apiInstance = new Camunda\Orchestration\Api\Api\VariableApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$variableKey = 'variableKey_example'; // string | The variable key.

try {
    $result = $apiInstance->getVariable($variableKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling VariableApi->getVariable: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **variableKey** | **string**| The variable key. | |

### Return type

[**\Camunda\Orchestration\Api\Model\VariableResult**](../Model/VariableResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchVariables()`

```php
searchVariables($truncateValues, $variableSearchQuery): \Camunda\Orchestration\Api\Model\VariableSearchQueryResult
```

Search variables

Search for variables based on given criteria.  This endpoint returns variables that exist directly at the specified scopes - it does not include variables from parent scopes that would be visible through the scope hierarchy.  Variables can be process-level (scoped to the process instance) or local (scoped to specific BPMN elements like tasks, subprocesses, etc.).  By default, long variable values in the response are truncated.

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


$apiInstance = new Camunda\Orchestration\Api\Api\VariableApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$truncateValues = True; // bool | When true (default), long variable values in the response are truncated. When false, full variable values are returned.
$variableSearchQuery = new \Camunda\Orchestration\Api\Model\VariableSearchQuery(); // \Camunda\Orchestration\Api\Model\VariableSearchQuery

try {
    $result = $apiInstance->searchVariables($truncateValues, $variableSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling VariableApi->searchVariables: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **truncateValues** | **bool**| When true (default), long variable values in the response are truncated. When false, full variable values are returned. | [optional] |
| **variableSearchQuery** | [**\Camunda\Orchestration\Api\Model\VariableSearchQuery**](../Model/VariableSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\VariableSearchQueryResult**](../Model/VariableSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
