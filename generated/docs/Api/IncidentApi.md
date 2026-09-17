# Camunda\Orchestration\Api\IncidentApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getIncident()**](IncidentApi.md#getIncident) | **GET** /incidents/{incidentKey} | Get incident |
| [**getProcessInstanceStatisticsByDefinition()**](IncidentApi.md#getProcessInstanceStatisticsByDefinition) | **POST** /incidents/statistics/process-instances-by-definition | Get process instance statistics by definition |
| [**getProcessInstanceStatisticsByError()**](IncidentApi.md#getProcessInstanceStatisticsByError) | **POST** /incidents/statistics/process-instances-by-error | Get process instance statistics by error |
| [**resolveIncident()**](IncidentApi.md#resolveIncident) | **POST** /incidents/{incidentKey}/resolution | Resolve incident |
| [**searchIncidents()**](IncidentApi.md#searchIncidents) | **POST** /incidents/search | Search incidents |


## `getIncident()`

```php
getIncident($incidentKey): \Camunda\Orchestration\Api\Model\IncidentResult
```

Get incident

Returns incident as JSON.

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


$apiInstance = new Camunda\Orchestration\Api\Api\IncidentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$incidentKey = 'incidentKey_example'; // string | The assigned key of the incident, which acts as a unique identifier for this incident.

try {
    $result = $apiInstance->getIncident($incidentKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IncidentApi->getIncident: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **incidentKey** | **string**| The assigned key of the incident, which acts as a unique identifier for this incident. | |

### Return type

[**\Camunda\Orchestration\Api\Model\IncidentResult**](../Model/IncidentResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getProcessInstanceStatisticsByDefinition()`

```php
getProcessInstanceStatisticsByDefinition($incidentProcessInstanceStatisticsByDefinitionQuery): \Camunda\Orchestration\Api\Model\IncidentProcessInstanceStatisticsByDefinitionQueryResult
```

Get process instance statistics by definition

Returns statistics for active process instances with incidents, grouped by process definition. The result set is scoped to a specific incident error hash code, which must be provided as a filter in the request body.

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


$apiInstance = new Camunda\Orchestration\Api\Api\IncidentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$incidentProcessInstanceStatisticsByDefinitionQuery = new \Camunda\Orchestration\Api\Model\IncidentProcessInstanceStatisticsByDefinitionQuery(); // \Camunda\Orchestration\Api\Model\IncidentProcessInstanceStatisticsByDefinitionQuery

try {
    $result = $apiInstance->getProcessInstanceStatisticsByDefinition($incidentProcessInstanceStatisticsByDefinitionQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IncidentApi->getProcessInstanceStatisticsByDefinition: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **incidentProcessInstanceStatisticsByDefinitionQuery** | [**\Camunda\Orchestration\Api\Model\IncidentProcessInstanceStatisticsByDefinitionQuery**](../Model/IncidentProcessInstanceStatisticsByDefinitionQuery.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\IncidentProcessInstanceStatisticsByDefinitionQueryResult**](../Model/IncidentProcessInstanceStatisticsByDefinitionQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getProcessInstanceStatisticsByError()`

```php
getProcessInstanceStatisticsByError($incidentProcessInstanceStatisticsByErrorQuery): \Camunda\Orchestration\Api\Model\IncidentProcessInstanceStatisticsByErrorQueryResult
```

Get process instance statistics by error

Returns statistics for active process instances that currently have active incidents, grouped by incident error hash code.

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


$apiInstance = new Camunda\Orchestration\Api\Api\IncidentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$incidentProcessInstanceStatisticsByErrorQuery = new \Camunda\Orchestration\Api\Model\IncidentProcessInstanceStatisticsByErrorQuery(); // \Camunda\Orchestration\Api\Model\IncidentProcessInstanceStatisticsByErrorQuery

try {
    $result = $apiInstance->getProcessInstanceStatisticsByError($incidentProcessInstanceStatisticsByErrorQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IncidentApi->getProcessInstanceStatisticsByError: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **incidentProcessInstanceStatisticsByErrorQuery** | [**\Camunda\Orchestration\Api\Model\IncidentProcessInstanceStatisticsByErrorQuery**](../Model/IncidentProcessInstanceStatisticsByErrorQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\IncidentProcessInstanceStatisticsByErrorQueryResult**](../Model/IncidentProcessInstanceStatisticsByErrorQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `resolveIncident()`

```php
resolveIncident($incidentKey, $incidentResolutionRequest)
```

Resolve incident

Marks the incident as resolved; most likely a call to Update job will be necessary to reset the job's retries, followed by this call.

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


$apiInstance = new Camunda\Orchestration\Api\Api\IncidentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$incidentKey = 'incidentKey_example'; // string | Key of the incident to resolve.
$incidentResolutionRequest = new \Camunda\Orchestration\Api\Model\IncidentResolutionRequest(); // \Camunda\Orchestration\Api\Model\IncidentResolutionRequest

try {
    $apiInstance->resolveIncident($incidentKey, $incidentResolutionRequest);
} catch (Exception $e) {
    echo 'Exception when calling IncidentApi->resolveIncident: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **incidentKey** | **string**| Key of the incident to resolve. | |
| **incidentResolutionRequest** | [**\Camunda\Orchestration\Api\Model\IncidentResolutionRequest**](../Model/IncidentResolutionRequest.md)|  | [optional] |

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

## `searchIncidents()`

```php
searchIncidents($incidentSearchQuery): \Camunda\Orchestration\Api\Model\IncidentSearchQueryResult
```

Search incidents

Search for incidents based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\IncidentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$incidentSearchQuery = new \Camunda\Orchestration\Api\Model\IncidentSearchQuery(); // \Camunda\Orchestration\Api\Model\IncidentSearchQuery

try {
    $result = $apiInstance->searchIncidents($incidentSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IncidentApi->searchIncidents: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **incidentSearchQuery** | [**\Camunda\Orchestration\Api\Model\IncidentSearchQuery**](../Model/IncidentSearchQuery.md)|  | [optional] |

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
