# Camunda\Orchestration\Api\ClockApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**addClock()**](ClockApi.md#addClock) | **POST** /clock/add | Add time to internal clock |
| [**getClock()**](ClockApi.md#getClock) | **GET** /clock | Get internal clock |
| [**pinClock()**](ClockApi.md#pinClock) | **PUT** /clock | Pin internal clock (alpha) |
| [**resetClock()**](ClockApi.md#resetClock) | **POST** /clock/reset | Reset internal clock (alpha) |


## `addClock()`

```php
addClock($clockAddRequest): \Camunda\Orchestration\Api\Model\ClockResult
```

Add time to internal clock

Moves the Zeebe engine's internal clock forward or backward by the given offset. Unlike pinning, the clock keeps ticking in real time after the offset is added. If the clock is pinned, it stays pinned and moves to the pinned time plus the offset.  Each request adds to the time of the clock, so adding `PT1H` twice moves the clock two hours ahead. Use `POST /v2/clock/reset` to remove all added time and return the clock to the system time.  The response contains the new time of the clock. The change reaches all partitions shortly after it is accepted, so a request sent right after it may still use the previous time.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ClockApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$clockAddRequest = new \Camunda\Orchestration\Api\Model\ClockAddRequest(); // \Camunda\Orchestration\Api\Model\ClockAddRequest

try {
    $result = $apiInstance->addClock($clockAddRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ClockApi->addClock: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **clockAddRequest** | [**\Camunda\Orchestration\Api\Model\ClockAddRequest**](../Model/ClockAddRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\ClockResult**](../Model/ClockResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getClock()`

```php
getClock(): \Camunda\Orchestration\Api\Model\ClockResult
```

Get internal clock

Returns the current time of the Zeebe engine's internal clock. If the clock is pinned, this is the pinned time. Otherwise, the clock ticks in real time and this is the engine's current time.  Use this endpoint to check the time that the engine uses, for example after pinning the clock with `PUT /v2/clock`, adding time with `POST /v2/clock/add`, or resetting it with `POST /v2/clock/reset`. A pin, add, or reset reaches all partitions shortly after it is accepted, so a request sent right after it may still return the previous time.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ClockApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->getClock();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ClockApi->getClock: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\Camunda\Orchestration\Api\Model\ClockResult**](../Model/ClockResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `pinClock()`

```php
pinClock($clockPinRequest)
```

Pin internal clock (alpha)

Set a precise, static time for the Zeebe engine's internal clock. When the clock is pinned, it remains at the specified time and does not advance. To change the time, the clock must be pinned again with a new timestamp. To move the clock without stopping it, use `POST /v2/clock/add` instead. Use `GET /v2/clock` to read the current time of the clock.  This endpoint is an alpha feature and may be subject to change in future releases.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ClockApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$clockPinRequest = new \Camunda\Orchestration\Api\Model\ClockPinRequest(); // \Camunda\Orchestration\Api\Model\ClockPinRequest

try {
    $apiInstance->pinClock($clockPinRequest);
} catch (Exception $e) {
    echo 'Exception when calling ClockApi->pinClock: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **clockPinRequest** | [**\Camunda\Orchestration\Api\Model\ClockPinRequest**](../Model/ClockPinRequest.md)|  | |

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

## `resetClock()`

```php
resetClock()
```

Reset internal clock (alpha)

Resets the Zeebe engine's internal clock to the current system time, enabling it to tick in real-time. This operation is useful for returning the clock to normal behavior after it has been pinned to a specific time or moved with `POST /v2/clock/add`. Use `GET /v2/clock` to read the current time of the clock.  This endpoint is an alpha feature and may be subject to change in future releases.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ClockApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $apiInstance->resetClock();
} catch (Exception $e) {
    echo 'Exception when calling ClockApi->resetClock: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

void (empty response body)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
