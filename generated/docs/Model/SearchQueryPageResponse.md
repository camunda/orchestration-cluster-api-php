# SearchQueryPageResponse

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**totalItems** | **int** | Total items matching the criteria. |
**hasMoreTotalItems** | **bool** | Indicates whether the &#x60;totalItems&#x60; value has been capped due to system limits. When true, &#x60;totalItems&#x60; is a lower bound and the actual number of matching items is greater than the reported value. |
**startCursor** | **string** | The cursor value for getting the previous page of results. Use this in the &#x60;before&#x60; field of an ensuing request. |
**endCursor** | **string** | The cursor value for getting the next page of results. Use this in the &#x60;after&#x60; field of an ensuing request. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
