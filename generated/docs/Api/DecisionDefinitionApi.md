# Camunda\Orchestration\Api\DecisionDefinitionApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**evaluateDecision()**](DecisionDefinitionApi.md#evaluateDecision) | **POST** /decision-definitions/evaluation | Evaluate decision |
| [**getDecisionDefinition()**](DecisionDefinitionApi.md#getDecisionDefinition) | **GET** /decision-definitions/{decisionDefinitionKey} | Get decision definition |
| [**getDecisionDefinitionXML()**](DecisionDefinitionApi.md#getDecisionDefinitionXML) | **GET** /decision-definitions/{decisionDefinitionKey}/xml | Get decision definition XML |
| [**searchDecisionDefinitions()**](DecisionDefinitionApi.md#searchDecisionDefinitions) | **POST** /decision-definitions/search | Search decision definitions |


## `evaluateDecision()`

```php
evaluateDecision($decisionEvaluationInstruction): \Camunda\Orchestration\Api\Model\EvaluateDecisionResult
```

Evaluate decision

Evaluates a decision. You specify the decision to evaluate either by using its unique key (as returned by DeployResource), or using the decision ID. When using the decision ID, the latest deployed version of the decision is used.

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


$apiInstance = new Camunda\Orchestration\Api\Api\DecisionDefinitionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$decisionEvaluationInstruction = {"decisionDefinitionKey":"12345","variables":{}}; // \Camunda\Orchestration\Api\Model\DecisionEvaluationInstruction

try {
    $result = $apiInstance->evaluateDecision($decisionEvaluationInstruction);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DecisionDefinitionApi->evaluateDecision: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **decisionEvaluationInstruction** | [**\Camunda\Orchestration\Api\Model\DecisionEvaluationInstruction**](../Model/DecisionEvaluationInstruction.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\EvaluateDecisionResult**](../Model/EvaluateDecisionResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getDecisionDefinition()`

```php
getDecisionDefinition($decisionDefinitionKey): \Camunda\Orchestration\Api\Model\DecisionDefinitionResult
```

Get decision definition

Returns a decision definition by key.

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


$apiInstance = new Camunda\Orchestration\Api\Api\DecisionDefinitionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$decisionDefinitionKey = 'decisionDefinitionKey_example'; // string | The assigned key of the decision definition, which acts as a unique identifier for this decision.

try {
    $result = $apiInstance->getDecisionDefinition($decisionDefinitionKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DecisionDefinitionApi->getDecisionDefinition: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **decisionDefinitionKey** | **string**| The assigned key of the decision definition, which acts as a unique identifier for this decision. | |

### Return type

[**\Camunda\Orchestration\Api\Model\DecisionDefinitionResult**](../Model/DecisionDefinitionResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getDecisionDefinitionXML()`

```php
getDecisionDefinitionXML($decisionDefinitionKey): string
```

Get decision definition XML

Returns decision definition as XML.

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


$apiInstance = new Camunda\Orchestration\Api\Api\DecisionDefinitionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$decisionDefinitionKey = 'decisionDefinitionKey_example'; // string | The assigned key of the decision definition, which acts as a unique identifier for this decision.

try {
    $result = $apiInstance->getDecisionDefinitionXML($decisionDefinitionKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DecisionDefinitionApi->getDecisionDefinitionXML: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **decisionDefinitionKey** | **string**| The assigned key of the decision definition, which acts as a unique identifier for this decision. | |

### Return type

**string**

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `text/xml`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchDecisionDefinitions()`

```php
searchDecisionDefinitions($decisionDefinitionSearchQuery): \Camunda\Orchestration\Api\Model\DecisionDefinitionSearchQueryResult
```

Search decision definitions

Search for decision definitions based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\DecisionDefinitionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$decisionDefinitionSearchQuery = new \Camunda\Orchestration\Api\Model\DecisionDefinitionSearchQuery(); // \Camunda\Orchestration\Api\Model\DecisionDefinitionSearchQuery

try {
    $result = $apiInstance->searchDecisionDefinitions($decisionDefinitionSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DecisionDefinitionApi->searchDecisionDefinitions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **decisionDefinitionSearchQuery** | [**\Camunda\Orchestration\Api\Model\DecisionDefinitionSearchQuery**](../Model/DecisionDefinitionSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\DecisionDefinitionSearchQueryResult**](../Model/DecisionDefinitionSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
