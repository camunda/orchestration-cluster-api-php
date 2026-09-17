# Camunda\Orchestration\Api\SignalApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**broadcastSignal()**](SignalApi.md#broadcastSignal) | **POST** /signals/broadcast | Broadcast signal |


## `broadcastSignal()`

```php
broadcastSignal($signalBroadcastRequest): \Camunda\Orchestration\Api\Model\SignalBroadcastResult
```

Broadcast signal

Broadcasts a signal.

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


$apiInstance = new Camunda\Orchestration\Api\Api\SignalApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$signalBroadcastRequest = new \Camunda\Orchestration\Api\Model\SignalBroadcastRequest(); // \Camunda\Orchestration\Api\Model\SignalBroadcastRequest

try {
    $result = $apiInstance->broadcastSignal($signalBroadcastRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SignalApi->broadcastSignal: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **signalBroadcastRequest** | [**\Camunda\Orchestration\Api\Model\SignalBroadcastRequest**](../Model/SignalBroadcastRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\SignalBroadcastResult**](../Model/SignalBroadcastResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
