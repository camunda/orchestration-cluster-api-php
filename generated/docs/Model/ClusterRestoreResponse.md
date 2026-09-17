# ClusterRestoreResponse

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**changeId** | **string** | The ID of the cluster change that was triggered by the request. |
**plannedChanges** | [**\Camunda\Orchestration\Api\Model\ClusterRestorePlannedChange[]**](ClusterRestorePlannedChange.md) | The operations that will be applied to complete the restore, grouped by the physical tenant they belong to. Groups are restored in parallel; the operations within a group are applied in the given order. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
