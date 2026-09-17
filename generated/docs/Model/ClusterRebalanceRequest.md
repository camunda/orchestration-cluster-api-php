# ClusterRebalanceRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**replicationLagThreshold** | **int** | The highest replication lag (in bytes) that a desired leader may have for its transfer to be accepted. | [optional]
**replicationTimeout** | **string** | How long a partition may stay frozen waiting for its desired leader to catch up (as a positive ISO-8601 duration). | [optional]
**maxTransferAttempts** | **int** | How many times a current leader may prompt the desired leader to take over leadership before giving up. | [optional]
**leaderWaitTimeout** | **string** | How long the coordinator waits for a partition without a leader to acquire one before reporting &#x60;NO_LEADER&#x60; and moving on (as a positive ISO-8601 duration). | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
