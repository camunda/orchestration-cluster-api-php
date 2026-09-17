# Camunda\Orchestration\Api\DecisionInstanceApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**deleteDecisionInstance()**](DecisionInstanceApi.md#deleteDecisionInstance) | **POST** /decision-instances/{decisionEvaluationKey}/deletion | Delete decision instance |
| [**deleteDecisionInstancesBatchOperation()**](DecisionInstanceApi.md#deleteDecisionInstancesBatchOperation) | **POST** /decision-instances/deletion | Delete decision instances (batch) |
| [**getDecisionInstance()**](DecisionInstanceApi.md#getDecisionInstance) | **GET** /decision-instances/{decisionEvaluationInstanceKey} | Get decision instance |
| [**searchDecisionInstances()**](DecisionInstanceApi.md#searchDecisionInstances) | **POST** /decision-instances/search | Search decision instances |


## `deleteDecisionInstance()`

```php
deleteDecisionInstance($decisionEvaluationKey, $deleteDecisionInstanceRequest)
```

Delete decision instance

Delete all associated decision evaluations based on provided key.

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


$apiInstance = new Camunda\Orchestration\Api\Api\DecisionInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$decisionEvaluationKey = 'decisionEvaluationKey_example'; // string | The key of the decision evaluation to delete.
$deleteDecisionInstanceRequest = new \Camunda\Orchestration\Api\Model\DeleteDecisionInstanceRequest(); // \Camunda\Orchestration\Api\Model\DeleteDecisionInstanceRequest

try {
    $apiInstance->deleteDecisionInstance($decisionEvaluationKey, $deleteDecisionInstanceRequest);
} catch (Exception $e) {
    echo 'Exception when calling DecisionInstanceApi->deleteDecisionInstance: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **decisionEvaluationKey** | **string**| The key of the decision evaluation to delete. | |
| **deleteDecisionInstanceRequest** | [**\Camunda\Orchestration\Api\Model\DeleteDecisionInstanceRequest**](../Model/DeleteDecisionInstanceRequest.md)|  | [optional] |

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

## `deleteDecisionInstancesBatchOperation()`

```php
deleteDecisionInstancesBatchOperation($decisionInstanceDeletionBatchOperationRequest): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult
```

Delete decision instances (batch)

Delete multiple decision instances. This will delete the historic data from secondary storage. This is done asynchronously, the progress can be tracked using the batchOperationKey from the response and the batch operation status endpoint (/batch-operations/{batchOperationKey}).

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


$apiInstance = new Camunda\Orchestration\Api\Api\DecisionInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$decisionInstanceDeletionBatchOperationRequest = new \Camunda\Orchestration\Api\Model\DecisionInstanceDeletionBatchOperationRequest(); // \Camunda\Orchestration\Api\Model\DecisionInstanceDeletionBatchOperationRequest

try {
    $result = $apiInstance->deleteDecisionInstancesBatchOperation($decisionInstanceDeletionBatchOperationRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DecisionInstanceApi->deleteDecisionInstancesBatchOperation: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **decisionInstanceDeletionBatchOperationRequest** | [**\Camunda\Orchestration\Api\Model\DecisionInstanceDeletionBatchOperationRequest**](../Model/DecisionInstanceDeletionBatchOperationRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\BatchOperationCreatedResult**](../Model/BatchOperationCreatedResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getDecisionInstance()`

```php
getDecisionInstance($decisionEvaluationInstanceKey): \Camunda\Orchestration\Api\Model\DecisionInstanceGetQueryResult
```

Get decision instance

Returns a decision instance.

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


$apiInstance = new Camunda\Orchestration\Api\Api\DecisionInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$decisionEvaluationInstanceKey = 'decisionEvaluationInstanceKey_example'; // string | The assigned key of the decision instance, which acts as a unique identifier for this decision instance.

try {
    $result = $apiInstance->getDecisionInstance($decisionEvaluationInstanceKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DecisionInstanceApi->getDecisionInstance: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **decisionEvaluationInstanceKey** | **string**| The assigned key of the decision instance, which acts as a unique identifier for this decision instance. | |

### Return type

[**\Camunda\Orchestration\Api\Model\DecisionInstanceGetQueryResult**](../Model/DecisionInstanceGetQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchDecisionInstances()`

```php
searchDecisionInstances($decisionInstanceSearchQuery): \Camunda\Orchestration\Api\Model\DecisionInstanceSearchQueryResult
```

Search decision instances

Search for decision instances based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\DecisionInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$decisionInstanceSearchQuery = new \Camunda\Orchestration\Api\Model\DecisionInstanceSearchQuery(); // \Camunda\Orchestration\Api\Model\DecisionInstanceSearchQuery

try {
    $result = $apiInstance->searchDecisionInstances($decisionInstanceSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DecisionInstanceApi->searchDecisionInstances: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **decisionInstanceSearchQuery** | [**\Camunda\Orchestration\Api\Model\DecisionInstanceSearchQuery**](../Model/DecisionInstanceSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\DecisionInstanceSearchQueryResult**](../Model/DecisionInstanceSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
