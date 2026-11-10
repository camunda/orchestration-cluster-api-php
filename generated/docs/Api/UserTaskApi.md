# Camunda\Orchestration\Api\UserTaskApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**assignUserTask()**](UserTaskApi.md#assignUserTask) | **POST** /user-tasks/{userTaskKey}/assignment | Assign user task |
| [**completeUserTask()**](UserTaskApi.md#completeUserTask) | **POST** /user-tasks/{userTaskKey}/completion | Complete user task |
| [**getUserTask()**](UserTaskApi.md#getUserTask) | **GET** /user-tasks/{userTaskKey} | Get user task |
| [**getUserTaskForm()**](UserTaskApi.md#getUserTaskForm) | **GET** /user-tasks/{userTaskKey}/form | Get user task form |
| [**lookupUserTask()**](UserTaskApi.md#lookupUserTask) | **POST** /user-tasks/{userTaskKey}/lookup | Look up an active user task (alpha) |
| [**searchUserTaskAuditLogs()**](UserTaskApi.md#searchUserTaskAuditLogs) | **POST** /user-tasks/{userTaskKey}/audit-logs/search | Search user task audit logs |
| [**searchUserTaskEffectiveVariables()**](UserTaskApi.md#searchUserTaskEffectiveVariables) | **POST** /user-tasks/{userTaskKey}/effective-variables/search | Search user task effective variables |
| [**searchUserTaskVariables()**](UserTaskApi.md#searchUserTaskVariables) | **POST** /user-tasks/{userTaskKey}/variables/search | Search user task variables |
| [**searchUserTasks()**](UserTaskApi.md#searchUserTasks) | **POST** /user-tasks/search | Search user tasks |
| [**unassignUserTask()**](UserTaskApi.md#unassignUserTask) | **DELETE** /user-tasks/{userTaskKey}/assignee | Unassign user task |
| [**updateUserTask()**](UserTaskApi.md#updateUserTask) | **PATCH** /user-tasks/{userTaskKey} | Update user task |


## `assignUserTask()`

```php
assignUserTask($userTaskKey, $userTaskAssignmentRequest)
```

Assign user task

Assigns a user task with the given key to the given assignee. Assignment waits for blocking task listeners on this lifecycle transition. If listener processing is delayed beyond the request timeout, this endpoint can return 504. Other gateway timeout causes are also possible. Retry with backoff and inspect listener worker availability and logs when this repeats.

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


$apiInstance = new Camunda\Orchestration\Api\Api\UserTaskApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$userTaskKey = 'userTaskKey_example'; // string | The key of the user task to assign.
$userTaskAssignmentRequest = new \Camunda\Orchestration\Api\Model\UserTaskAssignmentRequest(); // \Camunda\Orchestration\Api\Model\UserTaskAssignmentRequest

try {
    $apiInstance->assignUserTask($userTaskKey, $userTaskAssignmentRequest);
} catch (Exception $e) {
    echo 'Exception when calling UserTaskApi->assignUserTask: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **userTaskKey** | **string**| The key of the user task to assign. | |
| **userTaskAssignmentRequest** | [**\Camunda\Orchestration\Api\Model\UserTaskAssignmentRequest**](../Model/UserTaskAssignmentRequest.md)|  | |

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

## `completeUserTask()`

```php
completeUserTask($userTaskKey, $userTaskCompletionRequest)
```

Complete user task

Completes a user task with the given key. Completion waits for blocking task listeners on this lifecycle transition. If listener processing is delayed beyond the request timeout, this endpoint can return 504. Other gateway timeout causes are also possible. Retry with backoff and inspect listener worker availability and logs when this repeats.

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


$apiInstance = new Camunda\Orchestration\Api\Api\UserTaskApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$userTaskKey = 'userTaskKey_example'; // string | The key of the user task to complete.
$userTaskCompletionRequest = new \Camunda\Orchestration\Api\Model\UserTaskCompletionRequest(); // \Camunda\Orchestration\Api\Model\UserTaskCompletionRequest

try {
    $apiInstance->completeUserTask($userTaskKey, $userTaskCompletionRequest);
} catch (Exception $e) {
    echo 'Exception when calling UserTaskApi->completeUserTask: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **userTaskKey** | **string**| The key of the user task to complete. | |
| **userTaskCompletionRequest** | [**\Camunda\Orchestration\Api\Model\UserTaskCompletionRequest**](../Model/UserTaskCompletionRequest.md)|  | [optional] |

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

## `getUserTask()`

```php
getUserTask($userTaskKey): \Camunda\Orchestration\Api\Model\UserTaskResult
```

Get user task

Get the user task by the user task key.

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


$apiInstance = new Camunda\Orchestration\Api\Api\UserTaskApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$userTaskKey = 'userTaskKey_example'; // string | The user task key.

try {
    $result = $apiInstance->getUserTask($userTaskKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling UserTaskApi->getUserTask: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **userTaskKey** | **string**| The user task key. | |

### Return type

[**\Camunda\Orchestration\Api\Model\UserTaskResult**](../Model/UserTaskResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getUserTaskForm()`

```php
getUserTaskForm($userTaskKey): \Camunda\Orchestration\Api\Model\FormResult
```

Get user task form

Get the form of a user task. Note that this endpoint will only return linked forms. This endpoint does not support embedded forms.

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


$apiInstance = new Camunda\Orchestration\Api\Api\UserTaskApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$userTaskKey = 'userTaskKey_example'; // string | The user task key.

try {
    $result = $apiInstance->getUserTaskForm($userTaskKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling UserTaskApi->getUserTaskForm: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **userTaskKey** | **string**| The user task key. | |

### Return type

[**\Camunda\Orchestration\Api\Model\FormResult**](../Model/FormResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `lookupUserTask()`

```php
lookupUserTask($userTaskKey, $userTaskLookupRequest): \Camunda\Orchestration\Api\Model\UserTaskLookupResult
```

Look up an active user task (alpha)

Returns an active user task together with the variables visible to it, read from the engine's state instead of secondary storage. The task and its variables are read at the same point in processing, so they are consistent with each other and with any command the engine has already processed, for example the completion of a previous task.  The response includes the task's lifecycle `state`. Use it to interpret the returned values: while a change to the task is in progress, the task fields and variables are the ones from before the change, and `state` names the pending change. For example, with `ASSIGNING` the returned `assignee` is still the previous one, because task listeners can deny the new assignment; with `COMPLETING` the variables don't include the ones sent with the completion yet.  Only active tasks can be looked up. The lookup returns 404 when the task doesn't exist, when it was already completed or canceled (use the user task search for those), or when it belongs to a tenant the caller isn't assigned to. It returns 409 when the task's process instance is banned: an instance that hit an unrecoverable error and can only be canceled.  Each lookup is processed by the engine. Use it when a task is opened, not for polling.  This endpoint is an alpha feature and may be subject to change in future releases.

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


$apiInstance = new Camunda\Orchestration\Api\Api\UserTaskApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$userTaskKey = 'userTaskKey_example'; // string | The key of the user task to look up.
$userTaskLookupRequest = new \Camunda\Orchestration\Api\Model\UserTaskLookupRequest(); // \Camunda\Orchestration\Api\Model\UserTaskLookupRequest

try {
    $result = $apiInstance->lookupUserTask($userTaskKey, $userTaskLookupRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling UserTaskApi->lookupUserTask: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **userTaskKey** | **string**| The key of the user task to look up. | |
| **userTaskLookupRequest** | [**\Camunda\Orchestration\Api\Model\UserTaskLookupRequest**](../Model/UserTaskLookupRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\UserTaskLookupResult**](../Model/UserTaskLookupResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchUserTaskAuditLogs()`

```php
searchUserTaskAuditLogs($userTaskKey, $userTaskAuditLogSearchQueryRequest): \Camunda\Orchestration\Api\Model\AuditLogSearchQueryResult
```

Search user task audit logs

Search for user task audit logs based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\UserTaskApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$userTaskKey = 'userTaskKey_example'; // string | The key of the user task.
$userTaskAuditLogSearchQueryRequest = new \Camunda\Orchestration\Api\Model\UserTaskAuditLogSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\UserTaskAuditLogSearchQueryRequest

try {
    $result = $apiInstance->searchUserTaskAuditLogs($userTaskKey, $userTaskAuditLogSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling UserTaskApi->searchUserTaskAuditLogs: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **userTaskKey** | **string**| The key of the user task. | |
| **userTaskAuditLogSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\UserTaskAuditLogSearchQueryRequest**](../Model/UserTaskAuditLogSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\AuditLogSearchQueryResult**](../Model/AuditLogSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchUserTaskEffectiveVariables()`

```php
searchUserTaskEffectiveVariables($userTaskKey, $truncateValues, $userTaskEffectiveVariableSearchQueryRequest): \Camunda\Orchestration\Api\Model\VariableSearchQueryResult
```

Search user task effective variables

Search for the effective variables of a user task. This endpoint returns deduplicated variables where each variable name appears at most once. When the same variable name exists at multiple scope levels in the scope hierarchy, the value from the innermost scope (closest to the user task) takes precedence. This is useful for retrieving the actual runtime state of variables as seen by the user task. By default, long variable values in the response are truncated.

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


$apiInstance = new Camunda\Orchestration\Api\Api\UserTaskApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$userTaskKey = 'userTaskKey_example'; // string | The key of the user task.
$truncateValues = True; // bool | When true (default), long variable values in the response are truncated. When false, full variable values are returned.
$userTaskEffectiveVariableSearchQueryRequest = new \Camunda\Orchestration\Api\Model\UserTaskEffectiveVariableSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\UserTaskEffectiveVariableSearchQueryRequest

try {
    $result = $apiInstance->searchUserTaskEffectiveVariables($userTaskKey, $truncateValues, $userTaskEffectiveVariableSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling UserTaskApi->searchUserTaskEffectiveVariables: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **userTaskKey** | **string**| The key of the user task. | |
| **truncateValues** | **bool**| When true (default), long variable values in the response are truncated. When false, full variable values are returned. | [optional] |
| **userTaskEffectiveVariableSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\UserTaskEffectiveVariableSearchQueryRequest**](../Model/UserTaskEffectiveVariableSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\VariableSearchQueryResult**](../Model/VariableSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchUserTaskVariables()`

```php
searchUserTaskVariables($userTaskKey, $truncateValues, $userTaskVariableSearchQueryRequest): \Camunda\Orchestration\Api\Model\VariableSearchQueryResult
```

Search user task variables

Search for user task variables based on given criteria. This endpoint returns all variable documents visible from the user task's scope, including variables from parent scopes in the scope hierarchy. If the same variable name exists at multiple scope levels, each scope's variable is returned as a separate result. Use the `/user-tasks/{userTaskKey}/effective-variables/search` endpoint to get deduplicated variables where the innermost scope takes precedence. By default, long variable values in the response are truncated.

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


$apiInstance = new Camunda\Orchestration\Api\Api\UserTaskApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$userTaskKey = 'userTaskKey_example'; // string | The key of the user task.
$truncateValues = True; // bool | When true (default), long variable values in the response are truncated. When false, full variable values are returned.
$userTaskVariableSearchQueryRequest = new \Camunda\Orchestration\Api\Model\UserTaskVariableSearchQueryRequest(); // \Camunda\Orchestration\Api\Model\UserTaskVariableSearchQueryRequest

try {
    $result = $apiInstance->searchUserTaskVariables($userTaskKey, $truncateValues, $userTaskVariableSearchQueryRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling UserTaskApi->searchUserTaskVariables: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **userTaskKey** | **string**| The key of the user task. | |
| **truncateValues** | **bool**| When true (default), long variable values in the response are truncated. When false, full variable values are returned. | [optional] |
| **userTaskVariableSearchQueryRequest** | [**\Camunda\Orchestration\Api\Model\UserTaskVariableSearchQueryRequest**](../Model/UserTaskVariableSearchQueryRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\VariableSearchQueryResult**](../Model/VariableSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchUserTasks()`

```php
searchUserTasks($userTaskSearchQuery): \Camunda\Orchestration\Api\Model\UserTaskSearchQueryResult
```

Search user tasks

Search for user tasks based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\UserTaskApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$userTaskSearchQuery = new \Camunda\Orchestration\Api\Model\UserTaskSearchQuery(); // \Camunda\Orchestration\Api\Model\UserTaskSearchQuery

try {
    $result = $apiInstance->searchUserTasks($userTaskSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling UserTaskApi->searchUserTasks: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **userTaskSearchQuery** | [**\Camunda\Orchestration\Api\Model\UserTaskSearchQuery**](../Model/UserTaskSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\UserTaskSearchQueryResult**](../Model/UserTaskSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `unassignUserTask()`

```php
unassignUserTask($userTaskKey)
```

Unassign user task

Removes the assignee of a task with the given key. Unassignment waits for blocking task listeners on this lifecycle transition. If listener processing is delayed beyond the request timeout, this endpoint can return 504. Other gateway timeout causes are also possible. Retry with backoff and inspect listener worker availability and logs when this repeats.

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


$apiInstance = new Camunda\Orchestration\Api\Api\UserTaskApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$userTaskKey = 'userTaskKey_example'; // string | The key of the user task.

try {
    $apiInstance->unassignUserTask($userTaskKey);
} catch (Exception $e) {
    echo 'Exception when calling UserTaskApi->unassignUserTask: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **userTaskKey** | **string**| The key of the user task. | |

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

## `updateUserTask()`

```php
updateUserTask($userTaskKey, $userTaskUpdateRequest)
```

Update user task

Update a user task with the given key. Updates wait for blocking task listeners on this lifecycle transition. If listener processing is delayed beyond the request timeout, this endpoint can return 504. Other gateway timeout causes are also possible. Retry with backoff and inspect listener worker availability and logs when this repeats.

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


$apiInstance = new Camunda\Orchestration\Api\Api\UserTaskApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$userTaskKey = 'userTaskKey_example'; // string | The key of the user task to update.
$userTaskUpdateRequest = new \Camunda\Orchestration\Api\Model\UserTaskUpdateRequest(); // \Camunda\Orchestration\Api\Model\UserTaskUpdateRequest

try {
    $apiInstance->updateUserTask($userTaskKey, $userTaskUpdateRequest);
} catch (Exception $e) {
    echo 'Exception when calling UserTaskApi->updateUserTask: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **userTaskKey** | **string**| The key of the user task to update. | |
| **userTaskUpdateRequest** | [**\Camunda\Orchestration\Api\Model\UserTaskUpdateRequest**](../Model/UserTaskUpdateRequest.md)|  | [optional] |

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
