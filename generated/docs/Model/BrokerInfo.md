# BrokerInfo

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**nodeId** | **int** | The node ID for the broker. The uniqueness of this identifier depends if the cluster is zone-aware or not. - non zone-aware: (default) nodeId is unique across the cluster - zone-aware:  (opt-in) nodeId is unique only within its zone. If you are migrating to a zone aware cluster, you must use &#x60;brokerId&#x60; instead. This property is deprecated, as it&#39;s been replaced by &#x60;brokerId&#x60;. |
**brokerId** | **string** | The unique (within a cluster) broker identifier. When the cluster is not zoned, then it&#39;s a string that represents the nodeId (an integer). When the cluster is zoned, instead, it&#39;s of the form \&quot;$zoneName_$nodeId\&quot;, providing uniqueness even across zones. |
**host** | **string** | The hostname for reaching the broker. |
**port** | **int** | The port for reaching the broker. |
**partitions** | [**\Camunda\Orchestration\Api\Model\Partition[]**](Partition.md) | A list of partitions managed or replicated on this broker. |
**version** | **string** | The broker version. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
