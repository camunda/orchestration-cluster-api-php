# DecisionDefinitionFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**decisionDefinitionId** | **string** | The DMN ID of the decision definition. | [optional]
**name** | **string** | The DMN name of the decision definition. | [optional]
**isLatestVersion** | **bool** | Whether to only return the latest version of each decision definition. When using this filter, pagination functionality is limited, you can only paginate forward using &#x60;after&#x60; and &#x60;limit&#x60;. The response contains no &#x60;startCursor&#x60; in the &#x60;page&#x60;, and requests ignore the &#x60;from&#x60; and &#x60;before&#x60; in the &#x60;page&#x60;. | [optional]
**version** | **int** | The assigned version of the decision definition. | [optional]
**decisionRequirementsId** | **string** | the DMN ID of the decision requirements graph that the decision definition is part of. | [optional]
**tenantId** | **string** | The tenant ID of the decision definition. | [optional]
**decisionDefinitionKey** | **string** | The assigned key, which acts as a unique identifier for this decision definition. | [optional]
**decisionRequirementsKey** | **string** | The assigned key of the decision requirements graph that the decision definition is part of. | [optional]
**decisionRequirementsName** | **string** | The DMN name of the decision requirements that the decision definition is part of. | [optional]
**decisionRequirementsVersion** | **int** | The assigned version of the decision requirements that the decision definition is part of. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
