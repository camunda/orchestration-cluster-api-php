# Camunda\Orchestration\Api\ResourceApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**createDeployment()**](ResourceApi.md#createDeployment) | **POST** /deployments | Deploy resources |
| [**deleteResource()**](ResourceApi.md#deleteResource) | **POST** /resources/{resourceKey}/deletion | Delete resource |
| [**getResource()**](ResourceApi.md#getResource) | **GET** /resources/{resourceKey} | Get resource |
| [**getResourceContent()**](ResourceApi.md#getResourceContent) | **GET** /resources/{resourceKey}/content | Get RPA resource content (deprecated) |
| [**getResourceContentBinary()**](ResourceApi.md#getResourceContentBinary) | **GET** /resources/{resourceKey}/content/binary | Get resource content as binary |
| [**searchResources()**](ResourceApi.md#searchResources) | **POST** /resources/search | Search resources |


## `createDeployment()`

```php
createDeployment($resources, $tenantId): \Camunda\Orchestration\Api\Model\DeploymentResult
```

Deploy resources

Deploys one or more resources, including BPMN processes, DMN decision models, forms, RPA resources, and generic files. A deployment can contain any file type. Files that are not interpreted as BPMN, DMN, form, or RPA resources are stored as deployable generic resources in the engine. This is an atomic call, i.e. either all resources are deployed or none of them are.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ResourceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$resources = array('/path/to/file.txt'); // \SplFileObject[] | The binary data to create the deployment resources. It is possible to have more than one form part with different form part names for the binary data to create a deployment.
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.

try {
    $result = $apiInstance->createDeployment($resources, $tenantId);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ResourceApi->createDeployment: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **resources** | **\SplFileObject[]**| The binary data to create the deployment resources. It is possible to have more than one form part with different form part names for the binary data to create a deployment. | |
| **tenantId** | **string**| The unique identifier of the tenant. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\DeploymentResult**](../Model/DeploymentResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `multipart/form-data`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `deleteResource()`

```php
deleteResource($resourceKey, $deleteResourceRequest): \Camunda\Orchestration\Api\Model\DeleteResourceResponse
```

Delete resource

Deletes a deployed resource. This can be a process definition, decision requirements definition, or form definition deployed using the deploy resources endpoint. Specify the resource you want to delete in the `resourceKey` parameter.  Once a resource has been deleted it cannot be recovered. If the resource needs to be available again, a new deployment of the resource is required.  By default, only the resource itself is deleted from the runtime state. To also delete the historic data associated with a resource, set the `deleteHistory` flag in the request body to `true`. History deletion is supported for process definitions and decision requirements definitions; for other resource types (forms, generic resources) the flag is ignored and no history is deleted.  The two supported types differ in how the history is removed. For a decision requirements definition the history is deleted asynchronously via a batch operation whose details are returned in the `batchOperation` field of the response. For a process definition that still exists in the runtime state, the definition first drains its running instances and its history is deleted asynchronously once the definition is fully removed cluster-wide; no batch operation is returned in the response. If the process definition has already been removed from the runtime state and the deletion is later re-triggered with `deleteHistory` set to `true`, a batch operation is created immediately and returned in the `batchOperation` field.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ResourceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$resourceKey = 'resourceKey_example'; // \Camunda\Orchestration\Api\Model\ResourceKey | The key of the resource to delete. This can be the key of a process definition, the key of a decision requirements definition or the key of a form definition
$deleteResourceRequest = new \Camunda\Orchestration\Api\Model\DeleteResourceRequest(); // \Camunda\Orchestration\Api\Model\DeleteResourceRequest

try {
    $result = $apiInstance->deleteResource($resourceKey, $deleteResourceRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ResourceApi->deleteResource: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **resourceKey** | **\Camunda\Orchestration\Api\Model\ResourceKey**| The key of the resource to delete. This can be the key of a process definition, the key of a decision requirements definition or the key of a form definition | |
| **deleteResourceRequest** | [**\Camunda\Orchestration\Api\Model\DeleteResourceRequest**](../Model/DeleteResourceRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\DeleteResourceResponse**](../Model/DeleteResourceResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getResource()`

```php
getResource($resourceKey): \Camunda\Orchestration\Api\Model\ResourceResult
```

Get resource

Returns a deployed resource. :::info This endpoint does not return BPMN process definitions, DMN decision definitions, or form resources. To query BPMN process definitions or DMN decision definitions, use their respective APIs. :::

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


$apiInstance = new Camunda\Orchestration\Api\Api\ResourceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$resourceKey = 'resourceKey_example'; // \Camunda\Orchestration\Api\Model\ResourceKey | The unique key identifying the resource.

try {
    $result = $apiInstance->getResource($resourceKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ResourceApi->getResource: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **resourceKey** | **\Camunda\Orchestration\Api\Model\ResourceKey**| The unique key identifying the resource. | |

### Return type

[**\Camunda\Orchestration\Api\Model\ResourceResult**](../Model/ResourceResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getResourceContent()`

```php
getResourceContent($resourceKey): array<string,mixed>
```

Get RPA resource content (deprecated)

**Deprecated** — use `/resources/{resourceKey}/content/binary` instead, which supports all resource types and returns content as binary (octet-stream).  Returns the content of a deployed RPA resource as JSON. :::info This endpoint only supports RPA resources. For generic resource content in binary format, use the `/resources/{resourceKey}/content/binary` endpoint. :::

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


$apiInstance = new Camunda\Orchestration\Api\Api\ResourceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$resourceKey = 'resourceKey_example'; // \Camunda\Orchestration\Api\Model\ResourceKey | The unique key identifying the RPA resource.

try {
    $result = $apiInstance->getResourceContent($resourceKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ResourceApi->getResourceContent: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **resourceKey** | **\Camunda\Orchestration\Api\Model\ResourceKey**| The unique key identifying the RPA resource. | |

### Return type

**array<string,mixed>**

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getResourceContentBinary()`

```php
getResourceContentBinary($resourceKey): \SplFileObject
```

Get resource content as binary

Returns the content of a deployed resource in binary format (octet-stream). :::info This endpoint does not return BPMN process definitions, DMN decision definitions, or form resources. To query BPMN process definitions or DMN decision definitions, use their respective APIs. :::

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


$apiInstance = new Camunda\Orchestration\Api\Api\ResourceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$resourceKey = 'resourceKey_example'; // \Camunda\Orchestration\Api\Model\ResourceKey | The unique key identifying the resource.

try {
    $result = $apiInstance->getResourceContentBinary($resourceKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ResourceApi->getResourceContentBinary: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **resourceKey** | **\Camunda\Orchestration\Api\Model\ResourceKey**| The unique key identifying the resource. | |

### Return type

**\SplFileObject**

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/octet-stream`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchResources()`

```php
searchResources($resourceSearchQuery): \Camunda\Orchestration\Api\Model\ResourceSearchQueryResult
```

Search resources

Search for deployed resources based on given criteria. :::info This endpoint does not return BPMN process definitions, DMN decision definitions, or form resources. To query BPMN process definitions or DMN decision definitions, use their respective search APIs. :::

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


$apiInstance = new Camunda\Orchestration\Api\Api\ResourceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$resourceSearchQuery = new \Camunda\Orchestration\Api\Model\ResourceSearchQuery(); // \Camunda\Orchestration\Api\Model\ResourceSearchQuery

try {
    $result = $apiInstance->searchResources($resourceSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ResourceApi->searchResources: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **resourceSearchQuery** | [**\Camunda\Orchestration\Api\Model\ResourceSearchQuery**](../Model/ResourceSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ResourceSearchQueryResult**](../Model/ResourceSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
