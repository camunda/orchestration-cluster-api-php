# Camunda\Orchestration\Api\ExpressionApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**evaluateExpression()**](ExpressionApi.md#evaluateExpression) | **POST** /expression/evaluation | Evaluate an expression |


## `evaluateExpression()`

```php
evaluateExpression($expressionEvaluationRequest): \Camunda\Orchestration\Api\Model\ExpressionEvaluationResult
```

Evaluate an expression

Evaluates a FEEL expression and returns the result. Supports references to tenant scoped cluster variables when a tenant ID is provided. Optionally, provide a `scopeKey` to make the variables of a specific process instance or element instance visible while evaluating the expression.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ExpressionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$expressionEvaluationRequest = new \Camunda\Orchestration\Api\Model\ExpressionEvaluationRequest(); // \Camunda\Orchestration\Api\Model\ExpressionEvaluationRequest

try {
    $result = $apiInstance->evaluateExpression($expressionEvaluationRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ExpressionApi->evaluateExpression: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **expressionEvaluationRequest** | [**\Camunda\Orchestration\Api\Model\ExpressionEvaluationRequest**](../Model/ExpressionEvaluationRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\ExpressionEvaluationResult**](../Model/ExpressionEvaluationResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
