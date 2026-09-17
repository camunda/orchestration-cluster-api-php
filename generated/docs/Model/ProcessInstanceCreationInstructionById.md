# ProcessInstanceCreationInstructionById

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**processDefinitionId** | **string** | The BPMN process id of the process definition to start an instance of. |
**processDefinitionVersion** | **int** | The version of the process. If omitted, the latest active version is used. | [optional] [default to -1]
**variables** | **array<string,mixed>** | JSON object that will instantiate the variables for the root variable scope of the process instance. | [optional]
**tenantId** | **string** | The tenant id of the process definition. If multi-tenancy is enabled, provide the tenant id of the process definition to start a process instance of. If multi-tenancy is disabled, don&#39;t provide this parameter. | [optional]
**operationReference** | **int** | A reference key chosen by the user that will be part of all records resulting from this operation. Must be &gt; 0 if provided. | [optional]
**startInstructions** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceCreationStartInstruction[]**](ProcessInstanceCreationStartInstruction.md) | List of start instructions. By default, the process instance will start at the start event. If provided, the process instance will apply start instructions after it has been created. | [optional]
**runtimeInstructions** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceCreationTerminateInstruction[]**](ProcessInstanceCreationTerminateInstruction.md) | Runtime instructions (alpha). List of instructions that affect the runtime behavior of the process instance. Refer to specific instruction types for more details.  This parameter is an alpha feature and may be subject to change in future releases. | [optional]
**awaitCompletion** | **bool** | Wait for the process instance to complete. If the process instance does not complete within the request timeout limit, a 504 response status will be returned. The process instance will continue to run in the background regardless of the timeout. Disabled by default. | [optional] [default to false]
**fetchVariables** | **string[]** | List of variables by name to be included in the response when awaitCompletion is set to true. If empty, all visible variables in the root scope will be returned. | [optional]
**requestTimeout** | **int** | Timeout (in ms) the request waits for the process to complete. By default or when set to 0, the generic request timeout configured in the cluster is applied. | [optional] [default to 0]
**tags** | **string[]** | List of tags. Tags need to start with a letter; then alphanumerics, &#x60;_&#x60;, &#x60;-&#x60;, &#x60;:&#x60;, or &#x60;.&#x60;; length ≤ 100. | [optional]
**businessId** | **string** | An optional, user-defined string identifier that identifies the process instance within the scope of a process definition (scoped by tenant). If provided and uniqueness enforcement is enabled, the engine will reject creation if another root process instance with the same business id is already active for the same process definition. Note that any active child process instances with the same business id are not taken into account. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
