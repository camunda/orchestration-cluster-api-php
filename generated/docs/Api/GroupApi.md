# Camunda\Orchestration\Api\GroupApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**assignClientToGroup()**](GroupApi.md#assignClientToGroup) | **PUT** /groups/{groupId}/clients/{clientId} | Assign a client to a group |
| [**assignMappingRuleToGroup()**](GroupApi.md#assignMappingRuleToGroup) | **PUT** /groups/{groupId}/mapping-rules/{mappingRuleId} | Assign a mapping rule to a group |
| [**assignUserToGroup()**](GroupApi.md#assignUserToGroup) | **PUT** /groups/{groupId}/users/{username} | Assign a user to a group |
| [**createGroup()**](GroupApi.md#createGroup) | **POST** /groups | Create group |
| [**deleteGroup()**](GroupApi.md#deleteGroup) | **DELETE** /groups/{groupId} | Delete group |
| [**getGroup()**](GroupApi.md#getGroup) | **GET** /groups/{groupId} | Get group |
| [**searchClientsForGroup()**](GroupApi.md#searchClientsForGroup) | **POST** /groups/{groupId}/clients/search | Search group clients |
| [**searchGroups()**](GroupApi.md#searchGroups) | **POST** /groups/search | Search groups |
| [**searchMappingRulesForGroup()**](GroupApi.md#searchMappingRulesForGroup) | **POST** /groups/{groupId}/mapping-rules/search | Search group mapping rules |
| [**searchRolesForGroup()**](GroupApi.md#searchRolesForGroup) | **POST** /groups/{groupId}/roles/search | Search group roles |
| [**searchUsersForGroup()**](GroupApi.md#searchUsersForGroup) | **POST** /groups/{groupId}/users/search | Search group users |
| [**unassignClientFromGroup()**](GroupApi.md#unassignClientFromGroup) | **DELETE** /groups/{groupId}/clients/{clientId} | Unassign a client from a group |
| [**unassignMappingRuleFromGroup()**](GroupApi.md#unassignMappingRuleFromGroup) | **DELETE** /groups/{groupId}/mapping-rules/{mappingRuleId} | Unassign a mapping rule from a group |
| [**unassignUserFromGroup()**](GroupApi.md#unassignUserFromGroup) | **DELETE** /groups/{groupId}/users/{username} | Unassign a user from a group |
| [**updateGroup()**](GroupApi.md#updateGroup) | **PUT** /groups/{groupId} | Update group |


## `assignClientToGroup()`

```php
assignClientToGroup($groupId, $clientId)
```

Assign a client to a group

Assigns a client to a group, making it a member of the group. Members of the group inherit the group authorizations, roles, and tenant assignments.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GroupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$groupId = 'groupId_example'; // string | The group ID.
$clientId = 'clientId_example'; // string | The client ID.

try {
    $apiInstance->assignClientToGroup($groupId, $clientId);
} catch (Exception $e) {
    echo 'Exception when calling GroupApi->assignClientToGroup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **groupId** | **string**| The group ID. | |
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

## `assignMappingRuleToGroup()`

```php
assignMappingRuleToGroup($groupId, $mappingRuleId)
```

Assign a mapping rule to a group

Assigns a mapping rule to a group.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GroupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$groupId = 'groupId_example'; // string | The group ID.
$mappingRuleId = 'mappingRuleId_example'; // string | The mapping rule ID.

try {
    $apiInstance->assignMappingRuleToGroup($groupId, $mappingRuleId);
} catch (Exception $e) {
    echo 'Exception when calling GroupApi->assignMappingRuleToGroup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **groupId** | **string**| The group ID. | |
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

## `assignUserToGroup()`

```php
assignUserToGroup($groupId, $username)
```

Assign a user to a group

Assigns a user to a group, making the user a member of the group. Group members inherit the group authorizations, roles, and tenant assignments.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GroupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$groupId = 'groupId_example'; // string | The group ID.
$username = 'username_example'; // string | The user username.

try {
    $apiInstance->assignUserToGroup($groupId, $username);
} catch (Exception $e) {
    echo 'Exception when calling GroupApi->assignUserToGroup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **groupId** | **string**| The group ID. | |
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

## `createGroup()`

```php
createGroup($groupCreateRequest): \Camunda\Orchestration\Api\Model\GroupCreateResult
```

Create group

Create a new group.  The supplied `groupId` is validated against `^[a-zA-Z0-9_~@.+-]+$` (max 256 characters) by `IdentifierValidator.validateId` in the runtime. This strict validation applies wherever the Groups API is available: in OIDC deployments that set `camunda.security.authentication.oidc.groupsClaim` the Groups API (including this endpoint) is disabled entirely, so group CRUD never sees externally-minted IdP IDs. The BYOG relaxation only loosens validation when a group is referenced *as a member* of a role or tenant (`assignRoleToGroup`, `assignGroupToTenant`); group CRUD itself always uses the strict default-id regex. The constraint is not advertised on the `GroupId` schema so that the same schema can be reused at member-reference sites without falsely rejecting externally-minted IdP group IDs there.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GroupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$groupCreateRequest = new \Camunda\Orchestration\Api\Model\GroupCreateRequest(); // \Camunda\Orchestration\Api\Model\GroupCreateRequest

try {
    $result = $apiInstance->createGroup($groupCreateRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling GroupApi->createGroup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **groupCreateRequest** | [**\Camunda\Orchestration\Api\Model\GroupCreateRequest**](../Model/GroupCreateRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\GroupCreateResult**](../Model/GroupCreateResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `deleteGroup()`

```php
deleteGroup($groupId)
```

Delete group

Deletes the group with the given ID.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GroupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$groupId = 'groupId_example'; // string | The group ID.

try {
    $apiInstance->deleteGroup($groupId);
} catch (Exception $e) {
    echo 'Exception when calling GroupApi->deleteGroup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
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

## `getGroup()`

```php
getGroup($groupId): \Camunda\Orchestration\Api\Model\GroupResult
```

Get group

Get a group by its ID.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GroupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$groupId = 'groupId_example'; // string | The group ID.

try {
    $result = $apiInstance->getGroup($groupId);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling GroupApi->getGroup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **groupId** | **string**| The group ID. | |

### Return type

[**\Camunda\Orchestration\Api\Model\GroupResult**](../Model/GroupResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchClientsForGroup()`

```php
searchClientsForGroup($groupId, $groupClientSearchQueryRequest): \Camunda\Orchestration\Api\Model\GroupClientSearchResult
```

Search group clients

Search clients assigned to a group.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GroupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$groupId = 'groupId_example'; // string | The group ID.
$groupClientSearchQueryRequest = new \Camunda\Orchestration\Api\Model\GroupClientSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\GroupClientSearchQueryRequest

try {
    $result = $apiInstance->searchClientsForGroup($groupId, $groupClientSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling GroupApi->searchClientsForGroup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **groupId** | **string**| The group ID. | |
| **groupClientSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\GroupClientSearchQueryRequest**](../Model/GroupClientSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\GroupClientSearchResult**](../Model/GroupClientSearchResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchGroups()`

```php
searchGroups($groupSearchQueryRequest): \Camunda\Orchestration\Api\Model\GroupSearchQueryResult
```

Search groups

Search for groups based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GroupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$groupSearchQueryRequest = new \Camunda\Orchestration\Api\Model\GroupSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\GroupSearchQueryRequest

try {
    $result = $apiInstance->searchGroups($groupSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling GroupApi->searchGroups: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **groupSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\GroupSearchQueryRequest**](../Model/GroupSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\GroupSearchQueryResult**](../Model/GroupSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchMappingRulesForGroup()`

```php
searchMappingRulesForGroup($groupId, $mappingRuleSearchQueryRequest): \Camunda\Orchestration\Api\Model\GroupMappingRuleSearchResult
```

Search group mapping rules

Search mapping rules assigned to a group.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GroupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$groupId = 'groupId_example'; // string | The group ID.
$mappingRuleSearchQueryRequest = new \Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest

try {
    $result = $apiInstance->searchMappingRulesForGroup($groupId, $mappingRuleSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling GroupApi->searchMappingRulesForGroup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **groupId** | **string**| The group ID. | |
| **mappingRuleSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\MappingRuleSearchQueryRequest**](../Model/MappingRuleSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\GroupMappingRuleSearchResult**](../Model/GroupMappingRuleSearchResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchRolesForGroup()`

```php
searchRolesForGroup($groupId, $roleSearchQueryRequest): \Camunda\Orchestration\Api\Model\GroupRoleSearchResult
```

Search group roles

Search roles assigned to a group.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GroupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$groupId = 'groupId_example'; // string | The group ID.
$roleSearchQueryRequest = new \Camunda\Orchestration\Api\Model\RoleSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\RoleSearchQueryRequest

try {
    $result = $apiInstance->searchRolesForGroup($groupId, $roleSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling GroupApi->searchRolesForGroup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **groupId** | **string**| The group ID. | |
| **roleSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\RoleSearchQueryRequest**](../Model/RoleSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\GroupRoleSearchResult**](../Model/GroupRoleSearchResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchUsersForGroup()`

```php
searchUsersForGroup($groupId, $groupUserSearchQueryRequest): \Camunda\Orchestration\Api\Model\GroupUserSearchResult
```

Search group users

Search users assigned to a group.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GroupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$groupId = 'groupId_example'; // string | The group ID.
$groupUserSearchQueryRequest = new \Camunda\Orchestration\Api\Model\GroupUserSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\GroupUserSearchQueryRequest

try {
    $result = $apiInstance->searchUsersForGroup($groupId, $groupUserSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling GroupApi->searchUsersForGroup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **groupId** | **string**| The group ID. | |
| **groupUserSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\GroupUserSearchQueryRequest**](../Model/GroupUserSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\GroupUserSearchResult**](../Model/GroupUserSearchResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `unassignClientFromGroup()`

```php
unassignClientFromGroup($groupId, $clientId)
```

Unassign a client from a group

Unassigns a client from a group. The client is removed as a group member, with associated authorizations, roles, and tenant assignments no longer applied.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GroupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$groupId = 'groupId_example'; // string | The group ID.
$clientId = 'clientId_example'; // string | The client ID.

try {
    $apiInstance->unassignClientFromGroup($groupId, $clientId);
} catch (Exception $e) {
    echo 'Exception when calling GroupApi->unassignClientFromGroup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **groupId** | **string**| The group ID. | |
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

## `unassignMappingRuleFromGroup()`

```php
unassignMappingRuleFromGroup($groupId, $mappingRuleId)
```

Unassign a mapping rule from a group

Unassigns a mapping rule from a group.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GroupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$groupId = 'groupId_example'; // string | The group ID.
$mappingRuleId = 'mappingRuleId_example'; // string | The mapping rule ID.

try {
    $apiInstance->unassignMappingRuleFromGroup($groupId, $mappingRuleId);
} catch (Exception $e) {
    echo 'Exception when calling GroupApi->unassignMappingRuleFromGroup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **groupId** | **string**| The group ID. | |
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

## `unassignUserFromGroup()`

```php
unassignUserFromGroup($groupId, $username)
```

Unassign a user from a group

Unassigns a user from a group. The user is removed as a group member, with associated authorizations, roles, and tenant assignments no longer applied.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GroupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$groupId = 'groupId_example'; // string | The group ID.
$username = 'username_example'; // string | The user username.

try {
    $apiInstance->unassignUserFromGroup($groupId, $username);
} catch (Exception $e) {
    echo 'Exception when calling GroupApi->unassignUserFromGroup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **groupId** | **string**| The group ID. | |
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

## `updateGroup()`

```php
updateGroup($groupId, $groupUpdateRequest): \Camunda\Orchestration\Api\Model\GroupUpdateResult
```

Update group

Update a group with the given ID.

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


$apiInstance = new Camunda\Orchestration\Api\Api\GroupApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$groupId = 'groupId_example'; // string | The group ID.
$groupUpdateRequest = new \Camunda\Orchestration\Api\Model\GroupUpdateRequest(); // \Camunda\Orchestration\Api\Model\GroupUpdateRequest

try {
    $result = $apiInstance->updateGroup($groupId, $groupUpdateRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling GroupApi->updateGroup: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **groupId** | **string**| The group ID. | |
| **groupUpdateRequest** | [**\Camunda\Orchestration\Api\Model\GroupUpdateRequest**](../Model/GroupUpdateRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\GroupUpdateResult**](../Model/GroupUpdateResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
