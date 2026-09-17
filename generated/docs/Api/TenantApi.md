# Camunda\Orchestration\Api\TenantApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**assignClientToTenant()**](TenantApi.md#assignClientToTenant) | **PUT** /tenants/{tenantId}/clients/{clientId} | Assign a client to a tenant |
| [**assignGroupToTenant()**](TenantApi.md#assignGroupToTenant) | **PUT** /tenants/{tenantId}/groups/{groupId} | Assign a group to a tenant |
| [**assignMappingRuleToTenant()**](TenantApi.md#assignMappingRuleToTenant) | **PUT** /tenants/{tenantId}/mapping-rules/{mappingRuleId} | Assign a mapping rule to a tenant |
| [**assignRoleToTenant()**](TenantApi.md#assignRoleToTenant) | **PUT** /tenants/{tenantId}/roles/{roleId} | Assign a role to a tenant |
| [**assignUserToTenant()**](TenantApi.md#assignUserToTenant) | **PUT** /tenants/{tenantId}/users/{username} | Assign a user to a tenant |
| [**createTenant()**](TenantApi.md#createTenant) | **POST** /tenants | Create tenant |
| [**deleteTenant()**](TenantApi.md#deleteTenant) | **DELETE** /tenants/{tenantId} | Delete tenant |
| [**getTenant()**](TenantApi.md#getTenant) | **GET** /tenants/{tenantId} | Get tenant |
| [**searchClientsForTenant()**](TenantApi.md#searchClientsForTenant) | **POST** /tenants/{tenantId}/clients/search | Search clients for tenant |
| [**searchGroupIdsForTenant()**](TenantApi.md#searchGroupIdsForTenant) | **POST** /tenants/{tenantId}/groups/search | Search groups for tenant |
| [**searchMappingRulesForTenant()**](TenantApi.md#searchMappingRulesForTenant) | **POST** /tenants/{tenantId}/mapping-rules/search | Search mapping rules for tenant |
| [**searchRolesForTenant()**](TenantApi.md#searchRolesForTenant) | **POST** /tenants/{tenantId}/roles/search | Search roles for tenant |
| [**searchTenants()**](TenantApi.md#searchTenants) | **POST** /tenants/search | Search tenants |
| [**searchUsersForTenant()**](TenantApi.md#searchUsersForTenant) | **POST** /tenants/{tenantId}/users/search | Search users for tenant |
| [**unassignClientFromTenant()**](TenantApi.md#unassignClientFromTenant) | **DELETE** /tenants/{tenantId}/clients/{clientId} | Unassign a client from a tenant |
| [**unassignGroupFromTenant()**](TenantApi.md#unassignGroupFromTenant) | **DELETE** /tenants/{tenantId}/groups/{groupId} | Unassign a group from a tenant |
| [**unassignMappingRuleFromTenant()**](TenantApi.md#unassignMappingRuleFromTenant) | **DELETE** /tenants/{tenantId}/mapping-rules/{mappingRuleId} | Unassign a mapping rule from a tenant |
| [**unassignRoleFromTenant()**](TenantApi.md#unassignRoleFromTenant) | **DELETE** /tenants/{tenantId}/roles/{roleId} | Unassign a role from a tenant |
| [**unassignUserFromTenant()**](TenantApi.md#unassignUserFromTenant) | **DELETE** /tenants/{tenantId}/users/{username} | Unassign a user from a tenant |
| [**updateTenant()**](TenantApi.md#updateTenant) | **PUT** /tenants/{tenantId} | Update tenant |


## `assignClientToTenant()`

```php
assignClientToTenant($tenantId, $clientId)
```

Assign a client to a tenant

Assign the client to the specified tenant. The client can then access tenant data and perform authorized actions.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.
$clientId = 'clientId_example'; // string | The unique identifier of the application.

try {
    $apiInstance->assignClientToTenant($tenantId, $clientId);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->assignClientToTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The unique identifier of the tenant. | |
| **clientId** | **string**| The unique identifier of the application. | |

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

## `assignGroupToTenant()`

```php
assignGroupToTenant($tenantId, $groupId)
```

Assign a group to a tenant

Assigns a group to a specified tenant. Group members (users, clients) can then access tenant data and perform authorized actions.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.
$groupId = 'groupId_example'; // string | The unique identifier of the group.

try {
    $apiInstance->assignGroupToTenant($tenantId, $groupId);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->assignGroupToTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The unique identifier of the tenant. | |
| **groupId** | **string**| The unique identifier of the group. | |

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

## `assignMappingRuleToTenant()`

```php
assignMappingRuleToTenant($tenantId, $mappingRuleId)
```

Assign a mapping rule to a tenant

Assign a single mapping rule to a specified tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.
$mappingRuleId = 'mappingRuleId_example'; // string | The unique identifier of the mapping rule.

try {
    $apiInstance->assignMappingRuleToTenant($tenantId, $mappingRuleId);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->assignMappingRuleToTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The unique identifier of the tenant. | |
| **mappingRuleId** | **string**| The unique identifier of the mapping rule. | |

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

## `assignRoleToTenant()`

```php
assignRoleToTenant($tenantId, $roleId)
```

Assign a role to a tenant

Assigns a role to a specified tenant. Users, Clients or Groups, that have the role assigned, will get access to the tenant's data and can perform actions according to their authorizations.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.
$roleId = 'roleId_example'; // string | The unique identifier of the role.

try {
    $apiInstance->assignRoleToTenant($tenantId, $roleId);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->assignRoleToTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The unique identifier of the tenant. | |
| **roleId** | **string**| The unique identifier of the role. | |

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

## `assignUserToTenant()`

```php
assignUserToTenant($tenantId, $username)
```

Assign a user to a tenant

Assign a single user to a specified tenant. The user can then access tenant data and perform authorized actions.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.
$username = 'username_example'; // string | The unique identifier of the user.

try {
    $apiInstance->assignUserToTenant($tenantId, $username);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->assignUserToTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The unique identifier of the tenant. | |
| **username** | **string**| The unique identifier of the user. | |

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

## `createTenant()`

```php
createTenant($tenantCreateRequest): \Camunda\Orchestration\Api\Model\TenantCreateResult
```

Create tenant

Creates a new tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantCreateRequest = new \Camunda\Orchestration\Api\Model\TenantCreateRequest(); // \Camunda\Orchestration\Api\Model\TenantCreateRequest

try {
    $result = $apiInstance->createTenant($tenantCreateRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->createTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantCreateRequest** | [**\Camunda\Orchestration\Api\Model\TenantCreateRequest**](../Model/TenantCreateRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\TenantCreateResult**](../Model/TenantCreateResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `deleteTenant()`

```php
deleteTenant($tenantId)
```

Delete tenant

Deletes an existing tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.

try {
    $apiInstance->deleteTenant($tenantId);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->deleteTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The unique identifier of the tenant. | |

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

## `getTenant()`

```php
getTenant($tenantId): \Camunda\Orchestration\Api\Model\TenantResult
```

Get tenant

Retrieves a single tenant by tenant ID.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.

try {
    $result = $apiInstance->getTenant($tenantId);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->getTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The unique identifier of the tenant. | |

### Return type

[**\Camunda\Orchestration\Api\Model\TenantResult**](../Model/TenantResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchClientsForTenant()`

```php
searchClientsForTenant($tenantId, $tenantClientSearchQueryRequest): \Camunda\Orchestration\Api\Model\TenantClientSearchResult
```

Search clients for tenant

Retrieves a filtered and sorted list of clients for a specified tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.
$tenantClientSearchQueryRequest = new \Camunda\Orchestration\Api\Model\TenantClientSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\TenantClientSearchQueryRequest

try {
    $result = $apiInstance->searchClientsForTenant($tenantId, $tenantClientSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->searchClientsForTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The unique identifier of the tenant. | |
| **tenantClientSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\TenantClientSearchQueryRequest**](../Model/TenantClientSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\TenantClientSearchResult**](../Model/TenantClientSearchResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchGroupIdsForTenant()`

```php
searchGroupIdsForTenant($tenantId, $tenantGroupSearchQueryRequest): \Camunda\Orchestration\Api\Model\TenantGroupSearchResult
```

Search groups for tenant

Retrieves a filtered and sorted list of groups for a specified tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.
$tenantGroupSearchQueryRequest = new \Camunda\Orchestration\Api\Model\TenantGroupSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\TenantGroupSearchQueryRequest

try {
    $result = $apiInstance->searchGroupIdsForTenant($tenantId, $tenantGroupSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->searchGroupIdsForTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The unique identifier of the tenant. | |
| **tenantGroupSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\TenantGroupSearchQueryRequest**](../Model/TenantGroupSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\TenantGroupSearchResult**](../Model/TenantGroupSearchResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchMappingRulesForTenant()`

```php
searchMappingRulesForTenant($tenantId, $mappingRuleSearchQueryRequest): \Camunda\Orchestration\Api\Model\TenantMappingRuleSearchResult
```

Search mapping rules for tenant

Retrieves a filtered and sorted list of MappingRules for a specified tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.
$mappingRuleSearchQueryRequest = new \Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest

try {
    $result = $apiInstance->searchMappingRulesForTenant($tenantId, $mappingRuleSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->searchMappingRulesForTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The unique identifier of the tenant. | |
| **mappingRuleSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest**](../Model/MappingRuleSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\TenantMappingRuleSearchResult**](../Model/TenantMappingRuleSearchResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchRolesForTenant()`

```php
searchRolesForTenant($tenantId, $roleSearchQueryRequest): \Camunda\Orchestration\Api\Model\TenantRoleSearchResult
```

Search roles for tenant

Retrieves a filtered and sorted list of roles for a specified tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.
$roleSearchQueryRequest = new \Camunda\Orchestration\Api\Model\RoleSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\RoleSearchQueryRequest

try {
    $result = $apiInstance->searchRolesForTenant($tenantId, $roleSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->searchRolesForTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The unique identifier of the tenant. | |
| **roleSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\RoleSearchQueryRequest**](../Model/RoleSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\TenantRoleSearchResult**](../Model/TenantRoleSearchResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchTenants()`

```php
searchTenants($tenantSearchQueryRequest): \Camunda\Orchestration\Api\Model\TenantSearchQueryResult
```

Search tenants

Retrieves a filtered and sorted list of tenants.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantSearchQueryRequest = new \Camunda\Orchestration\Api\Model\TenantSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\TenantSearchQueryRequest

try {
    $result = $apiInstance->searchTenants($tenantSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->searchTenants: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\TenantSearchQueryRequest**](../Model/TenantSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\TenantSearchQueryResult**](../Model/TenantSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchUsersForTenant()`

```php
searchUsersForTenant($tenantId, $tenantUserSearchQueryRequest): \Camunda\Orchestration\Api\Model\TenantUserSearchResult
```

Search users for tenant

Retrieves a filtered and sorted list of users for a specified tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.
$tenantUserSearchQueryRequest = new \Camunda\Orchestration\Api\Model\TenantUserSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\TenantUserSearchQueryRequest

try {
    $result = $apiInstance->searchUsersForTenant($tenantId, $tenantUserSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->searchUsersForTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The unique identifier of the tenant. | |
| **tenantUserSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\TenantUserSearchQueryRequest**](../Model/TenantUserSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\TenantUserSearchResult**](../Model/TenantUserSearchResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `unassignClientFromTenant()`

```php
unassignClientFromTenant($tenantId, $clientId)
```

Unassign a client from a tenant

Unassigns the client from the specified tenant. The client can no longer access tenant data.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.
$clientId = 'clientId_example'; // string | The unique identifier of the application.

try {
    $apiInstance->unassignClientFromTenant($tenantId, $clientId);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->unassignClientFromTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The unique identifier of the tenant. | |
| **clientId** | **string**| The unique identifier of the application. | |

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

## `unassignGroupFromTenant()`

```php
unassignGroupFromTenant($tenantId, $groupId)
```

Unassign a group from a tenant

Unassigns a group from a specified tenant. Members of the group (users, clients) will no longer have access to the tenant's data - except they are assigned directly to the tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.
$groupId = 'groupId_example'; // string | The unique identifier of the group.

try {
    $apiInstance->unassignGroupFromTenant($tenantId, $groupId);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->unassignGroupFromTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The unique identifier of the tenant. | |
| **groupId** | **string**| The unique identifier of the group. | |

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

## `unassignMappingRuleFromTenant()`

```php
unassignMappingRuleFromTenant($tenantId, $mappingRuleId)
```

Unassign a mapping rule from a tenant

Unassigns a single mapping rule from a specified tenant without deleting the rule.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.
$mappingRuleId = 'mappingRuleId_example'; // string | The unique identifier of the mapping rule.

try {
    $apiInstance->unassignMappingRuleFromTenant($tenantId, $mappingRuleId);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->unassignMappingRuleFromTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The unique identifier of the tenant. | |
| **mappingRuleId** | **string**| The unique identifier of the mapping rule. | |

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

## `unassignRoleFromTenant()`

```php
unassignRoleFromTenant($tenantId, $roleId)
```

Unassign a role from a tenant

Unassigns a role from a specified tenant. Users, Clients or Groups, that have the role assigned, will no longer have access to the tenant's data - unless they are assigned directly to the tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.
$roleId = 'roleId_example'; // string | The unique identifier of the role.

try {
    $apiInstance->unassignRoleFromTenant($tenantId, $roleId);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->unassignRoleFromTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The unique identifier of the tenant. | |
| **roleId** | **string**| The unique identifier of the role. | |

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

## `unassignUserFromTenant()`

```php
unassignUserFromTenant($tenantId, $username)
```

Unassign a user from a tenant

Unassigns the user from the specified tenant. The user can no longer access tenant data.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.
$username = 'username_example'; // string | The unique identifier of the user.

try {
    $apiInstance->unassignUserFromTenant($tenantId, $username);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->unassignUserFromTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The unique identifier of the tenant. | |
| **username** | **string**| The unique identifier of the user. | |

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

## `updateTenant()`

```php
updateTenant($tenantId, $tenantUpdateRequest): \Camunda\Orchestration\Api\Model\TenantUpdateResult
```

Update tenant

Updates an existing tenant.

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


$apiInstance = new Camunda\Orchestration\Api\Api\TenantApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$tenantId = 'tenantId_example'; // string | The unique identifier of the tenant.
$tenantUpdateRequest = new \Camunda\Orchestration\Api\Model\TenantUpdateRequest(); // \Camunda\Orchestration\Api\Model\TenantUpdateRequest

try {
    $result = $apiInstance->updateTenant($tenantId, $tenantUpdateRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TenantApi->updateTenant: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **tenantId** | **string**| The unique identifier of the tenant. | |
| **tenantUpdateRequest** | [**\Camunda\Orchestration\Api\Model\TenantUpdateRequest**](../Model/TenantUpdateRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\TenantUpdateResult**](../Model/TenantUpdateResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
