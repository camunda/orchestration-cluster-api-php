# Camunda\Orchestration\Api\ProcessDefinitionApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getProcessDefinition()**](ProcessDefinitionApi.md#getProcessDefinition) | **GET** /process-definitions/{processDefinitionKey} | Get process definition |
| [**getProcessDefinitionInstanceStatistics()**](ProcessDefinitionApi.md#getProcessDefinitionInstanceStatistics) | **POST** /process-definitions/statistics/process-instances | Get process instance statistics |
| [**getProcessDefinitionInstanceVersionStatistics()**](ProcessDefinitionApi.md#getProcessDefinitionInstanceVersionStatistics) | **POST** /process-definitions/statistics/process-instances-by-version | Get process instance statistics by version |
| [**getProcessDefinitionMessageSubscriptionStatistics()**](ProcessDefinitionApi.md#getProcessDefinitionMessageSubscriptionStatistics) | **POST** /process-definitions/statistics/message-subscriptions | Get message subscription statistics |
| [**getProcessDefinitionStatistics()**](ProcessDefinitionApi.md#getProcessDefinitionStatistics) | **POST** /process-definitions/{processDefinitionKey}/statistics/element-instances | Get process definition statistics |
| [**getProcessDefinitionXML()**](ProcessDefinitionApi.md#getProcessDefinitionXML) | **GET** /process-definitions/{processDefinitionKey}/xml | Get process definition XML |
| [**getStartProcessForm()**](ProcessDefinitionApi.md#getStartProcessForm) | **GET** /process-definitions/{processDefinitionKey}/form | Get process start form |
| [**searchProcessDefinitionVariableNames()**](ProcessDefinitionApi.md#searchProcessDefinitionVariableNames) | **POST** /process-definitions/{processDefinitionKey}/variable-names/search | Search process definition variable names |
| [**searchProcessDefinitions()**](ProcessDefinitionApi.md#searchProcessDefinitions) | **POST** /process-definitions/search | Search process definitions |


## `getProcessDefinition()`

```php
getProcessDefinition($processDefinitionKey): \Camunda\Orchestration\Api\Model\ProcessDefinitionResult
```

Get process definition

Returns process definition as JSON.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessDefinitionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processDefinitionKey = 'processDefinitionKey_example'; // string | The assigned key of the process definition, which acts as a unique identifier for this process definition.

try {
    $result = $apiInstance->getProcessDefinition($processDefinitionKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessDefinitionApi->getProcessDefinition: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processDefinitionKey** | **string**| The assigned key of the process definition, which acts as a unique identifier for this process definition. | |

### Return type

[**\Camunda\Orchestration\Api\Model\ProcessDefinitionResult**](../Model/ProcessDefinitionResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getProcessDefinitionInstanceStatistics()`

```php
getProcessDefinitionInstanceStatistics($processDefinitionInstanceStatisticsQuery): \Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceStatisticsQueryResult
```

Get process instance statistics

Get statistics about process instances, grouped by process definition and tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessDefinitionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processDefinitionInstanceStatisticsQuery = new \Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceStatisticsQuery(); // \Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceStatisticsQuery

try {
    $result = $apiInstance->getProcessDefinitionInstanceStatistics($processDefinitionInstanceStatisticsQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessDefinitionApi->getProcessDefinitionInstanceStatistics: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processDefinitionInstanceStatisticsQuery** | [**\Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceStatisticsQuery**](../Model/ProcessDefinitionInstanceStatisticsQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceStatisticsQueryResult**](../Model/ProcessDefinitionInstanceStatisticsQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getProcessDefinitionInstanceVersionStatistics()`

```php
getProcessDefinitionInstanceVersionStatistics($processDefinitionInstanceVersionStatisticsQuery): \Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceVersionStatisticsQueryResult
```

Get process instance statistics by version

Get statistics about process instances, grouped by version for a given process definition. The process definition ID must be provided as a required field in the request body filter.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessDefinitionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processDefinitionInstanceVersionStatisticsQuery = new \Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceVersionStatisticsQuery(); // \Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceVersionStatisticsQuery

try {
    $result = $apiInstance->getProcessDefinitionInstanceVersionStatistics($processDefinitionInstanceVersionStatisticsQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessDefinitionApi->getProcessDefinitionInstanceVersionStatistics: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processDefinitionInstanceVersionStatisticsQuery** | [**\Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceVersionStatisticsQuery**](../Model/ProcessDefinitionInstanceVersionStatisticsQuery.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\ProcessDefinitionInstanceVersionStatisticsQueryResult**](../Model/ProcessDefinitionInstanceVersionStatisticsQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getProcessDefinitionMessageSubscriptionStatistics()`

```php
getProcessDefinitionMessageSubscriptionStatistics($processDefinitionMessageSubscriptionStatisticsQuery): \Camunda\Orchestration\Api\Model\ProcessDefinitionMessageSubscriptionStatisticsQueryResult
```

Get message subscription statistics

Get message subscription statistics, grouped by process definition.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessDefinitionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processDefinitionMessageSubscriptionStatisticsQuery = new \Camunda\Orchestration\Api\Model\ProcessDefinitionMessageSubscriptionStatisticsQuery(); // \Camunda\Orchestration\Api\Model\ProcessDefinitionMessageSubscriptionStatisticsQuery

try {
    $result = $apiInstance->getProcessDefinitionMessageSubscriptionStatistics($processDefinitionMessageSubscriptionStatisticsQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessDefinitionApi->getProcessDefinitionMessageSubscriptionStatistics: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processDefinitionMessageSubscriptionStatisticsQuery** | [**\Camunda\Orchestration\Api\Model\ProcessDefinitionMessageSubscriptionStatisticsQuery**](../Model/ProcessDefinitionMessageSubscriptionStatisticsQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ProcessDefinitionMessageSubscriptionStatisticsQueryResult**](../Model/ProcessDefinitionMessageSubscriptionStatisticsQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getProcessDefinitionStatistics()`

```php
getProcessDefinitionStatistics($processDefinitionKey, $processDefinitionElementStatisticsQuery): \Camunda\Orchestration\Api\Model\ProcessDefinitionElementStatisticsQueryResult
```

Get process definition statistics

Get statistics about elements in currently running process instances by process definition key and search filter.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessDefinitionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processDefinitionKey = 'processDefinitionKey_example'; // string | The assigned key of the process definition, which acts as a unique identifier for this process definition.
$processDefinitionElementStatisticsQuery = new \Camunda\Orchestration\Api\Model\ProcessDefinitionElementStatisticsQuery(); // \Camunda\Orchestration\Api\Model\ProcessDefinitionElementStatisticsQuery

try {
    $result = $apiInstance->getProcessDefinitionStatistics($processDefinitionKey, $processDefinitionElementStatisticsQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessDefinitionApi->getProcessDefinitionStatistics: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processDefinitionKey** | **string**| The assigned key of the process definition, which acts as a unique identifier for this process definition. | |
| **processDefinitionElementStatisticsQuery** | [**\Camunda\Orchestration\Api\Model\ProcessDefinitionElementStatisticsQuery**](../Model/ProcessDefinitionElementStatisticsQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ProcessDefinitionElementStatisticsQueryResult**](../Model/ProcessDefinitionElementStatisticsQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getProcessDefinitionXML()`

```php
getProcessDefinitionXML($processDefinitionKey): string
```

Get process definition XML

Returns process definition as XML.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessDefinitionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processDefinitionKey = 'processDefinitionKey_example'; // string | The assigned key of the process definition, which acts as a unique identifier for this process definition.

try {
    $result = $apiInstance->getProcessDefinitionXML($processDefinitionKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessDefinitionApi->getProcessDefinitionXML: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processDefinitionKey** | **string**| The assigned key of the process definition, which acts as a unique identifier for this process definition. | |

### Return type

**string**

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `text/xml`, `text/plain`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getStartProcessForm()`

```php
getStartProcessForm($processDefinitionKey): \Camunda\Orchestration\Api\Model\FormResult
```

Get process start form

Get the start form of a process. Note that this endpoint will only return linked forms. This endpoint does not support embedded forms.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessDefinitionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processDefinitionKey = 'processDefinitionKey_example'; // string | The process key.

try {
    $result = $apiInstance->getStartProcessForm($processDefinitionKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessDefinitionApi->getStartProcessForm: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processDefinitionKey** | **string**| The process key. | |

### Return type

[**\Camunda\Orchestration\Api\Model\FormResult**](../Model/FormResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchProcessDefinitionVariableNames()`

```php
searchProcessDefinitionVariableNames($processDefinitionKey, $processDefinitionVariableNameSearchQuery): \Camunda\Orchestration\Api\Model\ProcessDefinitionVariableNameSearchQueryResult
```

Search process definition variable names

Search for distinct variable names defined on a process definition, optionally narrowed by the name filter.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessDefinitionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processDefinitionKey = 'processDefinitionKey_example'; // string | The assigned key of the process definition, which acts as a unique identifier for this process definition.
$processDefinitionVariableNameSearchQuery = new \Camunda\Orchestration\Api\Model\ProcessDefinitionVariableNameSearchQuery(); // \Camunda\Orchestration\Api\Model\ProcessDefinitionVariableNameSearchQuery

try {
    $result = $apiInstance->searchProcessDefinitionVariableNames($processDefinitionKey, $processDefinitionVariableNameSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessDefinitionApi->searchProcessDefinitionVariableNames: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processDefinitionKey** | **string**| The assigned key of the process definition, which acts as a unique identifier for this process definition. | |
| **processDefinitionVariableNameSearchQuery** | [**\Camunda\Orchestration\Api\Model\ProcessDefinitionVariableNameSearchQuery**](../Model/ProcessDefinitionVariableNameSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ProcessDefinitionVariableNameSearchQueryResult**](../Model/ProcessDefinitionVariableNameSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchProcessDefinitions()`

```php
searchProcessDefinitions($processDefinitionSearchQuery): \Camunda\Orchestration\Api\Model\ProcessDefinitionSearchQueryResult
```

Search process definitions

Search for process definitions based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessDefinitionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processDefinitionSearchQuery = new \Camunda\Orchestration\Api\Model\ProcessDefinitionSearchQuery(); // \Camunda\Orchestration\Api\Model\ProcessDefinitionSearchQuery

try {
    $result = $apiInstance->searchProcessDefinitions($processDefinitionSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessDefinitionApi->searchProcessDefinitions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processDefinitionSearchQuery** | [**\Camunda\Orchestration\Api\Model\ProcessDefinitionSearchQuery**](../Model/ProcessDefinitionSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ProcessDefinitionSearchQueryResult**](../Model/ProcessDefinitionSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
