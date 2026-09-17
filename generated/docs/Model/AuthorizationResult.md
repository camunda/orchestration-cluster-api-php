# AuthorizationResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**ownerId** | **string** | The ID of the owner of permissions. |
**ownerType** | [**\Camunda\Orchestration\Api\Model\OwnerTypeEnum**](OwnerTypeEnum.md) |  |
**resourceType** | [**\Camunda\Orchestration\Api\Model\ResourceTypeEnum**](ResourceTypeEnum.md) | The type of resource that the permissions relate to. |
**resourceId** | **string** | ID of the resource the permission relates to (mutually exclusive with &#x60;resourcePropertyName&#x60;). |
**resourcePropertyName** | **string** | The name of the resource property the permission relates to (mutually exclusive with &#x60;resourceId&#x60;). |
**permissionTypes** | [**\Camunda\Orchestration\Api\Model\PermissionTypeEnum[]**](PermissionTypeEnum.md) | Specifies the types of the permissions. |
**authorizationKey** | **string** | The key of the authorization. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
