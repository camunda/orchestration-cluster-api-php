# Camunda\Orchestration\Api\RoleApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**assignRoleToClient()**](RoleApi.md#assignRoleToClient) | **PUT** /roles/{roleId}/clients/{clientId} | Assign a role to a client |
| [**assignRoleToGroup()**](RoleApi.md#assignRoleToGroup) | **PUT** /roles/{roleId}/groups/{groupId} | Assign a role to a group |
| [**assignRoleToMappingRule()**](RoleApi.md#assignRoleToMappingRule) | **PUT** /roles/{roleId}/mapping-rules/{mappingRuleId} | Assign a role to a mapping rule |
| [**assignRoleToUser()**](RoleApi.md#assignRoleToUser) | **PUT** /roles/{roleId}/users/{username} | Assign a role to a user |
| [**createRole()**](RoleApi.md#createRole) | **POST** /roles | Create role |
| [**deleteRole()**](RoleApi.md#deleteRole) | **DELETE** /roles/{roleId} | Delete role |
| [**getRole()**](RoleApi.md#getRole) | **GET** /roles/{roleId} | Get role |
| [**searchClientsForRole()**](RoleApi.md#searchClientsForRole) | **POST** /roles/{roleId}/clients/search | Search role clients |
| [**searchGroupsForRole()**](RoleApi.md#searchGroupsForRole) | **POST** /roles/{roleId}/groups/search | Search role groups |
| [**searchMappingRulesForRole()**](RoleApi.md#searchMappingRulesForRole) | **POST** /roles/{roleId}/mapping-rules/search | Search role mapping rules |
| [**searchRoles()**](RoleApi.md#searchRoles) | **POST** /roles/search | Search roles |
| [**searchUsersForRole()**](RoleApi.md#searchUsersForRole) | **POST** /roles/{roleId}/users/search | Search role users |
| [**unassignRoleFromClient()**](RoleApi.md#unassignRoleFromClient) | **DELETE** /roles/{roleId}/clients/{clientId} | Unassign a role from a client |
| [**unassignRoleFromGroup()**](RoleApi.md#unassignRoleFromGroup) | **DELETE** /roles/{roleId}/groups/{groupId} | Unassign a role from a group |
| [**unassignRoleFromMappingRule()**](RoleApi.md#unassignRoleFromMappingRule) | **DELETE** /roles/{roleId}/mapping-rules/{mappingRuleId} | Unassign a role from a mapping rule |
| [**unassignRoleFromUser()**](RoleApi.md#unassignRoleFromUser) | **DELETE** /roles/{roleId}/users/{username} | Unassign a role from a user |
| [**updateRole()**](RoleApi.md#updateRole) | **PUT** /roles/{roleId} | Update role |


## `assignRoleToClient()`

```php
assignRoleToClient($roleId, $clientId)
```

Assign a role to a client

Assigns the specified role to the client. The client will inherit the authorizations associated with this role.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RoleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$roleId = 'roleId_example'; // string | The role ID.
$clientId = 'clientId_example'; // string | The client ID.

try {
    $apiInstance->assignRoleToClient($roleId, $clientId);
} catch (Exception $e) {
    echo 'Exception when calling RoleApi->assignRoleToClient: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **roleId** | **string**| The role ID. | |
| **clientId** | **string**| The client ID. | |

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

## `assignRoleToGroup()`

```php
assignRoleToGroup($roleId, $groupId)
```

Assign a role to a group

Assigns the specified role to the group. Every member of the group (user or client) will inherit the authorizations associated with this role.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RoleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$roleId = 'roleId_example'; // string | The role ID.
$groupId = 'groupId_example'; // string | The group ID.

try {
    $apiInstance->assignRoleToGroup($roleId, $groupId);
} catch (Exception $e) {
    echo 'Exception when calling RoleApi->assignRoleToGroup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **roleId** | **string**| The role ID. | |
| **groupId** | **string**| The group ID. | |

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

## `assignRoleToMappingRule()`

```php
assignRoleToMappingRule($roleId, $mappingRuleId)
```

Assign a role to a mapping rule

Assigns a role to a mapping rule.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RoleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$roleId = 'roleId_example'; // string | The role ID.
$mappingRuleId = 'mappingRuleId_example'; // string | The mapping rule ID.

try {
    $apiInstance->assignRoleToMappingRule($roleId, $mappingRuleId);
} catch (Exception $e) {
    echo 'Exception when calling RoleApi->assignRoleToMappingRule: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **roleId** | **string**| The role ID. | |
| **mappingRuleId** | **string**| The mapping rule ID. | |

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

## `assignRoleToUser()`

```php
assignRoleToUser($roleId, $username)
```

Assign a role to a user

Assigns the specified role to the user. The user will inherit the authorizations associated with this role.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RoleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$roleId = 'roleId_example'; // string | The role ID.
$username = 'username_example'; // string | The user username.

try {
    $apiInstance->assignRoleToUser($roleId, $username);
} catch (Exception $e) {
    echo 'Exception when calling RoleApi->assignRoleToUser: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **roleId** | **string**| The role ID. | |
| **username** | **string**| The user username. | |

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

## `createRole()`

```php
createRole($roleCreateRequest): \Camunda\Orchestration\Api\Model\RoleCreateResult
```

Create role

Create a new role.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RoleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$roleCreateRequest = new \Camunda\Orchestration\Api\Model\RoleCreateRequest(); // \Camunda\Orchestration\Api\Model\RoleCreateRequest

try {
    $result = $apiInstance->createRole($roleCreateRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RoleApi->createRole: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **roleCreateRequest** | [**\Camunda\Orchestration\Api\Model\RoleCreateRequest**](../Model/RoleCreateRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\RoleCreateResult**](../Model/RoleCreateResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `deleteRole()`

```php
deleteRole($roleId)
```

Delete role

Deletes the role with the given ID.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RoleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$roleId = 'roleId_example'; // string | The role ID.

try {
    $apiInstance->deleteRole($roleId);
} catch (Exception $e) {
    echo 'Exception when calling RoleApi->deleteRole: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **roleId** | **string**| The role ID. | |

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

## `getRole()`

```php
getRole($roleId): \Camunda\Orchestration\Api\Model\RoleResult
```

Get role

Get a role by its ID.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RoleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$roleId = 'roleId_example'; // string | The role ID.

try {
    $result = $apiInstance->getRole($roleId);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RoleApi->getRole: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **roleId** | **string**| The role ID. | |

### Return type

[**\Camunda\Orchestration\Api\Model\RoleResult**](../Model/RoleResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchClientsForRole()`

```php
searchClientsForRole($roleId, $roleClientSearchQueryRequest): \Camunda\Orchestration\Api\Model\RoleClientSearchResult
```

Search role clients

Search clients with assigned role.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RoleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$roleId = 'roleId_example'; // string | The role ID.
$roleClientSearchQueryRequest = new \Camunda\Orchestration\Api\Model\RoleClientSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\RoleClientSearchQueryRequest

try {
    $result = $apiInstance->searchClientsForRole($roleId, $roleClientSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RoleApi->searchClientsForRole: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **roleId** | **string**| The role ID. | |
| **roleClientSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\RoleClientSearchQueryRequest**](../Model/RoleClientSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\RoleClientSearchResult**](../Model/RoleClientSearchResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchGroupsForRole()`

```php
searchGroupsForRole($roleId, $roleGroupSearchQueryRequest): \Camunda\Orchestration\Api\Model\RoleGroupSearchResult
```

Search role groups

Search groups with assigned role.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RoleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$roleId = 'roleId_example'; // string | The role ID.
$roleGroupSearchQueryRequest = new \Camunda\Orchestration\Api\Model\RoleGroupSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\RoleGroupSearchQueryRequest

try {
    $result = $apiInstance->searchGroupsForRole($roleId, $roleGroupSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RoleApi->searchGroupsForRole: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **roleId** | **string**| The role ID. | |
| **roleGroupSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\RoleGroupSearchQueryRequest**](../Model/RoleGroupSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\RoleGroupSearchResult**](../Model/RoleGroupSearchResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchMappingRulesForRole()`

```php
searchMappingRulesForRole($roleId, $mappingRuleSearchQueryRequest): \Camunda\Orchestration\Api\Model\RoleMappingRuleSearchResult
```

Search role mapping rules

Search mapping rules with assigned role.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RoleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$roleId = 'roleId_example'; // string | The role ID.
$mappingRuleSearchQueryRequest = new \Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest

try {
    $result = $apiInstance->searchMappingRulesForRole($roleId, $mappingRuleSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RoleApi->searchMappingRulesForRole: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **roleId** | **string**| The role ID. | |
| **mappingRuleSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest**](../Model/MappingRuleSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\RoleMappingRuleSearchResult**](../Model/RoleMappingRuleSearchResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchRoles()`

```php
searchRoles($roleSearchQueryRequest): \Camunda\Orchestration\Api\Model\RoleSearchQueryResult
```

Search roles

Search for roles based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RoleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$roleSearchQueryRequest = new \Camunda\Orchestration\Api\Model\RoleSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\RoleSearchQueryRequest

try {
    $result = $apiInstance->searchRoles($roleSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RoleApi->searchRoles: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **roleSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\RoleSearchQueryRequest**](../Model/RoleSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\RoleSearchQueryResult**](../Model/RoleSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchUsersForRole()`

```php
searchUsersForRole($roleId, $roleUserSearchQueryRequest): \Camunda\Orchestration\Api\Model\RoleUserSearchResult
```

Search role users

Search users with assigned role.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RoleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$roleId = 'roleId_example'; // string | The role ID.
$roleUserSearchQueryRequest = new \Camunda\Orchestration\Api\Model\RoleUserSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\RoleUserSearchQueryRequest

try {
    $result = $apiInstance->searchUsersForRole($roleId, $roleUserSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RoleApi->searchUsersForRole: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **roleId** | **string**| The role ID. | |
| **roleUserSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\RoleUserSearchQueryRequest**](../Model/RoleUserSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\RoleUserSearchResult**](../Model/RoleUserSearchResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `unassignRoleFromClient()`

```php
unassignRoleFromClient($roleId, $clientId)
```

Unassign a role from a client

Unassigns the specified role from the client. The client will no longer inherit the authorizations associated with this role.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RoleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$roleId = 'roleId_example'; // string | The role ID.
$clientId = 'clientId_example'; // string | The client ID.

try {
    $apiInstance->unassignRoleFromClient($roleId, $clientId);
} catch (Exception $e) {
    echo 'Exception when calling RoleApi->unassignRoleFromClient: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **roleId** | **string**| The role ID. | |
| **clientId** | **string**| The client ID. | |

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

## `unassignRoleFromGroup()`

```php
unassignRoleFromGroup($roleId, $groupId)
```

Unassign a role from a group

Unassigns the specified role from the group. All group members (user or client) no longer inherit the authorizations associated with this role.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RoleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$roleId = 'roleId_example'; // string | The role ID.
$groupId = 'groupId_example'; // string | The group ID.

try {
    $apiInstance->unassignRoleFromGroup($roleId, $groupId);
} catch (Exception $e) {
    echo 'Exception when calling RoleApi->unassignRoleFromGroup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **roleId** | **string**| The role ID. | |
| **groupId** | **string**| The group ID. | |

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

## `unassignRoleFromMappingRule()`

```php
unassignRoleFromMappingRule($roleId, $mappingRuleId)
```

Unassign a role from a mapping rule

Unassigns a role from a mapping rule.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RoleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$roleId = 'roleId_example'; // string | The role ID.
$mappingRuleId = 'mappingRuleId_example'; // string | The mapping rule ID.

try {
    $apiInstance->unassignRoleFromMappingRule($roleId, $mappingRuleId);
} catch (Exception $e) {
    echo 'Exception when calling RoleApi->unassignRoleFromMappingRule: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **roleId** | **string**| The role ID. | |
| **mappingRuleId** | **string**| The mapping rule ID. | |

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

## `unassignRoleFromUser()`

```php
unassignRoleFromUser($roleId, $username)
```

Unassign a role from a user

Unassigns a role from a user. The user will no longer inherit the authorizations associated with this role.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RoleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$roleId = 'roleId_example'; // string | The role ID.
$username = 'username_example'; // string | The user username.

try {
    $apiInstance->unassignRoleFromUser($roleId, $username);
} catch (Exception $e) {
    echo 'Exception when calling RoleApi->unassignRoleFromUser: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **roleId** | **string**| The role ID. | |
| **username** | **string**| The user username. | |

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

## `updateRole()`

```php
updateRole($roleId, $roleUpdateRequest): \Camunda\Orchestration\Api\Model\RoleUpdateResult
```

Update role

Update a role with the given ID.

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


$apiInstance = new Camunda\Orchestration\Api\Api\RoleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$roleId = 'roleId_example'; // string | The role ID.
$roleUpdateRequest = new \Camunda\Orchestration\Api\Model\RoleUpdateRequest(); // \Camunda\Orchestration\Api\Model\RoleUpdateRequest

try {
    $result = $apiInstance->updateRole($roleId, $roleUpdateRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RoleApi->updateRole: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **roleId** | **string**| The role ID. | |
| **roleUpdateRequest** | [**\Camunda\Orchestration\Api\Model\RoleUpdateRequest**](../Model/RoleUpdateRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\RoleUpdateResult**](../Model/RoleUpdateResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
