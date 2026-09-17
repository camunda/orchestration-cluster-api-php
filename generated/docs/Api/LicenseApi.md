# Camunda\Orchestration\Api\LicenseApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getLicense()**](LicenseApi.md#getLicense) | **GET** /license | Get license status |


## `getLicense()`

```php
getLicense(): \Camunda\Orchestration\Api\Model\LicenseResponse
```

Get license status

Obtains the status of the current Camunda license.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new Camunda\Orchestration\Api\Api\LicenseApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->getLicense();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling LicenseApi->getLicense: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\Camunda\Orchestration\Api\Model\LicenseResponse**](../Model/LicenseResponse.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
