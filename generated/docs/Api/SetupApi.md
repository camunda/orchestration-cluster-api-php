# Camunda\Orchestration\Api\SetupApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**createAdminUser()**](SetupApi.md#createAdminUser) | **POST** /setup/user | Create admin user |


## `createAdminUser()`

```php
createAdminUser($userRequest): \Camunda\Orchestration\Api\Model\UserCreateResult
```

Create admin user

Creates a new user and assigns the admin role to it. This endpoint is only usable when users are managed in the Orchestration Cluster and while no user is assigned to the admin role.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new Camunda\Orchestration\Api\Api\SetupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$userRequest = new \Camunda\Orchestration\Api\Model\UserRequest(); // \Camunda\Orchestration\Api\Model\UserRequest

try {
    $result = $apiInstance->createAdminUser($userRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SetupApi->createAdminUser: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **userRequest** | [**\Camunda\Orchestration\Api\Model\UserRequest**](../Model/UserRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\UserCreateResult**](../Model/UserCreateResult.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
