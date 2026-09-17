# Camunda\Orchestration\Api\MessageApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**correlateMessage()**](MessageApi.md#correlateMessage) | **POST** /messages/correlation | Correlate message |
| [**publishMessage()**](MessageApi.md#publishMessage) | **POST** /messages/publication | Publish message |


## `correlateMessage()`

```php
correlateMessage($messageCorrelationRequest): \Camunda\Orchestration\Api\Model\MessageCorrelationResult
```

Correlate message

Publishes a message and correlates it to a subscription. If correlation is successful it will return the first process instance key the message correlated with. The message is not buffered. Use the publish message endpoint to send messages that can be buffered.

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


$apiInstance = new Camunda\Orchestration\Api\Api\MessageApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$messageCorrelationRequest = new \Camunda\Orchestration\Api\Model\MessageCorrelationRequest(); // \Camunda\Orchestration\Api\Model\MessageCorrelationRequest

try {
    $result = $apiInstance->correlateMessage($messageCorrelationRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling MessageApi->correlateMessage: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **messageCorrelationRequest** | [**\Camunda\Orchestration\Api\Model\MessageCorrelationRequest**](../Model/MessageCorrelationRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\MessageCorrelationResult**](../Model/MessageCorrelationResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `publishMessage()`

```php
publishMessage($messagePublicationRequest): \Camunda\Orchestration\Api\Model\MessagePublicationResult
```

Publish message

Publishes a single message. Messages are published to specific partitions computed from their correlation keys. Messages can be buffered. The endpoint does not wait for a correlation result. Use the message correlation endpoint for such use cases.

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


$apiInstance = new Camunda\Orchestration\Api\Api\MessageApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$messagePublicationRequest = new \Camunda\Orchestration\Api\Model\MessagePublicationRequest(); // \Camunda\Orchestration\Api\Model\MessagePublicationRequest

try {
    $result = $apiInstance->publishMessage($messagePublicationRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling MessageApi->publishMessage: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **messagePublicationRequest** | [**\Camunda\Orchestration\Api\Model\MessagePublicationRequest**](../Model/MessagePublicationRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\MessagePublicationResult**](../Model/MessagePublicationResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
