# HistoryBackupSnapshotInfo

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**snapshotName** | **string** | The name of the snapshot. | [readonly]
**state** | **string** | The state of the snapshot, reported verbatim by the secondary storage (for example &#39;SUCCESS&#39;, &#39;IN_PROGRESS&#39; or &#39;PARTIAL&#39;). Deliberately not a closed set: Elasticsearch and OpenSearch report different vocabularies. Not reported when the backup was listed without snapshot detail. | [readonly]
**startTime** | **\DateTime** | The timestamp at which the snapshot was started. Not reported when the backup was listed without snapshot detail. | [readonly]
**failures** | **string[]** | The failures reported for this snapshot. Empty if there were none. | [readonly]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
