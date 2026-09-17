# Camunda\Orchestration\Api\JobApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**activateJobs()**](JobApi.md#activateJobs) | **POST** /jobs/activation | Activate jobs |
| [**completeJob()**](JobApi.md#completeJob) | **POST** /jobs/{jobKey}/completion | Complete job |
| [**failJob()**](JobApi.md#failJob) | **POST** /jobs/{jobKey}/failure | Fail job |
| [**getGlobalJobStatistics()**](JobApi.md#getGlobalJobStatistics) | **GET** /jobs/statistics/global | Global job statistics |
| [**getJobErrorStatistics()**](JobApi.md#getJobErrorStatistics) | **POST** /jobs/statistics/errors | Get error metrics for a job type |
| [**getJobTimeSeriesStatistics()**](JobApi.md#getJobTimeSeriesStatistics) | **POST** /jobs/statistics/time-series | Get time-series metrics for a job type |
| [**getJobTypeStatistics()**](JobApi.md#getJobTypeStatistics) | **POST** /jobs/statistics/by-types | Get job statistics by type |
| [**getJobWorkerStatistics()**](JobApi.md#getJobWorkerStatistics) | **POST** /jobs/statistics/by-workers | Get job statistics by worker |
| [**searchJobs()**](JobApi.md#searchJobs) | **POST** /jobs/search | Search jobs |
| [**throwJobError()**](JobApi.md#throwJobError) | **POST** /jobs/{jobKey}/error | Throw error for job |
| [**updateJob()**](JobApi.md#updateJob) | **PATCH** /jobs/{jobKey} | Update job |
| [**updateJobsBatchOperation()**](JobApi.md#updateJobsBatchOperation) | **POST** /jobs/batch-update | Update jobs (batch) |


## `activateJobs()`

```php
activateJobs($jobActivationRequest): \Camunda\Orchestration\Api\Model\JobActivationResult
```

Activate jobs

Iterate through all known partitions and activate jobs up to the requested maximum.

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


$apiInstance = new Camunda\Orchestration\Api\Api\JobApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$jobActivationRequest = new \Camunda\Orchestration\Api\Model\JobActivationRequest(); // \Camunda\Orchestration\Api\Model\JobActivationRequest

try {
    $result = $apiInstance->activateJobs($jobActivationRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling JobApi->activateJobs: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **jobActivationRequest** | [**\Camunda\Orchestration\Api\Model\JobActivationRequest**](../Model/JobActivationRequest.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\JobActivationResult**](../Model/JobActivationResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `completeJob()`

```php
completeJob($jobKey, $jobCompletionRequest)
```

Complete job

Complete a job with the given payload, which allows completing the associated service task.

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


$apiInstance = new Camunda\Orchestration\Api\Api\JobApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$jobKey = 'jobKey_example'; // string | The key of the job to complete.
$jobCompletionRequest = new \Camunda\Orchestration\Api\Model\JobCompletionRequest(); // \Camunda\Orchestration\Api\Model\JobCompletionRequest

try {
    $apiInstance->completeJob($jobKey, $jobCompletionRequest);
} catch (Exception $e) {
    echo 'Exception when calling JobApi->completeJob: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **jobKey** | **string**| The key of the job to complete. | |
| **jobCompletionRequest** | [**\Camunda\Orchestration\Api\Model\JobCompletionRequest**](../Model/JobCompletionRequest.md)|  | [optional] |

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

## `failJob()`

```php
failJob($jobKey, $jobFailRequest)
```

Fail job

Mark the job as failed.

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


$apiInstance = new Camunda\Orchestration\Api\Api\JobApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$jobKey = 'jobKey_example'; // string | The key of the job to fail.
$jobFailRequest = new \Camunda\Orchestration\Api\Model\JobFailRequest(); // \Camunda\Orchestration\Api\Model\JobFailRequest

try {
    $apiInstance->failJob($jobKey, $jobFailRequest);
} catch (Exception $e) {
    echo 'Exception when calling JobApi->failJob: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **jobKey** | **string**| The key of the job to fail. | |
| **jobFailRequest** | [**\Camunda\Orchestration\Api\Model\JobFailRequest**](../Model/JobFailRequest.md)|  | [optional] |

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

## `getGlobalJobStatistics()`

```php
getGlobalJobStatistics($from, $to, $jobType): \Camunda\Orchestration\Api\Model\GlobalJobStatisticsQueryResult
```

Global job statistics

Returns global aggregated counts for jobs. Filter by the creation time window (required) and optionally by jobType.

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


$apiInstance = new Camunda\Orchestration\Api\Api\JobApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$from = 2024-07-28T15:51:28.071Z; // \DateTime | Start of the time window to filter metrics. ISO 8601 date-time format.
$to = 2024-07-29T15:51:28.071Z; // \DateTime | End of the time window to filter metrics. ISO 8601 date-time format.
$jobType = fetch-customer-data; // string | Optional job type to limit the aggregation to a single job type.

try {
    $result = $apiInstance->getGlobalJobStatistics($from, $to, $jobType);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling JobApi->getGlobalJobStatistics: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **from** | **\DateTime**| Start of the time window to filter metrics. ISO 8601 date-time format. | |
| **to** | **\DateTime**| End of the time window to filter metrics. ISO 8601 date-time format. | |
| **jobType** | **string**| Optional job type to limit the aggregation to a single job type. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\GlobalJobStatisticsQueryResult**](../Model/GlobalJobStatisticsQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getJobErrorStatistics()`

```php
getJobErrorStatistics($jobErrorStatisticsQuery): \Camunda\Orchestration\Api\Model\JobErrorStatisticsQueryResult
```

Get error metrics for a job type

Returns aggregated metrics per error for the given jobType.

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


$apiInstance = new Camunda\Orchestration\Api\Api\JobApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$jobErrorStatisticsQuery = new \Camunda\Orchestration\Api\Model\JobErrorStatisticsQuery(); // \Camunda\Orchestration\Api\Model\JobErrorStatisticsQuery

try {
    $result = $apiInstance->getJobErrorStatistics($jobErrorStatisticsQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling JobApi->getJobErrorStatistics: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **jobErrorStatisticsQuery** | [**\Camunda\Orchestration\Api\Model\JobErrorStatisticsQuery**](../Model/JobErrorStatisticsQuery.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\JobErrorStatisticsQueryResult**](../Model/JobErrorStatisticsQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getJobTimeSeriesStatistics()`

```php
getJobTimeSeriesStatistics($jobTimeSeriesStatisticsQuery): \Camunda\Orchestration\Api\Model\JobTimeSeriesStatisticsQueryResult
```

Get time-series metrics for a job type

Returns a list of time-bucketed metrics ordered ascending by time. The `from` and `to` fields select the time window of interest. Each item in the response corresponds to one time bucket of the requested resolution.

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


$apiInstance = new Camunda\Orchestration\Api\Api\JobApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$jobTimeSeriesStatisticsQuery = new \Camunda\Orchestration\Api\Model\JobTimeSeriesStatisticsQuery(); // \Camunda\Orchestration\Api\Model\JobTimeSeriesStatisticsQuery

try {
    $result = $apiInstance->getJobTimeSeriesStatistics($jobTimeSeriesStatisticsQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling JobApi->getJobTimeSeriesStatistics: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **jobTimeSeriesStatisticsQuery** | [**\Camunda\Orchestration\Api\Model\JobTimeSeriesStatisticsQuery**](../Model/JobTimeSeriesStatisticsQuery.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\JobTimeSeriesStatisticsQueryResult**](../Model/JobTimeSeriesStatisticsQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getJobTypeStatistics()`

```php
getJobTypeStatistics($jobTypeStatisticsQuery): \Camunda\Orchestration\Api\Model\JobTypeStatisticsQueryResult
```

Get job statistics by type

Get statistics about jobs, grouped by job type.

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


$apiInstance = new Camunda\Orchestration\Api\Api\JobApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$jobTypeStatisticsQuery = new \Camunda\Orchestration\Api\Model\JobTypeStatisticsQuery(); // \Camunda\Orchestration\Api\Model\JobTypeStatisticsQuery

try {
    $result = $apiInstance->getJobTypeStatistics($jobTypeStatisticsQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling JobApi->getJobTypeStatistics: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **jobTypeStatisticsQuery** | [**\Camunda\Orchestration\Api\Model\JobTypeStatisticsQuery**](../Model/JobTypeStatisticsQuery.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\JobTypeStatisticsQueryResult**](../Model/JobTypeStatisticsQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getJobWorkerStatistics()`

```php
getJobWorkerStatistics($jobWorkerStatisticsQuery): \Camunda\Orchestration\Api\Model\JobWorkerStatisticsQueryResult
```

Get job statistics by worker

Get statistics about jobs, grouped by worker, for a given job type.

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


$apiInstance = new Camunda\Orchestration\Api\Api\JobApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$jobWorkerStatisticsQuery = new \Camunda\Orchestration\Api\Model\JobWorkerStatisticsQuery(); // \Camunda\Orchestration\Api\Model\JobWorkerStatisticsQuery

try {
    $result = $apiInstance->getJobWorkerStatistics($jobWorkerStatisticsQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling JobApi->getJobWorkerStatistics: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **jobWorkerStatisticsQuery** | [**\Camunda\Orchestration\Api\Model\JobWorkerStatisticsQuery**](../Model/JobWorkerStatisticsQuery.md)|  | |

### Return type

[**\Camunda\Orchestration\Api\Model\JobWorkerStatisticsQueryResult**](../Model/JobWorkerStatisticsQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `searchJobs()`

```php
searchJobs($jobSearchQuery): \Camunda\Orchestration\Api\Model\JobSearchQueryResult
```

Search jobs

Search for jobs based on given criteria.

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


$apiInstance = new Camunda\Orchestration\Api\Api\JobApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$jobSearchQuery = new \Camunda\Orchestration\Api\Model\JobSearchQuery(); // \Camunda\Orchestration\Api\Model\JobSearchQuery

try {
    $result = $apiInstance->searchJobs($jobSearchQuery);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling JobApi->searchJobs: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **jobSearchQuery** | [**\Camunda\Orchestration\Api\Model\JobSearchQuery**](../Model/JobSearchQuery.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\JobSearchQueryResult**](../Model/JobSearchQueryResult.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `throwJobError()`

```php
throwJobError($jobKey, $jobErrorRequest)
```

Throw error for job

Reports a business error (i.e. non-technical) that occurs while processing a job.

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


$apiInstance = new Camunda\Orchestration\Api\Api\JobApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$jobKey = 'jobKey_example'; // string | The key of the job.
$jobErrorRequest = new \Camunda\Orchestration\Api\Model\JobErrorRequest(); // \Camunda\Orchestration\Api\Model\JobErrorRequest

try {
    $apiInstance->throwJobError($jobKey, $jobErrorRequest);
} catch (Exception $e) {
    echo 'Exception when calling JobApi->throwJobError: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **jobKey** | **string**| The key of the job. | |
| **jobErrorRequest** | [**\Camunda\Orchestration\Api\Model\JobErrorRequest**](../Model/JobErrorRequest.md)|  | |

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

## `updateJob()`

```php
updateJob($jobKey, $jobUpdateRequest)
```

Update job

Update a job with the given key.

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


$apiInstance = new Camunda\Orchestration\Api\Api\JobApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$jobKey = 'jobKey_example'; // string | The key of the job to update.
$jobUpdateRequest = new \Camunda\Orchestration\Api\Model\JobUpdateRequest(); // \Camunda\Orchestration\Api\Model\JobUpdateRequest

try {
    $apiInstance->updateJob($jobKey, $jobUpdateRequest);
} catch (Exception $e) {
    echo 'Exception when calling JobApi->updateJob: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **jobKey** | **string**| The key of the job to update. | |
| **jobUpdateRequest** | [**\Camunda\Orchestration\Api\Model\JobUpdateRequest**](../Model/JobUpdateRequest.md)|  | |

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

## `updateJobsBatchOperation()`

```php
updateJobsBatchOperation($jobBatchUpdateRequest): \Camunda\Orchestration\Api\Model\BatchOperationCreatedResult
```

Update jobs (batch)

Creates a batch operation to update jobs matching the given filter. At least one changeset field must be non-null. This is done asynchronously; the progress can be tracked using the batchOperationKey from the response and the batch operation status endpoint (/batch-operations/{batchOperationKey}).

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


$apiInstance = new Camunda\Orchestration\Api\Api\JobApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$jobBatchUpdateRequest = new \Camunda\Orchestration\Api\Model\JobBatchUpdateRequest(); // \Camunda\Orchestration\Api\Model\JobBatchUpdateRequest

try {
    $result = $apiInstance->updateJobsBatchOperation($jobBatchUpdateRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling JobApi->updateJobsBatchOperation: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **jobBatchUpdateRequest** | [**\Camunda\Orchestration\Api\Model\JobBatchUpdateRequest**](../Model/JobBatchUpdateRequest.md)|  | |

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
