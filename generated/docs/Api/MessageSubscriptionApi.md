# Camunda\Orchestration\Api\MessageSubscriptionApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**searchCorrelatedMessageSubscriptions()**](MessageSubscriptionApi.md#searchCorrelatedMessageSubscriptions) | **POST** /correlated-message-subscriptions/search | Search correlated message subscriptions |
| [**searchMessageSubscriptions()**](MessageSubscriptionApi.md#searchMessageSubscriptions) | **POST** /message-subscriptions/search | Search message subscriptions |


## `searchCorrelatedMessageSubscriptions()`

```php
searchCorrelatedMessageSubscriptions($correlatedMessageSubscriptionSearchQuery): \Camunda\Orchestration\Api\Model\CorrelatedMessageSubscriptionSearchQueryResult
```

Search correlated message subscriptions

Search correlated message subscriptions based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\MessageSubscriptionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$correlatedMessageSubscriptionSearchQuery = new \Camunda\Orchestration\Api\Model\CorrelatedMessageSubscriptionSearchQuery(); // \Camunda\Orchestration\Api\Model\CorrelatedMessageSubscriptionSearchQuery

try {
    $result = $apiInstance->searchCorrelatedMessageSubscriptions($correlatedMessageSubscriptionSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling MessageSubscriptionApi->searchCorrelatedMessageSubscriptions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **correlatedMessageSubscriptionSearchQuery** | [**\Camunda\Orchestration\Api\Model\CorrelatedMessageSubscriptionSearchQuery**](../Model/CorrelatedMessageSubscriptionSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\CorrelatedMessageSubscriptionSearchQueryResult**](../Model/CorrelatedMessageSubscriptionSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchMessageSubscriptions()`

```php
searchMessageSubscriptions($messageSubscriptionSearchQuery): \Camunda\Orchestration\Api\Model\MessageSubscriptionSearchQueryResult
```

Search message subscriptions

Search for message subscriptions based on given criteria.  By default, both start and intermediate event subscriptions are returned. Use the `messageSubscriptionType` filter to restrict results to a single type.  **Version notes:** - Start event subscriptions are only captured for deployments made with 8.10 or later. - The `messageSubscriptionType` field is only populated for data created   with Camunda 8.10 or later. For pre-8.10 data, intermediate event entries have no   `messageSubscriptionType` value stored. For convenience, the API returns `PROCESS_EVENT`   as a default for such search results, though. - Searching for intermediate event subscriptions **including legacy data** can be achieved   by filtering for `messageSubscriptionType` not matching `START_EVENT`.

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


$apiInstance = new Camunda\Orchestration\Api\Api\MessageSubscriptionApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$messageSubscriptionSearchQuery = new \Camunda\Orchestration\Api\Model\MessageSubscriptionSearchQuery(); // \Camunda\Orchestration\Api\Model\MessageSubscriptionSearchQuery

try {
    $result = $apiInstance->searchMessageSubscriptions($messageSubscriptionSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling MessageSubscriptionApi->searchMessageSubscriptions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **messageSubscriptionSearchQuery** | [**\Camunda\Orchestration\Api\Model\MessageSubscriptionSearchQuery**](../Model/MessageSubscriptionSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\MessageSubscriptionSearchQueryResult**](../Model/MessageSubscriptionSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
