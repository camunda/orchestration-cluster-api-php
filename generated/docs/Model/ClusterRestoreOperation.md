# ClusterRestoreOperation

This model is a `oneOf` wrapper: a value is exactly one of the member types listed below.
It is never instantiated directly — use one of the concrete types.

## oneOf

- [**\Camunda\Orchestration\Api\Model\ClusterRestoreBrokerOperation**](ClusterRestoreBrokerOperation.md)
- [**\Camunda\Orchestration\Api\Model\ClusterRestorePartitionOperation**](ClusterRestorePartitionOperation.md)
- [**\Camunda\Orchestration\Api\Model\ClusterRestorePartitionRestoreOperation**](ClusterRestorePartitionRestoreOperation.md)
- [**\Camunda\Orchestration\Api\Model\ClusterRestoreModeChangeOperation**](ClusterRestoreModeChangeOperation.md)
- [**\Camunda\Orchestration\Api\Model\ClusterRestoreAwaitModeChangeOperation**](ClusterRestoreAwaitModeChangeOperation.md)

The concrete type is selected by the `operation` discriminator property.

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
