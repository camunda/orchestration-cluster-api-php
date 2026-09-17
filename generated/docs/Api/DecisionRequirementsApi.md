# Camunda\Orchestration\Api\DecisionRequirementsApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getDecisionRequirements()**](DecisionRequirementsApi.md#getDecisionRequirements) | **GET** /decision-requirements/{decisionRequirementsKey} | Get decision requirements |
| [**getDecisionRequirementsXML()**](DecisionRequirementsApi.md#getDecisionRequirementsXML) | **GET** /decision-requirements/{decisionRequirementsKey}/xml | Get decision requirements XML |
| [**searchDecisionRequirements()**](DecisionRequirementsApi.md#searchDecisionRequirements) | **POST** /decision-requirements/search | Search decision requirements |


## `getDecisionRequirements()`

```php
getDecisionRequirements($decisionRequirementsKey): \Camunda\Orchestration\Api\Model\DecisionRequirementsResult
```

Get decision requirements

Returns Decision Requirements as JSON.

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


$apiInstance = new Camunda\Orchestration\Api\Api\DecisionRequirementsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$decisionRequirementsKey = 'decisionRequirementsKey_example'; // string | The assigned key of the decision requirements, which acts as a unique identifier for this decision requirements.

try {
    $result = $apiInstance->getDecisionRequirements($decisionRequirementsKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DecisionRequirementsApi->getDecisionRequirements: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **decisionRequirementsKey** | **string**| The assigned key of the decision requirements, which acts as a unique identifier for this decision requirements. | |

### Return type

[**\Camunda\Orchestration\Api\Model\DecisionRequirementsResult**](../Model/DecisionRequirementsResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getDecisionRequirementsXML()`

```php
getDecisionRequirementsXML($decisionRequirementsKey): string
```

Get decision requirements XML

Returns decision requirements as XML.

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


$apiInstance = new Camunda\Orchestration\Api\Api\DecisionRequirementsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$decisionRequirementsKey = 'decisionRequirementsKey_example'; // string | The assigned key of the decision requirements, which acts as a unique identifier for this decision.

try {
    $result = $apiInstance->getDecisionRequirementsXML($decisionRequirementsKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DecisionRequirementsApi->getDecisionRequirementsXML: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **decisionRequirementsKey** | **string**| The assigned key of the decision requirements, which acts as a unique identifier for this decision. | |

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

## `searchDecisionRequirements()`

```php
searchDecisionRequirements($decisionRequirementsSearchQuery): \Camunda\Orchestration\Api\Model\DecisionRequirementsSearchQueryResult
```

Search decision requirements

Search for decision requirements based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\DecisionRequirementsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$decisionRequirementsSearchQuery = new \Camunda\Orchestration\Api\Model\DecisionRequirementsSearchQuery(); // \Camunda\Orchestration\Api\Model\DecisionRequirementsSearchQuery

try {
    $result = $apiInstance->searchDecisionRequirements($decisionRequirementsSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DecisionRequirementsApi->searchDecisionRequirements: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **decisionRequirementsSearchQuery** | [**\Camunda\Orchestration\Api\Model\DecisionRequirementsSearchQuery**](../Model/DecisionRequirementsSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\DecisionRequirementsSearchQueryResult**](../Model/DecisionRequirementsSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
