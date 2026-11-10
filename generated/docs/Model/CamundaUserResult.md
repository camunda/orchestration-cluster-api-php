# CamundaUserResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**isAnonymous** | **bool** | Whether the caller has no identity. Only an unprotected API answers an anonymous caller; a protected API returns &#x60;401&#x60; instead. When &#x60;true&#x60;, &#x60;username&#x60;, &#x60;displayName&#x60; and &#x60;email&#x60; are &#x60;null&#x60; and the lists are empty. |
**username** | **string** | The username of the user. &#x60;null&#x60; when &#x60;isAnonymous&#x60; is &#x60;true&#x60;. |
**displayName** | **string** | The display name of the user. |
**email** | **string** | The email of the user. |
**authorizedComponents** | **string[]** | The web components the user is authorized to use. When authorizations are disabled for the cluster, this always returns &#x60;[\&quot;*\&quot;]&#x60;, regardless of the user&#39;s actual permissions, since access is not restricted in that case. An anonymous caller of an unprotected API also gets &#x60;[\&quot;*\&quot;]&#x60;, for the same reason. |
**tenants** | [**\Camunda\Orchestration\Api\Model\TenantResult[]**](TenantResult.md) | The tenants the user is a member of. |
**groups** | **string[]** | The groups assigned to the user. |
**roles** | **string[]** | The roles assigned to the user. |
**salesPlanType** | **string** | The plan of the user. |
**c8Links** | **array<string,string>** | The links to the components in the C8 stack. |
**canLogout** | **bool** | Flag for understanding if the user is able to perform logout. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
