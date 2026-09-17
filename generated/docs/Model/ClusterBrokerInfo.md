# ClusterBrokerInfo

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**brokerId** | **string** | The unique (within a cluster) broker identifier. When the cluster is not zoned, then it&#39;s a string that represents the nodeId (an integer). When the cluster is zoned, instead, it&#39;s of the form \&quot;$zoneName_$nodeId\&quot;, providing uniqueness even across zones. |
**host** | **string** | The hostname for reaching the broker. |
**port** | **int** | The port for reaching the broker. |
**version** | **string** | The broker version. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
