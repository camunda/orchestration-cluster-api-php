# JobErrorStatisticsFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**from** | **\DateTime** | Start of the time window to filter metrics. ISO 8601 date-time format. |
**to** | **\DateTime** | End of the time window to filter metrics. ISO 8601 date-time format. |
**jobType** | **string** | Job type to return error metrics for. |
**errorCode** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | Optional error code filter with advanced search capabilities. | [optional]
**errorMessage** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | Optional error message filter with advanced search capabilities. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
