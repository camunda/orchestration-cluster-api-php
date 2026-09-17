# UserTaskFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**state** | [**\Camunda\Orchestration\Api\Model\UserTaskStateFilterProperty**](UserTaskStateFilterProperty.md) | The user task state. | [optional]
**assignee** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The assignee of the user task. | [optional]
**businessId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The business ID of the owning process instance the user task belongs to. This only works for user tasks created with 8.10 and onwards. Tasks from prior versions don&#39;t contain this data and cannot be found. | [optional]
**priority** | [**\Camunda\Orchestration\Api\Model\IntegerFilterProperty**](IntegerFilterProperty.md) | The priority of the user task. | [optional]
**elementId** | **string** | The element ID of the user task. | [optional]
**name** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The task name. This only works for data created with 8.8 and onwards. Instances from prior versions don&#39;t contain this data and cannot be found. | [optional]
**candidateGroup** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The candidate group for this user task. | [optional]
**candidateUser** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The candidate user for this user task. | [optional]
**tenantId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | Tenant ID of this user task. | [optional]
**processDefinitionId** | [**\Camunda\Orchestration\Api\Model\ProcessDefinitionIdFilterProperty**](ProcessDefinitionIdFilterProperty.md) | The ID of the process definition. | [optional]
**creationDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The user task creation date. | [optional]
**completionDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The user task completion date. | [optional]
**followUpDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The user task follow-up date. | [optional]
**dueDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The user task due date. | [optional]
**processInstanceVariables** | [**\Camunda\Orchestration\Api\Model\VariableValueFilterProperty[]**](VariableValueFilterProperty.md) | The variables of the process instance. | [optional]
**localVariables** | [**\Camunda\Orchestration\Api\Model\VariableValueFilterProperty[]**](VariableValueFilterProperty.md) | The local variables of the user task. | [optional]
**userTaskKey** | **string** | The key for this user task. | [optional]
**processDefinitionKey** | [**\Camunda\Orchestration\Api\Model\ProcessDefinitionKeyFilterProperty**](ProcessDefinitionKeyFilterProperty.md) | The key of the process definition. | [optional]
**processInstanceKey** | [**\Camunda\Orchestration\Api\Model\ProcessInstanceKeyFilterProperty**](ProcessInstanceKeyFilterProperty.md) | The key of the process instance. | [optional]
**elementInstanceKey** | **string** | The key of the element instance. | [optional]
**tags** | **string[]** | List of tags. Tags need to start with a letter; then alphanumerics, &#x60;_&#x60;, &#x60;-&#x60;, &#x60;:&#x60;, or &#x60;.&#x60;; length ≤ 100. | [optional]
**or** | [**\Camunda\Orchestration\Api\Model\UserTaskFilterFields[]**](UserTaskFilterFields.md) | Defines a list of alternative filter groups combined using OR logic. Each object in the array is evaluated independently, and the filter matches if any one of them is satisfied.  Top-level fields and the &#x60;$or&#x60; clause are combined using AND logic — meaning: (top-level filters) AND (any of the &#x60;$or&#x60; filters) must match. &lt;br&gt; &lt;em&gt;Example:&lt;/em&gt;  &#x60;&#x60;&#x60;json {   \&quot;assignee\&quot;: \&quot;user1\&quot;,   \&quot;$or\&quot;: [     { \&quot;candidateGroup\&quot;: \&quot;groupA\&quot; },     { \&quot;candidateUser\&quot;: \&quot;user2\&quot; }   ] } &#x60;&#x60;&#x60; This matches user tasks that:  &lt;ul style&#x3D;\&quot;padding-left: 20px; margin-left: 20px;\&quot;&gt;   &lt;li style&#x3D;\&quot;list-style-type: disc;\&quot;&gt;are assigned to &lt;em&gt;user1&lt;/em&gt;&lt;/li&gt;   &lt;li style&#x3D;\&quot;list-style-type: disc;\&quot;&gt;and match either:     &lt;ul style&#x3D;\&quot;padding-left: 20px; margin-left: 20px;\&quot;&gt;       &lt;li style&#x3D;\&quot;list-style-type: circle;\&quot;&gt;&lt;code&gt;candidateGroup&lt;/code&gt; is &lt;em&gt;groupA&lt;/em&gt;, or&lt;/li&gt;       &lt;li style&#x3D;\&quot;list-style-type: circle;\&quot;&gt;&lt;code&gt;candidateUser&lt;/code&gt; is &lt;em&gt;user2&lt;/em&gt;&lt;/li&gt;     &lt;/ul&gt;   &lt;/li&gt; &lt;/ul&gt; &lt;br&gt; &lt;p&gt;Note: Using complex &lt;code&gt;$or&lt;/code&gt; conditions may impact performance, use with caution in high-volume environments. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
