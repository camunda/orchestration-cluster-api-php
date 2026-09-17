# ClusterRuntimeBackupInfo

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**backupId** | **int** | The id of the backup. |
**state** | [**\Camunda\Orchestration\Api\Model\StateCode**](StateCode.md) | The state aggregated over every targeted physical tenant, whether the backup id was looked up directly or listed. A tenant holding nothing for this id counts as &#x60;DOES_NOT_EXIST&#x60;, so the aggregate is &#x60;INCOMPLETE&#x60; unless every targeted tenant holds the backup. |
**failureReason** | **string** | Reason for failure if the aggregated state is &#39;FAILED&#39;. |
**physicalTenants** | [**\Camunda\Orchestration\Api\Model\ClusterRuntimeBackupTenantInfo[]**](ClusterRuntimeBackupTenantInfo.md) | What each physical tenant reports for this backup id, ordered by physical tenant id. Every targeted tenant is listed, including the ones reporting &#x60;DOES_NOT_EXIST&#x60;. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
