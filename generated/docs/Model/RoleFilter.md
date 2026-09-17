# RoleFilter

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**roleId** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The role ID search filters. | [optional]
**name** | [**\Camunda\Orchestration\Api\Model\StringFilterProperty**](StringFilterProperty.md) | The role name search filters. | [optional]
**or** | [**\Camunda\Orchestration\Api\Model\RoleFilterFields[]**](RoleFilterFields.md) | Defines a list of alternative filter groups combined using OR logic. Each object in the array is evaluated independently, and the filter matches if any one of them is satisfied.  Top-level fields and the &#x60;$or&#x60; clause are combined using AND logic — meaning: (top-level filters) AND (any of the &#x60;$or&#x60; filters) must match. &lt;br&gt; &lt;em&gt;Example:&lt;/em&gt;  &#x60;&#x60;&#x60;json {   \&quot;name\&quot;: \&quot;Admin\&quot;,   \&quot;$or\&quot;: [     { \&quot;roleId\&quot;: \&quot;role-1\&quot; },     { \&quot;roleId\&quot;: \&quot;role-2\&quot; }   ] } &#x60;&#x60;&#x60; This matches roles that:  &lt;ul style&#x3D;\&quot;padding-left: 20px; margin-left: 20px;\&quot;&gt;   &lt;li style&#x3D;\&quot;list-style-type: disc;\&quot;&gt;have name equal to &lt;em&gt;Admin&lt;/em&gt;&lt;/li&gt;   &lt;li style&#x3D;\&quot;list-style-type: disc;\&quot;&gt;and match either:     &lt;ul style&#x3D;\&quot;padding-left: 20px; margin-left: 20px;\&quot;&gt;       &lt;li style&#x3D;\&quot;list-style-type: circle;\&quot;&gt;&lt;code&gt;roleId&lt;/code&gt; is &lt;em&gt;role-1&lt;/em&gt;, or&lt;/li&gt;       &lt;li style&#x3D;\&quot;list-style-type: circle;\&quot;&gt;&lt;code&gt;roleId&lt;/code&gt; is &lt;em&gt;role-2&lt;/em&gt;&lt;/li&gt;     &lt;/ul&gt;   &lt;/li&gt; &lt;/ul&gt; &lt;br&gt; &lt;p&gt;Note: Using complex &lt;code&gt;$or&lt;/code&gt; conditions may impact performance, use with caution in high-volume environments. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
