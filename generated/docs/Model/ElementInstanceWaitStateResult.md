# ElementInstanceWaitStateResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**rootProcessInstanceKey** | **string** | Key of the root process instance. |
**processInstanceKey** | **string** | The process instance key associated to this element instance. |
**elementInstanceKey** | **string** | The element instance key associated to this element instance. |
**elementId** | **string** | The element ID for this element instance. |
**elementType** | [**\Camunda\Orchestration\Api\Model\WaitStateElementTypeEnum**](WaitStateElementTypeEnum.md) | The BPMN element type of this element instance. |
**tenantId** | **string** | The tenant ID of the element instance. |
**bpmnProcessId** | **string** | The BPMN process ID of the process definition associated to this element instance. |
**details** | [**\Camunda\Orchestration\Api\Model\WaitStateDetails**](WaitStateDetails.md) | Wait-state-specific details, resolved by waitStateType. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
