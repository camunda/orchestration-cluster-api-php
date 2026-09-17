# CamundaUserResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**username** | **string** | The username of the user. |
**displayName** | **string** | The display name of the user. |
**email** | **string** | The email of the user. |
**authorizedComponents** | **string[]** | The web components the user is authorized to use. |
**tenants** | [**\Camunda\Orchestration\Api\Model\TenantResult[]**](TenantResult.md) | The tenants the user is a member of. |
**groups** | **string[]** | The groups assigned to the user. |
**roles** | **string[]** | The roles assigned to the user. |
**salesPlanType** | **string** | The plan of the user. |
**c8Links** | **array<string,string>** | The links to the components in the C8 stack. |
**canLogout** | **bool** | Flag for understanding if the user is able to perform logout. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
