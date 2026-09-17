# Camunda\Orchestration\Api\AdHocSubProcessApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**activateAdHocSubProcessActivities()**](AdHocSubProcessApi.md#activateAdHocSubProcessActivities) | **POST** /element-instances/ad-hoc-activities/{adHocSubProcessInstanceKey}/activation | Activate activities within an ad-hoc sub-process |


## `activateAdHocSubProcessActivities()`

```php
activateAdHocSubProcessActivities($adHocSubProcessInstanceKey, $adHocSubProcessActivateActivitiesInstruction)
```

Activate activities within an ad-hoc sub-process

Activates selected activities within an ad-hoc sub-process identified by element ID. The provided element IDs must exist within the ad-hoc sub-process instance identified by the provided adHocSubProcessInstanceKey.

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


$apiInstance = new Camunda\Orchestration\Api\Api\AdHocSubProcessApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$adHocSubProcessInstanceKey = 'adHocSubProcessInstanceKey_example'; // string | The key of the ad-hoc sub-process instance that contains the activities.
$adHocSubProcessActivateActivitiesInstruction = new \Camunda\Orchestration\Api\Model\AdHocSubProcessActivateActivitiesInstruction(); // \Camunda\Orchestration\Api\Model\AdHocSubProcessActivateActivitiesInstruction

try {
    $apiInstance->activateAdHocSubProcessActivities($adHocSubProcessInstanceKey, $adHocSubProcessActivateActivitiesInstruction);
} catch (Exception $e) {
    echo 'Exception when calling AdHocSubProcessApi->activateAdHocSubProcessActivities: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **adHocSubProcessInstanceKey** | **string**| The key of the ad-hoc sub-process instance that contains the activities. | |
| **adHocSubProcessActivateActivitiesInstruction** | [**\Camunda\Orchestration\Api\Model\AdHocSubProcessActivateActivitiesInstruction**](../Model/AdHocSubProcessActivateActivitiesInstruction.md)|  | |

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
