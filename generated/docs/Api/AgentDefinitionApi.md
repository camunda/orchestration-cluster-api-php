# Camunda\Orchestration\Api\AgentDefinitionApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getAgentDefinition()**](AgentDefinitionApi.md#getAgentDefinition) | **GET** /agent-definitions/{agentDefinitionKey} | Get agent definition |
| [**searchAgentDefinitions()**](AgentDefinitionApi.md#searchAgentDefinitions) | **POST** /agent-definitions/search | Search agent definitions |


## `getAgentDefinition()`

```php
getAgentDefinition($agentDefinitionKey): \Camunda\Orchestration\Api\Model\AgentDefinitionResult
```

Get agent definition

Returns an agent definition by key.

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


$apiInstance = new Camunda\Orchestration\Api\Api\AgentDefinitionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$agentDefinitionKey = 'agentDefinitionKey_example'; // string | The assigned key of the agent definition, which acts as a unique identifier for this agent definition.

try {
    $result = $apiInstance->getAgentDefinition($agentDefinitionKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AgentDefinitionApi->getAgentDefinition: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **agentDefinitionKey** | **string**| The assigned key of the agent definition, which acts as a unique identifier for this agent definition. | |

### Return type

[**\Camunda\Orchestration\Api\Model\AgentDefinitionResult**](../Model/AgentDefinitionResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchAgentDefinitions()`

```php
searchAgentDefinitions($agentDefinitionSearchQuery): \Camunda\Orchestration\Api\Model\AgentDefinitionSearchQueryResult
```

Search agent definitions

Search for agent definitions based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\AgentDefinitionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$agentDefinitionSearchQuery = new \Camunda\Orchestration\Api\Model\AgentDefinitionSearchQuery(); // \Camunda\Orchestration\Api\Model\AgentDefinitionSearchQuery

try {
    $result = $apiInstance->searchAgentDefinitions($agentDefinitionSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AgentDefinitionApi->searchAgentDefinitions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **agentDefinitionSearchQuery** | [**\Camunda\Orchestration\Api\Model\AgentDefinitionSearchQuery**](../Model/AgentDefinitionSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\AgentDefinitionSearchQueryResult**](../Model/AgentDefinitionSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
