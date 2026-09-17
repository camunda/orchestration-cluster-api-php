# ElementInstanceFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**processDefinitionId** | **string** | The process definition ID associated to this element instance. | [optional]
**state** | [**\Camunda\Orchestration\Api\Model\ElementInstanceStateFilterProperty**](ElementInstanceStateFilterProperty.md) | State of element instance as defined set of values. | [optional]
**type** | **string** | Type of element as defined set of values. | [optional]
**elementId** | [**\Camunda\Orchestration\Api\Model\ElementIdFilterProperty**](ElementIdFilterProperty.md) | The element ID for this element instance. | [optional]
**elementName** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The element name. This only works for data created with 8.8 and onwards. Instances from prior versions don&#39;t contain this data and cannot be found. | [optional]
**hasIncident** | **bool** | Shows whether this element instance has an incident related to. | [optional]
**tenantId** | **string** | The unique identifier of the tenant. | [optional]
**elementInstanceKey** | **string** | The assigned key, which acts as a unique identifier for this element instance. | [optional]
**processInstanceKey** | **string** | The process instance key associated to this element instance. | [optional]
**processDefinitionKey** | **string** | The process definition key associated to this element instance. | [optional]
**incidentKey** | **string** | The key of incident if field incident is true. | [optional]
**startDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The start date of this element instance. | [optional]
**endDate** | [**\Camunda\Orchestration\Api\Model\DateTimeFilterProperty**](DateTimeFilterProperty.md) | The end date of this element instance. | [optional]
**elementInstanceScopeKey** | **string** | The scope key of this element instance. If provided with a process instance key it will return element instances that are immediate children of the process instance. If provided with an element instance key it will return element instances that are immediate children of the element instance. | [optional]
**or** | [**\Camunda\Orchestration\Api\Model\ElementInstanceFilterFields[]**](ElementInstanceFilterFields.md) | Defines a list of alternative filter groups combined using OR logic. Each object in the array is evaluated independently, and the filter matches if any one of them is satisfied.  Top-level fields and the &#x60;$or&#x60; clause are combined using AND logic — meaning: (top-level filters) AND (any of the &#x60;$or&#x60; filters) must match. &lt;br&gt; &lt;em&gt;Example:&lt;/em&gt;  &#x60;&#x60;&#x60;json {   \&quot;processInstanceKey\&quot;: \&quot;2251799813685323\&quot;,   \&quot;$or\&quot;: [     { \&quot;elementName\&quot;: { \&quot;$like\&quot;: \&quot;*Order*\&quot; } },     { \&quot;elementId\&quot;:   { \&quot;$like\&quot;: \&quot;*Order*\&quot; } }   ] } &#x60;&#x60;&#x60; This matches element instances scoped to the given process instance whose:  &lt;ul style&#x3D;\&quot;padding-left: 20px; margin-left: 20px;\&quot;&gt;   &lt;li style&#x3D;\&quot;list-style-type: disc;\&quot;&gt;&lt;code&gt;elementName&lt;/code&gt; contains &lt;em&gt;Order&lt;/em&gt;, or&lt;/li&gt;   &lt;li style&#x3D;\&quot;list-style-type: disc;\&quot;&gt;&lt;code&gt;elementId&lt;/code&gt; contains &lt;em&gt;Order&lt;/em&gt;&lt;/li&gt; &lt;/ul&gt; &lt;br&gt; &lt;p&gt;Note: Using complex &lt;code&gt;$or&lt;/code&gt; conditions may impact performance, use with caution in high-volume environments. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
