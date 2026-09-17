# ClusterVariableSearchQueryFilterRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | Name of the cluster variable. | [optional]
**value** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The value of the cluster variable. | [optional]
**scope** | [**\Camunda\Orchestration\Api\Model\ClusterVariableScopeFilterProperty**](ClusterVariableScopeFilterProperty.md) | The scope filter for cluster variables. | [optional]
**tenantId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | Tenant ID of this variable. | [optional]
**isTruncated** | **bool** | Filter cluster variables by truncation status of their stored values. When true, returns only variables whose stored values are truncated (i.e., the value exceeds the storage size limit and is truncated in storage). When false, returns only variables with non-truncated stored values. This filter is based on the underlying storage characteristic, not the response format. | [optional]
**metadata** | [**array<string,\Camunda\Orchestration\Api\Model\AdvancedMetadataValueFilter>**](AdvancedMetadataValueFilter.md) | Filter by metadata entries. A map of metadata key to an advanced filter on that key&#39;s value. Metadata values are strings or numbers. | [optional]
**kind** | [**\Camunda\Orchestration\Api\Model\ClusterVariableKindFilterProperty**](ClusterVariableKindFilterProperty.md) | The kind filter for cluster variables. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
