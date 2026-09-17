# Camunda\Orchestration\Api\AgentInstanceApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**createAgentInstance()**](AgentInstanceApi.md#createAgentInstance) | **POST** /agent-instances | Create agent instance |
| [**getAgentInstance()**](AgentInstanceApi.md#getAgentInstance) | **GET** /agent-instances/{agentInstanceKey} | Get agent instance |
| [**searchAgentInstanceHistory()**](AgentInstanceApi.md#searchAgentInstanceHistory) | **POST** /agent-instances/{agentInstanceKey}/history/search | Search agent instance history |
| [**searchAgentInstances()**](AgentInstanceApi.md#searchAgentInstances) | **POST** /agent-instances/search | Search agent instances |
| [**updateAgentInstance()**](AgentInstanceApi.md#updateAgentInstance) | **PATCH** /agent-instances/{agentInstanceKey} | Update agent instance |


## `createAgentInstance()`

```php
createAgentInstance($agentInstanceCreationRequest): \Camunda\Orchestration\Api\Model\AgentInstanceCreationResult
```

Create agent instance

Creates a new agent instance. The returned key identifies the instance and must be used in subsequent update and query calls.

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


$apiInstance = new Camunda\Orchestration\Api\Api\AgentInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$agentInstanceCreationRequest = new \Camunda\Orchestration\Api\Model\AgentInstanceCreationRequest(); // \Camunda\Orchestration\Api\Model\AgentInstanceCreationRequest

try {
    $result = $apiInstance->createAgentInstance($agentInstanceCreationRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AgentInstanceApi->createAgentInstance: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **agentInstanceCreationRequest** | [**\Camunda\Orchestration\Api\Model\AgentInstanceCreationRequest**](../Model/AgentInstanceCreationRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\AgentInstanceCreationResult**](../Model/AgentInstanceCreationResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getAgentInstance()`

```php
getAgentInstance($agentInstanceKey): \Camunda\Orchestration\Api\Model\AgentInstanceResult
```

Get agent instance

Returns agent instance as JSON.

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


$apiInstance = new Camunda\Orchestration\Api\Api\AgentInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$agentInstanceKey = 'agentInstanceKey_example'; // string | The key of the agent instance to retrieve.

try {
    $result = $apiInstance->getAgentInstance($agentInstanceKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AgentInstanceApi->getAgentInstance: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **agentInstanceKey** | **string**| The key of the agent instance to retrieve. | |

### Return type

[**\Camunda\Orchestration\Api\Model\AgentInstanceResult**](../Model/AgentInstanceResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchAgentInstanceHistory()`

```php
searchAgentInstanceHistory($agentInstanceKey, $agentInstanceHistorySearchQuery): \Camunda\Orchestration\Api\Model\AgentInstanceHistorySearchQueryResult
```

Search agent instance history

Searches the conversation history of an agent instance. Committed items are returned by default.

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


$apiInstance = new Camunda\Orchestration\Api\Api\AgentInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$agentInstanceKey = 'agentInstanceKey_example'; // string | The key of the agent instance whose history to search.
$agentInstanceHistorySearchQuery = new \Camunda\Orchestration\Api\Model\AgentInstanceHistorySearchQuery(); // \Camunda\Orchestration\Api\Model\AgentInstanceHistorySearchQuery

try {
    $result = $apiInstance->searchAgentInstanceHistory($agentInstanceKey, $agentInstanceHistorySearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AgentInstanceApi->searchAgentInstanceHistory: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **agentInstanceKey** | **string**| The key of the agent instance whose history to search. | |
| **agentInstanceHistorySearchQuery** | [**\Camunda\Orchestration\Api\Model\AgentInstanceHistorySearchQuery**](../Model/AgentInstanceHistorySearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\AgentInstanceHistorySearchQueryResult**](../Model/AgentInstanceHistorySearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchAgentInstances()`

```php
searchAgentInstances($agentInstanceSearchQuery): \Camunda\Orchestration\Api\Model\AgentInstanceSearchQueryResult
```

Search agent instances

Search for agent instances based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\AgentInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$agentInstanceSearchQuery = new \Camunda\Orchestration\Api\Model\AgentInstanceSearchQuery(); // \Camunda\Orchestration\Api\Model\AgentInstanceSearchQuery

try {
    $result = $apiInstance->searchAgentInstances($agentInstanceSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AgentInstanceApi->searchAgentInstances: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **agentInstanceSearchQuery** | [**\Camunda\Orchestration\Api\Model\AgentInstanceSearchQuery**](../Model/AgentInstanceSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\AgentInstanceSearchQueryResult**](../Model/AgentInstanceSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `updateAgentInstance()`

```php
updateAgentInstance($agentInstanceKey, $agentInstanceUpdateRequest): \Camunda\Orchestration\Api\Model\AgentInstanceUpdateResult
```

Update agent instance

Updates the status of an agent instance and appends a batch of history items to its conversation history. Each history item created for this request is echoed back in the response.

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


$apiInstance = new Camunda\Orchestration\Api\Api\AgentInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$agentInstanceKey = 'agentInstanceKey_example'; // string | The key of the agent instance to update.
$agentInstanceUpdateRequest = new \Camunda\Orchestration\Api\Model\AgentInstanceUpdateRequest(); // \Camunda\Orchestration\Api\Model\AgentInstanceUpdateRequest

try {
    $result = $apiInstance->updateAgentInstance($agentInstanceKey, $agentInstanceUpdateRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AgentInstanceApi->updateAgentInstance: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **agentInstanceKey** | **string**| The key of the agent instance to update. | |
| **agentInstanceUpdateRequest** | [**\Camunda\Orchestration\Api\Model\AgentInstanceUpdateRequest**](../Model/AgentInstanceUpdateRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\AgentInstanceUpdateResult**](../Model/AgentInstanceUpdateResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
