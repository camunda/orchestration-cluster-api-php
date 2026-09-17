# JobBatchUpdateRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**filter** | [**\Camunda\Orchestration\Api\Model\JobFilter**](JobFilter.md) | The job filter. At least one dimension must be set. |
**changeset** | [**\Camunda\Orchestration\Api\Model\JobChangeset**](JobChangeset.md) | The fields to update. At least one field must be non-null. |
**operationReference** | **int** | A reference key chosen by the user that will be part of all records resulting from this operation. Must be &gt; 0 if provided. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
