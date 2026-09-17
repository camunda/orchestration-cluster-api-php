# Camunda\Orchestration\Api\SecretApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**listSecrets()**](SecretApi.md#listSecrets) | **POST** /secrets/list | List secrets (alpha) |
| [**resolveSecrets()**](SecretApi.md#resolveSecrets) | **POST** /secrets/resolve | Resolve secrets (alpha) |


## `listSecrets()`

```php
listSecrets($body): \Camunda\Orchestration\Api\Model\SecretListResult
```

List secrets (alpha)

List the `camunda.secrets.*` references known for the caller's physical tenant.  Only references the caller holds `SECRET:READ` on are returned. This endpoint never returns secret values, only the reference names.  The references are read from the secret stores configured for the caller's physical tenant. A store may hold names outside the reference name charset (for example one containing a dot); those are omitted, since `/secrets/resolve` would reject them and no permission can be granted on them.  A returned reference is usable verbatim with `/secrets/resolve`. In a FEEL expression, however, a name that is not a bare identifier has to be backtick-escaped, since FEEL reads a bare dash as the minus operator: a listed `camunda.secrets.db-password` is written `` =camunda.secrets.`db-password` `` in a BPMN input mapping.  This endpoint is an alpha feature and may be subject to change in future releases.

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


$apiInstance = new Camunda\Orchestration\Api\Api\SecretApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = array('key' => new \stdClass); // object

try {
    $result = $apiInstance->listSecrets($body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SecretApi->listSecrets: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **body** | **object**|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\SecretListResult**](../Model/SecretListResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `resolveSecrets()`

```php
resolveSecrets($secretResolveRequest): \Camunda\Orchestration\Api\Model\SecretResolveResult
```

Resolve secrets (alpha)

Resolve a deduplicated batch of `camunda.secrets.*` references for the caller's physical tenant in a single round-trip.  Each reference is authorized and resolved independently. For valid requests, the endpoint always responds with HTTP 200: successfully resolved references are returned in `resolved`, while references that could not be resolved (for example not found, malformed or over-long, or the caller lacks `SECRET:REVEAL` on that reference) are returned in `errors`. A failure of one reference never fails the others. Only structurally invalid requests are rejected with HTTP 400: a missing or non-array `references` field, more than 20 references, or a null entry.  References are resolved against the secret stores configured for the caller's physical tenant, served from the gateway's secret cache when the value is already cached and read from the store otherwise.  This endpoint is an alpha feature and may be subject to change in future releases.

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


$apiInstance = new Camunda\Orchestration\Api\Api\SecretApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$secretResolveRequest = new \Camunda\Orchestration\Api\Model\SecretResolveRequest(); // \Camunda\Orchestration\Api\Model\SecretResolveRequest

try {
    $result = $apiInstance->resolveSecrets($secretResolveRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SecretApi->resolveSecrets: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **secretResolveRequest** | [**\Camunda\Orchestration\Api\Model\SecretResolveRequest**](../Model/SecretResolveRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\SecretResolveResult**](../Model/SecretResolveResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
