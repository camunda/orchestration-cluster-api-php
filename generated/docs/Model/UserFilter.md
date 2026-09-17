# UserFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**username** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The username of the user. | [optional]
**name** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The name of the user. | [optional]
**email** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The email of the user. | [optional]
**or** | [**\Camunda\Orchestration\Api\Model\UserFilterFields[]**](UserFilterFields.md) | Defines a list of alternative filter groups combined using OR logic. Each object in the array is evaluated independently, and the filter matches if any one of them is satisfied.  Top-level fields and the &#x60;$or&#x60; clause are combined using AND logic — meaning: (top-level filters) AND (any of the &#x60;$or&#x60; filters) must match. &lt;br&gt; &lt;em&gt;Example:&lt;/em&gt;  &#x60;&#x60;&#x60;json {   \&quot;$or\&quot;: [     { \&quot;username\&quot;: \&quot;user-1\&quot; },     { \&quot;username\&quot;: \&quot;user-2\&quot; }   ] } &#x60;&#x60;&#x60; This matches users whose &lt;code&gt;username&lt;/code&gt; is &lt;em&gt;user-1&lt;/em&gt; or &lt;em&gt;user-2&lt;/em&gt;. &lt;br&gt; &lt;p&gt;Note: Using complex &lt;code&gt;$or&lt;/code&gt; conditions may impact performance, use with caution in high-volume environments. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
