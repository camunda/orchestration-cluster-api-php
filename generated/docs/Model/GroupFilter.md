# GroupFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**groupId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The group ID search filters. | [optional]
**name** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The group name search filters. | [optional]
**or** | [**\Camunda\Orchestration\Api\Model\GroupFilterFields[]**](GroupFilterFields.md) | Defines a list of alternative filter groups combined using OR logic. Each object in the array is evaluated independently, and the filter matches if any one of them is satisfied.  Top-level fields and the &#x60;$or&#x60; clause are combined using AND logic — meaning: (top-level filters) AND (any of the &#x60;$or&#x60; filters) must match. &lt;br&gt; &lt;em&gt;Example:&lt;/em&gt;  &#x60;&#x60;&#x60;json {   \&quot;$or\&quot;: [     { \&quot;groupId\&quot;: \&quot;group-1\&quot; },     { \&quot;groupId\&quot;: \&quot;group-2\&quot; }   ] } &#x60;&#x60;&#x60; This matches groups whose &lt;code&gt;groupId&lt;/code&gt; is &lt;em&gt;group-1&lt;/em&gt; or &lt;em&gt;group-2&lt;/em&gt;. &lt;br&gt; &lt;p&gt;Note: Using complex &lt;code&gt;$or&lt;/code&gt; conditions may impact performance, use with caution in high-volume environments. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
