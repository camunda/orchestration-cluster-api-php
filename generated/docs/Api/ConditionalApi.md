# Camunda\Orchestration\Api\ConditionalApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**evaluateConditionals()**](ConditionalApi.md#evaluateConditionals) | **POST** /conditionals/evaluation | Evaluate root level conditional start events |


## `evaluateConditionals()`

```php
evaluateConditionals($conditionalEvaluationInstruction): \Camunda\Orchestration\Api\Model\EvaluateConditionalResult
```

Evaluate root level conditional start events

Evaluates root-level conditional start events for process definitions. If the evaluation is successful, it will return the keys of all created process instances, along with their associated process definition key. Multiple root-level conditional start events of the same process definition can trigger if their conditions evaluate to true.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ConditionalApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$conditionalEvaluationInstruction = {"variables":{"orderAmount":1000,"region":"EU"}}; // \Camunda\Orchestration\Api\Model\ConditionalEvaluationInstruction

try {
    $result = $apiInstance->evaluateConditionals($conditionalEvaluationInstruction);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ConditionalApi->evaluateConditionals: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **conditionalEvaluationInstruction** | [**\Camunda\Orchestration\Api\Model\ConditionalEvaluationInstruction**](../Model/ConditionalEvaluationInstruction.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\EvaluateConditionalResult**](../Model/EvaluateConditionalResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
