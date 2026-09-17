# ProcessInstanceFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**startDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The start date. | [optional]
**endDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The end date. | [optional]
**state** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceStateFilterProperty**](ProcessInstanceStateFilterProperty.md) | The process instance state. | [optional]
**hasIncident** | **bool** | Whether this process instance has a related incident or not. | [optional]
**suspendedDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The time this process instance most recently entered the SUSPENDED state. This is cleared (null) again once the process instance is resumed. | [optional]
**tenantId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The tenant id. | [optional]
**variables** | [**\Camunda\Orchestration\Api\Model\VariableValueFilterProperty[]**](VariableValueFilterProperty.md) | The process instance variables. | [optional]
**processInstanceKey** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceKeyFilterProperty**](ProcessInstanceKeyFilterProperty.md) | The key of this process instance. | [optional]
**parentProcessInstanceKey** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceKeyFilterProperty**](ProcessInstanceKeyFilterProperty.md) | The parent process instance key. | [optional]
**parentElementInstanceKey** | [**\Camunda\Orchestration\Api\Model\ElementInstanceKeyFilterProperty**](ElementInstanceKeyFilterProperty.md) | The parent element instance key. | [optional]
**batchOperationId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The batch operation id. **Deprecated**: Use &#x60;batchOperationKey&#x60; instead. This field will be removed in a future release. If both &#x60;batchOperationId&#x60; and &#x60;batchOperationKey&#x60; are provided, the request will be rejected with a 400 error. | [optional]
**batchOperationKey** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The batch operation key. | [optional]
**errorMessage** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The error message related to the process. | [optional]
**hasRetriesLeft** | **bool** | Whether the process has failed jobs with retries left. | [optional]
**elementInstanceState** | [**\Camunda\Orchestration\Api\Model\ElementInstanceStateFilterProperty**](ElementInstanceStateFilterProperty.md) | The state of the element instances associated with the process instance. | [optional]
**elementId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The element id associated with the process instance. | [optional]
**hasElementInstanceIncident** | **bool** | Whether the element instance has an incident or not. | [optional]
**incidentErrorHashCode** | [**\Camunda\Orchestration\Api\Model\IntegerFilterProperty**](IntegerFilterProperty.md) | The incident error hash code, associated with this process. | [optional]
**tags** | **string[]** | List of tags. Tags need to start with a letter; then alphanumerics, &#x60;_&#x60;, &#x60;-&#x60;, &#x60;:&#x60;, or &#x60;.&#x60;; length ≤ 100. | [optional]
**businessId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The business id associated with the process instance. | [optional]
**processDefinitionId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The process definition id. | [optional]
**processDefinitionName** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The process definition name. | [optional]
**processDefinitionVersion** | [**\Camunda\Orchestration\Api\Model\IntegerFilterProperty**](IntegerFilterProperty.md) | The process definition version. | [optional]
**processDefinitionVersionTag** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The process definition version tag. | [optional]
**processDefinitionKey** | [**\Camunda\Orchestration\Api\Model\ProcessDefinitionKeyFilterProperty**](ProcessDefinitionKeyFilterProperty.md) | The process definition key. | [optional]
**or** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceFilterFields[]**](ProcessInstanceFilterFields.md) | Defines a list of alternative filter groups combined using OR logic. Each object in the array is evaluated independently, and the filter matches if any one of them is satisfied.  Top-level fields and the &#x60;$or&#x60; clause are combined using AND logic — meaning: (top-level filters) AND (any of the &#x60;$or&#x60; filters) must match. &lt;br&gt; &lt;em&gt;Example:&lt;/em&gt;  &#x60;&#x60;&#x60;json {   \&quot;state\&quot;: \&quot;ACTIVE\&quot;,   \&quot;tenantId\&quot;: 123,   \&quot;$or\&quot;: [     { \&quot;processDefinitionId\&quot;: \&quot;process_v1\&quot; },     { \&quot;processDefinitionId\&quot;: \&quot;process_v2\&quot;, \&quot;hasIncident\&quot;: true }   ] } &#x60;&#x60;&#x60; This matches process instances that:  &lt;ul style&#x3D;\&quot;padding-left: 20px; margin-left: 20px;\&quot;&gt;   &lt;li style&#x3D;\&quot;list-style-type: disc;\&quot;&gt;are in &lt;em&gt;ACTIVE&lt;/em&gt; state&lt;/li&gt;   &lt;li style&#x3D;\&quot;list-style-type: disc;\&quot;&gt;have tenant id equal to &lt;em&gt;123&lt;/em&gt;&lt;/li&gt;   &lt;li style&#x3D;\&quot;list-style-type: disc;\&quot;&gt;and match either:     &lt;ul style&#x3D;\&quot;padding-left: 20px; margin-left: 20px;\&quot;&gt;       &lt;li style&#x3D;\&quot;list-style-type: circle;\&quot;&gt;&lt;code&gt;processDefinitionId&lt;/code&gt; is &lt;em&gt;process_v1&lt;/em&gt;, or&lt;/li&gt;       &lt;li style&#x3D;\&quot;list-style-type: circle;\&quot;&gt;&lt;code&gt;processDefinitionId&lt;/code&gt; is &lt;em&gt;process_v2&lt;/em&gt; and &lt;code&gt;hasIncident&lt;/code&gt; is &lt;em&gt;true&lt;/em&gt;&lt;/li&gt;     &lt;/ul&gt;   &lt;/li&gt; &lt;/ul&gt; &lt;br&gt; &lt;p&gt;Note: Using complex &lt;code&gt;$or&lt;/code&gt; conditions may impact performance, use with caution in high-volume environments. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
