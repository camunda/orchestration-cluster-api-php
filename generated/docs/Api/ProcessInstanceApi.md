# Camunda\Orchestration\Api\ProcessInstanceApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**assignProcessInstanceBusinessId()**](ProcessInstanceApi.md#assignProcessInstanceBusinessId) | **POST** /process-instances/{processInstanceKey}/business-id-assignment | Assign business id to process instance |
| [**cancelProcessInstance()**](ProcessInstanceApi.md#cancelProcessInstance) | **POST** /process-instances/{processInstanceKey}/cancellation | Cancel process instance |
| [**cancelProcessInstancesBatchOperation()**](ProcessInstanceApi.md#cancelProcessInstancesBatchOperation) | **POST** /process-instances/cancellation | Cancel process instances (batch) |
| [**createProcessInstance()**](ProcessInstanceApi.md#createProcessInstance) | **POST** /process-instances | Create process instance |
| [**deleteProcessInstance()**](ProcessInstanceApi.md#deleteProcessInstance) | **POST** /process-instances/{processInstanceKey}/deletion | Delete process instance |
| [**deleteProcessInstancesBatchOperation()**](ProcessInstanceApi.md#deleteProcessInstancesBatchOperation) | **POST** /process-instances/deletion | Delete process instances (batch) |
| [**getProcessInstance()**](ProcessInstanceApi.md#getProcessInstance) | **GET** /process-instances/{processInstanceKey} | Get process instance |
| [**getProcessInstanceCallHierarchy()**](ProcessInstanceApi.md#getProcessInstanceCallHierarchy) | **GET** /process-instances/{processInstanceKey}/call-hierarchy | Get call hierarchy |
| [**getProcessInstanceSequenceFlows()**](ProcessInstanceApi.md#getProcessInstanceSequenceFlows) | **GET** /process-instances/{processInstanceKey}/sequence-flows | Get sequence flows |
| [**getProcessInstanceStatistics()**](ProcessInstanceApi.md#getProcessInstanceStatistics) | **GET** /process-instances/{processInstanceKey}/statistics/element-instances | Get element instance statistics |
| [**getProcessInstanceWaitStateStatistics()**](ProcessInstanceApi.md#getProcessInstanceWaitStateStatistics) | **GET** /process-instances/{processInstanceKey}/statistics/wait-states | Get wait state statistics |
| [**migrateProcessInstance()**](ProcessInstanceApi.md#migrateProcessInstance) | **POST** /process-instances/{processInstanceKey}/migration | Migrate process instance |
| [**migrateProcessInstancesBatchOperation()**](ProcessInstanceApi.md#migrateProcessInstancesBatchOperation) | **POST** /process-instances/migration | Migrate process instances (batch) |
| [**modifyProcessInstance()**](ProcessInstanceApi.md#modifyProcessInstance) | **POST** /process-instances/{processInstanceKey}/modification | Modify process instance |
| [**modifyProcessInstancesBatchOperation()**](ProcessInstanceApi.md#modifyProcessInstancesBatchOperation) | **POST** /process-instances/modification | Modify process instances (batch) |
| [**resolveIncidentsBatchOperation()**](ProcessInstanceApi.md#resolveIncidentsBatchOperation) | **POST** /process-instances/incident-resolution | Resolve related incidents (batch) |
| [**resolveProcessInstanceIncidents()**](ProcessInstanceApi.md#resolveProcessInstanceIncidents) | **POST** /process-instances/{processInstanceKey}/incident-resolution | Resolve related incidents |
| [**resumeProcessInstance()**](ProcessInstanceApi.md#resumeProcessInstance) | **POST** /process-instances/{processInstanceKey}/resumption | Resume process instance |
| [**resumeProcessInstancesBatchOperation()**](ProcessInstanceApi.md#resumeProcessInstancesBatchOperation) | **POST** /process-instances/resumption | Resume process instances (batch) |
| [**searchProcessInstanceIncidents()**](ProcessInstanceApi.md#searchProcessInstanceIncidents) | **POST** /process-instances/{processInstanceKey}/incidents/search | Search related incidents |
| [**searchProcessInstances()**](ProcessInstanceApi.md#searchProcessInstances) | **POST** /process-instances/search | Search process instances |
| [**suspendProcessInstance()**](ProcessInstanceApi.md#suspendProcessInstance) | **POST** /process-instances/{processInstanceKey}/suspension | Suspend process instance |
| [**suspendProcessInstancesBatchOperation()**](ProcessInstanceApi.md#suspendProcessInstancesBatchOperation) | **POST** /process-instances/suspension | Suspend process instances (batch) |


## `assignProcessInstanceBusinessId()`

```php
assignProcessInstanceBusinessId($processInstanceKey, $processInstanceBusinessIdAssignmentInstruction)
```

Assign business id to process instance

Assigns a business id to an already-running process instance that currently has none.  The assignment is single and irreversible: only artifacts created after the assignment (for example future jobs, user tasks, decision instances, and message subscriptions) carry the business id, while existing artifacts are not retroactively enriched. Re-sending the same business id succeeds as a no-op. This endpoint is only useful while business id uniqueness enforcement is disabled; when it is enabled, the request is rejected with a 409 response.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceKey = 'processInstanceKey_example'; // string | The key of the process instance to assign the business id to.
$processInstanceBusinessIdAssignmentInstruction = new \Camunda\Orchestration\Api\Model\ProcessInstanceBusinessIdAssignmentInstruction(); // \Camunda\Orchestration\Api\Model\ProcessInstanceBusinessIdAssignmentInstruction

try {
    $apiInstance->assignProcessInstanceBusinessId($processInstanceKey, $processInstanceBusinessIdAssignmentInstruction);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->assignProcessInstanceBusinessId: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceKey** | **string**| The key of the process instance to assign the business id to. | |
| **processInstanceBusinessIdAssignmentInstruction** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceBusinessIdAssignmentInstruction**](../Model/ProcessInstanceBusinessIdAssignmentInstruction.md)|  | |

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

## `cancelProcessInstance()`

```php
cancelProcessInstance($processInstanceKey, $cancelProcessInstanceRequest)
```

Cancel process instance

Cancels a running process instance. As a cancellation includes more than just the removal of the process instance resource, the cancellation resource must be posted. Cancellation can wait on listener-related processing; when that processing does not complete in time, this endpoint can return 504. Other gateway timeout causes are also possible. Retry with backoff and inspect listener worker availability and logs when this repeats.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceKey = 'processInstanceKey_example'; // string | The key of the process instance to cancel.
$cancelProcessInstanceRequest = new \Camunda\Orchestration\Api\Model\CancelProcessInstanceRequest(); // \Camunda\Orchestration\Api\Model\CancelProcessInstanceRequest

try {
    $apiInstance->cancelProcessInstance($processInstanceKey, $cancelProcessInstanceRequest);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->cancelProcessInstance: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceKey** | **string**| The key of the process instance to cancel. | |
| **cancelProcessInstanceRequest** | [**\Camunda\Orchestration\Api\Model\CancelProcessInstanceRequest**](../Model/CancelProcessInstanceRequest.md)|  | [optional] |

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

## `cancelProcessInstancesBatchOperation()`

```php
cancelProcessInstancesBatchOperation($processInstanceCancellationBatchOperationRequest): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult
```

Cancel process instances (batch)

Cancels multiple active or suspended process instances. Since only ACTIVE and SUSPENDED root instances can be cancelled, any given filters for state and parentProcessInstanceKey are ignored and overridden during this batch operation. This is done asynchronously, the progress can be tracked using the batchOperationKey from the response and the batch operation status endpoint (/batch-operations/{batchOperationKey}).

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceCancellationBatchOperationRequest = new \Camunda\Orchestration\Api\Model\ProcessInstanceCancellationBatchOperationRequest(); // \Camunda\Orchestration\Api\Model\ProcessInstanceCancellationBatchOperationRequest

try {
    $result = $apiInstance->cancelProcessInstancesBatchOperation($processInstanceCancellationBatchOperationRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->cancelProcessInstancesBatchOperation: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceCancellationBatchOperationRequest** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceCancellationBatchOperationRequest**](../Model/ProcessInstanceCancellationBatchOperationRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\BatchOperationCreatedResult**](../Model/BatchOperationCreatedResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `createProcessInstance()`

```php
createProcessInstance($processInstanceCreationInstruction): \Camunda\Orchestration\Api\Model\CreateProcessInstanceResult
```

Create process instance

Creates and starts an instance of the specified process. The process definition to use to create the instance can be specified either using its unique key (as returned by Deploy resources), or using the BPMN process id and a version. If only the process definition id is given, the latest ACTIVE version is used. If no ACTIVE version exists, the request is rejected as not found.  Waits for the completion of the process instance before returning a result when awaitCompletion is enabled.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceCreationInstruction = {"processDefinitionKey":"12345543223453245","variables":{}}; // \Camunda\Orchestration\Api\Model\ProcessInstanceCreationInstruction

try {
    $result = $apiInstance->createProcessInstance($processInstanceCreationInstruction);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->createProcessInstance: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceCreationInstruction** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceCreationInstruction**](../Model/ProcessInstanceCreationInstruction.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\CreateProcessInstanceResult**](../Model/CreateProcessInstanceResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `deleteProcessInstance()`

```php
deleteProcessInstance($processInstanceKey, $deleteProcessInstanceRequest)
```

Delete process instance

Deletes a process instance. Only instances that are completed or terminated can be deleted.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceKey = 'processInstanceKey_example'; // string | The key of the process instance to delete.
$deleteProcessInstanceRequest = new \Camunda\Orchestration\Api\Model\DeleteProcessInstanceRequest(); // \Camunda\Orchestration\Api\Model\DeleteProcessInstanceRequest

try {
    $apiInstance->deleteProcessInstance($processInstanceKey, $deleteProcessInstanceRequest);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->deleteProcessInstance: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceKey** | **string**| The key of the process instance to delete. | |
| **deleteProcessInstanceRequest** | [**\Camunda\Orchestration\Api\Model\DeleteProcessInstanceRequest**](../Model/DeleteProcessInstanceRequest.md)|  | [optional] |

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

## `deleteProcessInstancesBatchOperation()`

```php
deleteProcessInstancesBatchOperation($processInstanceDeletionBatchOperationRequest): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult
```

Delete process instances (batch)

Delete multiple process instances. This will delete the historic data from secondary storage. Only process instances in a final state (COMPLETED or TERMINATED) can be deleted. This is done asynchronously, the progress can be tracked using the batchOperationKey from the response and the batch operation status endpoint (/batch-operations/{batchOperationKey}).

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceDeletionBatchOperationRequest = new \Camunda\Orchestration\Api\Model\ProcessInstanceDeletionBatchOperationRequest(); // \Camunda\Orchestration\Api\Model\ProcessInstanceDeletionBatchOperationRequest

try {
    $result = $apiInstance->deleteProcessInstancesBatchOperation($processInstanceDeletionBatchOperationRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->deleteProcessInstancesBatchOperation: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceDeletionBatchOperationRequest** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceDeletionBatchOperationRequest**](../Model/ProcessInstanceDeletionBatchOperationRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\BatchOperationCreatedResult**](../Model/BatchOperationCreatedResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getProcessInstance()`

```php
getProcessInstance($processInstanceKey): \Camunda\Orchestration\Api\Model\ProcessInstanceResult
```

Get process instance

Get the process instance by the process instance key.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceKey = 'processInstanceKey_example'; // string | The process instance key.

try {
    $result = $apiInstance->getProcessInstance($processInstanceKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->getProcessInstance: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceKey** | **string**| The process instance key. | |

### Return type

[**\Camunda\Orchestration\Api\Model\ProcessInstanceResult**](../Model/ProcessInstanceResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getProcessInstanceCallHierarchy()`

```php
getProcessInstanceCallHierarchy($processInstanceKey): \Camunda\Orchestration\Api\Model\ProcessInstanceCallHierarchyEntry[]
```

Get call hierarchy

Returns the call hierarchy for a given process instance, showing its ancestry up to the root instance.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceKey = 'processInstanceKey_example'; // string | The key of the process instance to fetch the hierarchy for.

try {
    $result = $apiInstance->getProcessInstanceCallHierarchy($processInstanceKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->getProcessInstanceCallHierarchy: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceKey** | **string**| The key of the process instance to fetch the hierarchy for. | |

### Return type

[**\Camunda\Orchestration\Api\Model\ProcessInstanceCallHierarchyEntry[]**](../Model/ProcessInstanceCallHierarchyEntry.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getProcessInstanceSequenceFlows()`

```php
getProcessInstanceSequenceFlows($processInstanceKey): \Camunda\Orchestration\Api\Model\ProcessInstanceSequenceFlowsQueryResult
```

Get sequence flows

Get sequence flows taken by the process instance.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceKey = 'processInstanceKey_example'; // string | The assigned key of the process instance, which acts as a unique identifier for this process instance.

try {
    $result = $apiInstance->getProcessInstanceSequenceFlows($processInstanceKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->getProcessInstanceSequenceFlows: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceKey** | **string**| The assigned key of the process instance, which acts as a unique identifier for this process instance. | |

### Return type

[**\Camunda\Orchestration\Api\Model\ProcessInstanceSequenceFlowsQueryResult**](../Model/ProcessInstanceSequenceFlowsQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getProcessInstanceStatistics()`

```php
getProcessInstanceStatistics($processInstanceKey): \Camunda\Orchestration\Api\Model\ProcessInstanceElementStatisticsQueryResult
```

Get element instance statistics

Get statistics about elements by the process instance key.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceKey = 'processInstanceKey_example'; // string | The assigned key of the process instance, which acts as a unique identifier for this process instance.

try {
    $result = $apiInstance->getProcessInstanceStatistics($processInstanceKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->getProcessInstanceStatistics: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceKey** | **string**| The assigned key of the process instance, which acts as a unique identifier for this process instance. | |

### Return type

[**\Camunda\Orchestration\Api\Model\ProcessInstanceElementStatisticsQueryResult**](../Model/ProcessInstanceElementStatisticsQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getProcessInstanceWaitStateStatistics()`

```php
getProcessInstanceWaitStateStatistics($processInstanceKey): \Camunda\Orchestration\Api\Model\ProcessInstanceWaitStateStatisticsQueryResult
```

Get wait state statistics

Get statistics about waiting element instances by the process instance key, grouped by element id.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceKey = 'processInstanceKey_example'; // string | The assigned key of the process instance, which acts as a unique identifier for this process instance.

try {
    $result = $apiInstance->getProcessInstanceWaitStateStatistics($processInstanceKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->getProcessInstanceWaitStateStatistics: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceKey** | **string**| The assigned key of the process instance, which acts as a unique identifier for this process instance. | |

### Return type

[**\Camunda\Orchestration\Api\Model\ProcessInstanceWaitStateStatisticsQueryResult**](../Model/ProcessInstanceWaitStateStatisticsQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `migrateProcessInstance()`

```php
migrateProcessInstance($processInstanceKey, $processInstanceMigrationInstruction)
```

Migrate process instance

Migrates a process instance to a new process definition. This request can contain multiple mapping instructions to define mapping between the active process instance's elements and target process definition elements.  Use this to upgrade a process instance to a new version of a process or to a different process definition, e.g. to keep your running instances up-to-date with the latest process improvements.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceKey = 'processInstanceKey_example'; // string | The key of the process instance that should be migrated.
$processInstanceMigrationInstruction = new \Camunda\Orchestration\Api\Model\ProcessInstanceMigrationInstruction(); // \Camunda\Orchestration\Api\Model\ProcessInstanceMigrationInstruction

try {
    $apiInstance->migrateProcessInstance($processInstanceKey, $processInstanceMigrationInstruction);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->migrateProcessInstance: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceKey** | **string**| The key of the process instance that should be migrated. | |
| **processInstanceMigrationInstruction** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceMigrationInstruction**](../Model/ProcessInstanceMigrationInstruction.md)|  | |

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

## `migrateProcessInstancesBatchOperation()`

```php
migrateProcessInstancesBatchOperation($processInstanceMigrationBatchOperationRequest): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult
```

Migrate process instances (batch)

Migrate multiple process instances. Since only process instances with ACTIVE state can be migrated, any given filters for state are ignored and overridden during this batch operation. This is done asynchronously, the progress can be tracked using the batchOperationKey from the response and the batch operation status endpoint (/batch-operations/{batchOperationKey}).

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceMigrationBatchOperationRequest = new \Camunda\Orchestration\Api\Model\ProcessInstanceMigrationBatchOperationRequest(); // \Camunda\Orchestration\Api\Model\ProcessInstanceMigrationBatchOperationRequest

try {
    $result = $apiInstance->migrateProcessInstancesBatchOperation($processInstanceMigrationBatchOperationRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->migrateProcessInstancesBatchOperation: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceMigrationBatchOperationRequest** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceMigrationBatchOperationRequest**](../Model/ProcessInstanceMigrationBatchOperationRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\BatchOperationCreatedResult**](../Model/BatchOperationCreatedResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `modifyProcessInstance()`

```php
modifyProcessInstance($processInstanceKey, $processInstanceModificationInstruction)
```

Modify process instance

Modifies a running process instance. This request can contain multiple instructions to activate an element of the process or to terminate an active instance of an element.  Use this to repair a process instance that is stuck on an element or took an unintended path. For example, because an external system is not available or doesn't respond as expected.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceKey = 'processInstanceKey_example'; // string | The key of the process instance that should be modified.
$processInstanceModificationInstruction = new \Camunda\Orchestration\Api\Model\ProcessInstanceModificationInstruction(); // \Camunda\Orchestration\Api\Model\ProcessInstanceModificationInstruction

try {
    $apiInstance->modifyProcessInstance($processInstanceKey, $processInstanceModificationInstruction);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->modifyProcessInstance: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceKey** | **string**| The key of the process instance that should be modified. | |
| **processInstanceModificationInstruction** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceModificationInstruction**](../Model/ProcessInstanceModificationInstruction.md)|  | |

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

## `modifyProcessInstancesBatchOperation()`

```php
modifyProcessInstancesBatchOperation($processInstanceModificationBatchOperationRequest): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult
```

Modify process instances (batch)

Modify multiple process instances. Since only process instances with ACTIVE state can be modified, any given filters for state are ignored and overridden during this batch operation. In contrast to single modification operation, it is not possible to add variable instructions or modify by element key. It is only possible to use the element id of the source and target. This is done asynchronously, the progress can be tracked using the batchOperationKey from the response and the batch operation status endpoint (/batch-operations/{batchOperationKey}).

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceModificationBatchOperationRequest = new \Camunda\Orchestration\Api\Model\ProcessInstanceModificationBatchOperationRequest(); // \Camunda\Orchestration\Api\Model\ProcessInstanceModificationBatchOperationRequest

try {
    $result = $apiInstance->modifyProcessInstancesBatchOperation($processInstanceModificationBatchOperationRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->modifyProcessInstancesBatchOperation: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceModificationBatchOperationRequest** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceModificationBatchOperationRequest**](../Model/ProcessInstanceModificationBatchOperationRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\BatchOperationCreatedResult**](../Model/BatchOperationCreatedResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `resolveIncidentsBatchOperation()`

```php
resolveIncidentsBatchOperation($processInstanceIncidentResolutionBatchOperationRequest): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult
```

Resolve related incidents (batch)

Resolves multiple instances of process instances. Since only process instances with ACTIVE state can have unresolved incidents, any given filters for state are ignored and overridden during this batch operation. This is done asynchronously, the progress can be tracked using the batchOperationKey from the response and the batch operation status endpoint (/batch-operations/{batchOperationKey}).

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceIncidentResolutionBatchOperationRequest = new \Camunda\Orchestration\Api\Model\ProcessInstanceIncidentResolutionBatchOperationRequest(); // \Camunda\Orchestration\Api\Model\ProcessInstanceIncidentResolutionBatchOperationRequest

try {
    $result = $apiInstance->resolveIncidentsBatchOperation($processInstanceIncidentResolutionBatchOperationRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->resolveIncidentsBatchOperation: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceIncidentResolutionBatchOperationRequest** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceIncidentResolutionBatchOperationRequest**](../Model/ProcessInstanceIncidentResolutionBatchOperationRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\BatchOperationCreatedResult**](../Model/BatchOperationCreatedResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `resolveProcessInstanceIncidents()`

```php
resolveProcessInstanceIncidents($processInstanceKey): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult
```

Resolve related incidents

Creates a batch operation to resolve multiple incidents of a process instance.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceKey = 'processInstanceKey_example'; // string | The key of the process instance to resolve incidents for.

try {
    $result = $apiInstance->resolveProcessInstanceIncidents($processInstanceKey);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->resolveProcessInstanceIncidents: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceKey** | **string**| The key of the process instance to resolve incidents for. | |

### Return type

[**\Camunda\Orchestration\Api\Model\BatchOperationCreatedResult**](../Model/BatchOperationCreatedResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `resumeProcessInstance()`

```php
resumeProcessInstance($processInstanceKey, $resumeProcessInstanceRequest)
```

Resume process instance

Resumes a suspended process instance, returning it to the ACTIVE state and continuing processing. Only process instances in the SUSPENDED state can be resumed. A child process instance can be resumed independently of its parent or root process instance; resumption does not cascade to or from related instances.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceKey = 'processInstanceKey_example'; // string | The key of the process instance to resume.
$resumeProcessInstanceRequest = new \Camunda\Orchestration\Api\Model\ResumeProcessInstanceRequest(); // \Camunda\Orchestration\Api\Model\ResumeProcessInstanceRequest

try {
    $apiInstance->resumeProcessInstance($processInstanceKey, $resumeProcessInstanceRequest);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->resumeProcessInstance: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceKey** | **string**| The key of the process instance to resume. | |
| **resumeProcessInstanceRequest** | [**\Camunda\Orchestration\Api\Model\ResumeProcessInstanceRequest**](../Model/ResumeProcessInstanceRequest.md)|  | [optional] |

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

## `resumeProcessInstancesBatchOperation()`

```php
resumeProcessInstancesBatchOperation($processInstanceResumptionBatchOperationRequest): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult
```

Resume process instances (batch)

Resumes multiple suspended process instances. Any given filter for state or parentProcessInstanceKey is ignored and overridden, as only SUSPENDED process instances can be resumed and resumption does not cascade between parent and child instances, so child instances are resumed independently of their parent or root instance. This is done asynchronously, the progress can be tracked using the batchOperationKey from the response and the batch operation status endpoint (/batch-operations/{batchOperationKey}).

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceResumptionBatchOperationRequest = new \Camunda\Orchestration\Api\Model\ProcessInstanceResumptionBatchOperationRequest(); // \Camunda\Orchestration\Api\Model\ProcessInstanceResumptionBatchOperationRequest

try {
    $result = $apiInstance->resumeProcessInstancesBatchOperation($processInstanceResumptionBatchOperationRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->resumeProcessInstancesBatchOperation: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceResumptionBatchOperationRequest** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceResumptionBatchOperationRequest**](../Model/ProcessInstanceResumptionBatchOperationRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\BatchOperationCreatedResult**](../Model/BatchOperationCreatedResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchProcessInstanceIncidents()`

```php
searchProcessInstanceIncidents($processInstanceKey, $incidentSearchQuery): \Camunda\Orchestration\Api\Model\IncidentSearchQueryResult
```

Search related incidents

Search for incidents caused by the process instance or any of its called process or decision instances.  Although the `processInstanceKey` is provided as a path parameter to indicate the root process instance, you may also include a `processInstanceKey` within the filter object to narrow results to specific child process instances. This is useful, for example, if you want to isolate incidents associated with subprocesses or called processes under the root instance while excluding incidents directly tied to the root.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceKey = 'processInstanceKey_example'; // string | The assigned key of the process instance, which acts as a unique identifier for this process instance.
$incidentSearchQuery = new \Camunda\Orchestration\Api\Model\IncidentSearchQuery(); // \Camunda\Orchestration\Api\Model\IncidentSearchQuery

try {
    $result = $apiInstance->searchProcessInstanceIncidents($processInstanceKey, $incidentSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->searchProcessInstanceIncidents: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceKey** | **string**| The assigned key of the process instance, which acts as a unique identifier for this process instance. | |
| **incidentSearchQuery** | [**\Camunda\Orchestration\Api\Model\IncidentSearchQuery**](../Model/IncidentSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\IncidentSearchQueryResult**](../Model/IncidentSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchProcessInstances()`

```php
searchProcessInstances($processInstanceSearchQuery): \Camunda\Orchestration\Api\Model\ProcessInstanceSearchQueryResult
```

Search process instances

Search for process instances based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceSearchQuery = new \Camunda\Orchestration\Api\Model\ProcessInstanceSearchQuery(); // \Camunda\Orchestration\Api\Model\ProcessInstanceSearchQuery

try {
    $result = $apiInstance->searchProcessInstances($processInstanceSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->searchProcessInstances: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceSearchQuery** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceSearchQuery**](../Model/ProcessInstanceSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\ProcessInstanceSearchQueryResult**](../Model/ProcessInstanceSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `suspendProcessInstance()`

```php
suspendProcessInstance($processInstanceKey, $suspendProcessInstanceRequest)
```

Suspend process instance

Suspends a running process instance, pausing further processing until it is resumed. Only process instances in the ACTIVE state can be suspended. A child process instance can be suspended independently of its parent or root process instance; suspension does not cascade to or from related instances.

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceKey = 'processInstanceKey_example'; // string | The key of the process instance to suspend.
$suspendProcessInstanceRequest = new \Camunda\Orchestration\Api\Model\SuspendProcessInstanceRequest(); // \Camunda\Orchestration\Api\Model\SuspendProcessInstanceRequest

try {
    $apiInstance->suspendProcessInstance($processInstanceKey, $suspendProcessInstanceRequest);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->suspendProcessInstance: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceKey** | **string**| The key of the process instance to suspend. | |
| **suspendProcessInstanceRequest** | [**\Camunda\Orchestration\Api\Model\SuspendProcessInstanceRequest**](../Model/SuspendProcessInstanceRequest.md)|  | [optional] |

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

## `suspendProcessInstancesBatchOperation()`

```php
suspendProcessInstancesBatchOperation($processInstanceSuspensionBatchOperationRequest): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult
```

Suspend process instances (batch)

Suspends multiple running process instances. Any given filter for state or parentProcessInstanceKey is ignored and overridden, as only ACTIVE process instances can be suspended and suspension does not cascade between parent and child instances, so child instances are suspended independently of their parent or root instance. This is done asynchronously, the progress can be tracked using the batchOperationKey from the response and the batch operation status endpoint (/batch-operations/{batchOperationKey}).

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


$apiInstance = new Camunda\Orchestration\Api\Api\ProcessInstanceApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$processInstanceSuspensionBatchOperationRequest = new \Camunda\Orchestration\Api\Model\ProcessInstanceSuspensionBatchOperationRequest(); // \Camunda\Orchestration\Api\Model\ProcessInstanceSuspensionBatchOperationRequest

try {
    $result = $apiInstance->suspendProcessInstancesBatchOperation($processInstanceSuspensionBatchOperationRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessInstanceApi->suspendProcessInstancesBatchOperation: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **processInstanceSuspensionBatchOperationRequest** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceSuspensionBatchOperationRequest**](../Model/ProcessInstanceSuspensionBatchOperationRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\BatchOperationCreatedResult**](../Model/BatchOperationCreatedResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
