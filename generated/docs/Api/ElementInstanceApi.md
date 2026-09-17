# Camunda\Orchestration\Api\ElementInstanceApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**createElementInstanceVariables()**](ElementInstanceApi.md#createElementInstanceVariables) | **PUT** /element-instances/{elementInstanceKey}/variables | Update element instance variables |
| [**getElementInstance()**](ElementInstanceApi.md#getElementInstance) | **GET** /element-instances/{elementInstanceKey} | Get element instance |
| [**searchElementInstanceIncidents()**](ElementInstanceApi.md#searchElementInstanceIncidents) | **POST** /element-instances/{elementInstanceKey}/incidents/search | Search for incidents of a specific element instance |
| [**searchElementInstanceWaitStates()**](ElementInstanceApi.md#searchElementInstanceWaitStates) | **POST** /element-instances/wait-states/search | Search element instance wait states |
| [**searchElementInstances()**](ElementInstanceApi.md#searchElementInstances) | **POST** /element-instances/search | Search element instances |


## `createElementInstanceVariables()`

```php
createElementInstanceVariables($elementInstanceKey, $setVariableRequest)
```

Update element instance variables

Updates all the variables of a particular scope (for example, process instance, element instance) with the given variable data. Specify the element instance in the `elementInstanceKey` parameter. Variable updates can be delayed by listener-related processing; if processing exceeds the request timeout, this endpoint can return 504. Other gateway timeout causes are also possible. Retry with backoff and inspect listener worker availability and logs when this repeats.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ElementInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$elementInstanceKey = 'elementInstanceKey_example'; // string | The key of the element instance to update the variables for. This can be the process instance key (as obtained during instance creation), or a given element, such as a service task (see the `elementInstanceKey` on the job message).
$setVariableRequest = new \Camunda\Orchestration\Api\Model\SetVariableRequest(); // \Camunda\Orchestration\Api\Model\SetVariableRequest

try {
    $apiInstance->createElementInstanceVariables($elementInstanceKey, $setVariableRequest);
} catch (Exception $e) {
    echo 'Exception when calling ElementInstanceApi->createElementInstanceVariables: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **elementInstanceKey** | **string**| The key of the element instance to update the variables for. This can be the process instance key (as obtained during instance creation), or a given element, such as a service task (see the &#x60;elementInstanceKey&#x60; on the job message). | |
| **setVariableRequest** | [**\Camunda\Orchestration\Api\Model\SetVariableRequest**](../Model/SetVariableRequest.md)|  | |

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

## `getElementInstance()`

```php
getElementInstance($elementInstanceKey): \Camunda\Orchestration\Api\Model\ElementInstanceResult
```

Get element instance

Returns element instance as JSON.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ElementInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$elementInstanceKey = 'elementInstanceKey_example'; // string | The assigned key of the element instance, which acts as a unique identifier for this element instance.

try {
    $result = $apiInstance->getElementInstance($elementInstanceKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ElementInstanceApi->getElementInstance: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **elementInstanceKey** | **string**| The assigned key of the element instance, which acts as a unique identifier for this element instance. | |

### Return type

[**\Camunda\Orchestration\Api\Model\ElementInstanceResult**](../Model/ElementInstanceResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchElementInstanceIncidents()`

```php
searchElementInstanceIncidents($elementInstanceKey, $incidentSearchQuery): \Camunda\Orchestration\Api\Model\IncidentSearchQueryResult
```

Search for incidents of a specific element instance

Search for incidents caused by the specified element instance, including incidents of any child instances created from this element instance.  Although the `elementInstanceKey` is provided as a path parameter to indicate the root element instance, you may also include an `elementInstanceKey` within the filter object to narrow results to specific child element instances. This is useful, for example, if you want to isolate incidents associated with nested or subordinate elements within the given element instance while excluding incidents directly tied to the root element itself.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ElementInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$elementInstanceKey = 'elementInstanceKey_example'; // string | The unique key of the element instance to search incidents for.
$incidentSearchQuery = new \Camunda\Orchestration\Api\Model\IncidentSearchQuery(); // \Camunda\Orchestration\Api\Model\IncidentSearchQuery

try {
    $result = $apiInstance->searchElementInstanceIncidents($elementInstanceKey, $incidentSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ElementInstanceApi->searchElementInstanceIncidents: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **elementInstanceKey** | **string**| The unique key of the element instance to search incidents for. | |
| **incidentSearchQuery** | [**\Camunda\Orchestration\Api\Model\IncidentSearchQuery**](../Model/IncidentSearchQuery.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\IncidentSearchQueryResult**](../Model/IncidentSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchElementInstanceWaitStates()`

```php
searchElementInstanceWaitStates($elementInstanceWaitStateQuery): \Camunda\Orchestration\Api\Model\ElementInstanceWaitStateQueryResult
```

Search element instance wait states

Returns the wait states for element instances matching the given filter.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ElementInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$elementInstanceWaitStateQuery = new \Camunda\Orchestration\Api\Model\ElementInstanceWaitStateQuery(); // \Camunda\Orchestration\Api\Model\ElementInstanceWaitStateQuery

try {
    $result = $apiInstance->searchElementInstanceWaitStates($elementInstanceWaitStateQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ElementInstanceApi->searchElementInstanceWaitStates: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **elementInstanceWaitStateQuery** | [**\Camunda\Orchestration\Api\Model\ElementInstanceWaitStateQuery**](../Model/ElementInstanceWaitStateQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ElementInstanceWaitStateQueryResult**](../Model/ElementInstanceWaitStateQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchElementInstances()`

```php
searchElementInstances($elementInstanceSearchQuery): \Camunda\Orchestration\Api\Model\ElementInstanceSearchQueryResult
```

Search element instances

Search for element instances based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ElementInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$elementInstanceSearchQuery = new \Camunda\Orchestration\Api\Model\ElementInstanceSearchQuery(); // \Camunda\Orchestration\Api\Model\ElementInstanceSearchQuery

try {
    $result = $apiInstance->searchElementInstances($elementInstanceSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ElementInstanceApi->searchElementInstances: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **elementInstanceSearchQuery** | [**\Camunda\Orchestration\Api\Model\ElementInstanceSearchQuery**](../Model/ElementInstanceSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ElementInstanceSearchQueryResult**](../Model/ElementInstanceSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
