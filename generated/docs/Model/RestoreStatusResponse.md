# RestoreStatusResponse

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**status** | **string** | The overall status of the restore. |
**changeId** | **string** | The ID of the cluster change that performs the restore. |
**startedAt** | **\DateTime** | The time the restore started, as an ISO 8601 timestamp. |
**brokers** | [**\Camunda\Orchestration\Api\Model\RestoreBrokerStatus[]**](RestoreBrokerStatus.md) | The per-broker restore status. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
