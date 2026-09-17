# ClusterRestoreRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**from** | **\DateTime** | The start of the time range to restore from, as an ISO 8601 timestamp. | [optional]
**to** | **\DateTime** | The end of the time range to restore from, as an ISO 8601 timestamp. | [optional]
**backupIds** | **int[]** | The IDs of the backups to restore from, one per partition. | [optional]
**overrides** | [**array<string,\Camunda\Orchestration\Api\Model\RestoreRequest>**](RestoreRequest.md) | The backup selection to apply to individual physical tenants, keyed by physical tenant id. Only allowed for a cluster-wide restore, that is when no &#x60;physicalTenantId&#x60; parameter is given. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
