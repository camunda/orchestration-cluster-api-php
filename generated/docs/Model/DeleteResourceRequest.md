# DeleteResourceRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**operationReference** | **int** | A reference key chosen by the user that will be part of all records resulting from this operation. Must be &gt; 0 if provided. | [optional]
**deleteHistory** | **bool** | Indicates if the historic data associated with the resource should also be deleted asynchronously.  This flag is effective for process definitions and decision requirements definitions. For other resource types (forms, generic resources) it is ignored and no history is deleted. For a decision requirements definition the &#x60;batchOperation&#x60; field in the response carries the created batch operation. For a process definition the history is deleted as part of the definition&#39;s draining/deletion lifecycle and no batch operation is returned. | [optional] [default to false]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
