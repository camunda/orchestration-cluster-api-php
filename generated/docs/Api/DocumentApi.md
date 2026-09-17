# Camunda\Orchestration\Api\DocumentApi

All URIs are relative to http://localhost:8080/v2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**createDocument()**](DocumentApi.md#createDocument) | **POST** /documents | Upload document |
| [**createDocumentLink()**](DocumentApi.md#createDocumentLink) | **POST** /documents/{documentId}/links | Create document link |
| [**createDocuments()**](DocumentApi.md#createDocuments) | **POST** /documents/batch | Upload multiple documents |
| [**deleteDocument()**](DocumentApi.md#deleteDocument) | **DELETE** /documents/{documentId} | Delete document |
| [**getDocument()**](DocumentApi.md#getDocument) | **GET** /documents/{documentId} | Download document |


## `createDocument()`

```php
createDocument($file, $storeId, $documentId, $metadata): \Camunda\Orchestration\Api\Model\DocumentReference
```

Upload document

Upload a document to the Camunda 8 cluster.  Note that this is currently supported for document stores of type: AWS, Azure, GCP, in-memory (non-production), local (non-production)

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


$apiInstance = new Camunda\Orchestration\Api\Api\DocumentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$file = '/path/to/file.txt'; // \SplFileObject
$storeId = 'storeId_example'; // string | The ID of the document store to upload the documents to. Currently, only a single document store is supported per cluster. However, this attribute is included to allow for potential future support of multiple document stores.
$documentId = 'documentId_example'; // string | The ID of the document to upload. If not provided, a new ID will be generated. Specifying an existing ID will result in an error if the document already exists.
$metadata = new \Camunda\Orchestration\Api\Model\DocumentMetadata(); // \Camunda\Orchestration\Api\Model\DocumentMetadata

try {
    $result = $apiInstance->createDocument($file, $storeId, $documentId, $metadata);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DocumentApi->createDocument: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **file** | **\SplFileObject****\SplFileObject**|  | |
| **storeId** | **string**| The ID of the document store to upload the documents to. Currently, only a single document store is supported per cluster. However, this attribute is included to allow for potential future support of multiple document stores. | [optional] |
| **documentId** | **string**| The ID of the document to upload. If not provided, a new ID will be generated. Specifying an existing ID will result in an error if the document already exists. | [optional] |
| **metadata** | [**\Camunda\Orchestration\Api\Model\DocumentMetadata**](../Model/DocumentMetadata.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\DocumentReference**](../Model/DocumentReference.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `multipart/form-data`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `createDocumentLink()`

```php
createDocumentLink($documentId, $storeId, $contentHash, $documentLinkRequest): \Camunda\Orchestration\Api\Model\DocumentLink
```

Create document link

Create a link to a document in the Camunda 8 cluster.  Note that this is currently supported for document stores of type: AWS, Azure, GCP

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


$apiInstance = new Camunda\Orchestration\Api\Api\DocumentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$documentId = 'documentId_example'; // string | The ID of the document to link.
$storeId = 'storeId_example'; // string | The ID of the document store where the document is located.
$contentHash = 'contentHash_example'; // string | The hash of the document content that was computed by the document store during upload. The hash is part of the document reference that is returned when uploading a document. If the client fails to provide the correct hash, the request will be rejected.
$documentLinkRequest = new \Camunda\Orchestration\Api\Model\DocumentLinkRequest(); // \Camunda\Orchestration\Api\Model\DocumentLinkRequest

try {
    $result = $apiInstance->createDocumentLink($documentId, $storeId, $contentHash, $documentLinkRequest);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DocumentApi->createDocumentLink: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **documentId** | **string**| The ID of the document to link. | |
| **storeId** | **string**| The ID of the document store where the document is located. | [optional] |
| **contentHash** | **string**| The hash of the document content that was computed by the document store during upload. The hash is part of the document reference that is returned when uploading a document. If the client fails to provide the correct hash, the request will be rejected. | [optional] |
| **documentLinkRequest** | [**\Camunda\Orchestration\Api\Model\DocumentLinkRequest**](../Model/DocumentLinkRequest.md)|  | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\DocumentLink**](../Model/DocumentLink.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `createDocuments()`

```php
createDocuments($files, $storeId, $metadataList): \Camunda\Orchestration\Api\Model\DocumentCreationBatchResponse
```

Upload multiple documents

Upload multiple documents to the Camunda 8 cluster.  The caller must provide a file name for each document, which will be used in case of a multi-status response to identify which documents failed to upload. The file name can be provided in the `Content-Disposition` header of the file part or in the `fileName` field of the metadata. You can add a parallel array of metadata objects. These are matched with the files based on index, and must have the same length as the files array. To pass homogenous metadata for all files, spread the metadata over the metadata array. A filename value provided explicitly via the metadata array in the request overrides the `Content-Disposition` header of the file part.  In case of a multi-status response, the response body will contain a list of `DocumentBatchProblemDetail` objects, each of which contains the file name of the document that failed to upload and the reason for the failure. The client can choose to retry the whole batch or individual documents based on the response.  Note that this is currently supported for document stores of type: AWS, Azure, GCP, in-memory (non-production), local (non-production)

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


$apiInstance = new Camunda\Orchestration\Api\Api\DocumentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$files = array('/path/to/file.txt'); // \SplFileObject[] | The documents to upload.
$storeId = 'storeId_example'; // string | The ID of the document store to upload the documents to. Currently, only a single document store is supported per cluster. However, this attribute is included to allow for potential future support of multiple document stores.
$metadataList = array(new \Camunda\Orchestration\Api\Model\\Camunda\Orchestration\Api\Model\DocumentMetadata()); // \Camunda\Orchestration\Api\Model\DocumentMetadata[] | Optional JSON array of metadata object whose index aligns with each file entry. The metadata array must have the same length as the files array.

try {
    $result = $apiInstance->createDocuments($files, $storeId, $metadataList);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DocumentApi->createDocuments: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **files** | **\SplFileObject[]**| The documents to upload. | |
| **storeId** | **string**| The ID of the document store to upload the documents to. Currently, only a single document store is supported per cluster. However, this attribute is included to allow for potential future support of multiple document stores. | [optional] |
| **metadataList** | [**\Camunda\Orchestration\Api\Model\DocumentMetadata[]**](../Model/\Camunda\Orchestration\Api\Model\DocumentMetadata.md)| Optional JSON array of metadata object whose index aligns with each file entry. The metadata array must have the same length as the files array. | [optional] |

### Return type

[**\Camunda\Orchestration\Api\Model\DocumentCreationBatchResponse**](../Model/DocumentCreationBatchResponse.md)

### Authorization

[basicAuth](../../README.md#basicAuth), [bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `multipart/form-data`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `deleteDocument()`

```php
deleteDocument($documentId, $storeId)
```

Delete document

Delete a document from the Camunda 8 cluster.  Note that this is currently supported for document stores of type: AWS, Azure, GCP, in-memory (non-production), local (non-production)

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


$apiInstance = new Camunda\Orchestration\Api\Api\DocumentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$documentId = 'documentId_example'; // string | The ID of the document to delete.
$storeId = 'storeId_example'; // string | The ID of the document store to delete the document from.

try {
    $apiInstance->deleteDocument($documentId, $storeId);
} catch (Exception $e) {
    echo 'Exception when calling DocumentApi->deleteDocument: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **documentId** | **string**| The ID of the document to delete. | |
| **storeId** | **string**| The ID of the document store to delete the document from. | [optional] |

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

## `getDocument()`

```php
getDocument($documentId, $storeId, $contentHash): \SplFileObject
```

Download document

Download a document from the Camunda 8 cluster.  Note that this is currently supported for document stores of type: AWS, Azure, GCP, in-memory (non-production), local (non-production)

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


$apiInstance = new Camunda\Orchestration\Api\Api\DocumentApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$documentId = 'documentId_example'; // string | The ID of the document to download.
$storeId = 'storeId_example'; // string | The ID of the document store to download the document from.
$contentHash = 'contentHash_example'; // string | The hash of the document content that was computed by the document store during upload. The hash is part of the document reference that is returned when uploading a document. If the client fails to provide the correct hash, the request will be rejected.

try {
    $result = $apiInstance->getDocument($documentId, $storeId, $contentHash);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DocumentApi->getDocument: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **documentId** | **string**| The ID of the document to download. | |
| **storeId** | **string**| The ID of the document store to download the document from. | [optional] |
| **contentHash** | **string**| The hash of the document content that was computed by the document store during upload. The hash is part of the document reference that is returned when uploading a document. If the client fails to provide the correct hash, the request will be rejected. | [optional] |

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
