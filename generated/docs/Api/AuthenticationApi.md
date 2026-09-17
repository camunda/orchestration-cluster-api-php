# Camunda\Orchestration\Api\AuthenticationApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getAuthentication()**](AuthenticationApi.md#getAuthentication) | **GET** /authentication/me | Get current user |
| [**searchOwnAuthorizations()**](AuthenticationApi.md#searchOwnAuthorizations) | **POST** /authentication/me/authorizations/search | Search own authorizations |


## `getAuthentication()`

```php
getAuthentication(): \Camunda\Orchestration\Api\Model\CamundaUserResult
```

Get current user

Retrieves the current authenticated user.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearerAuth
$config = Camunda\Orchestration\Api\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new Camunda\Orchestration\Api\Api\AuthenticationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->getAuthentication();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AuthenticationApi->getAuthentication: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\Camunda\Orchestration\Api\Model\CamundaUserResult**](../Model/CamundaUserResult.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchOwnAuthorizations()`

```php
searchOwnAuthorizations($authorizationSearchQuery): \Camunda\Orchestration\Api\Model\OwnAuthorizationSearchResult
```

Search own authorizations

Search for the current authenticated principal's own authorization records — including authorizations granted directly to the user or client, as well as those granted via a group, role, or mapping rule the principal belongs to.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearerAuth
$config = Camunda\Orchestration\Api\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new Camunda\Orchestration\Api\Api\AuthenticationApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$authorizationSearchQuery = new \Camunda\Orchestration\Api\Model\AuthorizationSearchQuery(); // \Camunda\Orchestration\Api\Model\AuthorizationSearchQuery

try {
    $result = $apiInstance->searchOwnAuthorizations($authorizationSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AuthenticationApi->searchOwnAuthorizations: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **authorizationSearchQuery** | [**\Camunda\Orchestration\Api\Model\AuthorizationSearchQuery**](../Model/AuthorizationSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\OwnAuthorizationSearchResult**](../Model/OwnAuthorizationSearchResult.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
