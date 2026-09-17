# SetVariableRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**variables** | **array<string,mixed>** | JSON object representing the variables to set in the element’s scope. |
**local** | **bool** | If set to &#x60;true&#x60;, the variables are merged strictly into the local scope (as specified by the &#x60;elementInstanceKey&#x60;). Otherwise, the variables are propagated to upper scopes and set at the outermost one.  Let&#39;s consider the following example: There are two scopes &#39;1&#39; and &#39;2&#39;. Scope &#39;1&#39; is the parent scope of &#39;2&#39;. The effective variables of the scopes are: 1 &#x3D;&gt; { \&quot;foo\&quot; : 2 } 2 &#x3D;&gt; { \&quot;bar\&quot; : 1 }  An update request with elementInstanceKey as &#39;2&#39;, variables { \&quot;foo\&quot;: 5 }, and local set to &#x60;true&#x60; leaves scope &#39;1&#39; unchanged and adjusts scope &#39;2&#39; to { \&quot;bar\&quot;: 1, \&quot;foo\&quot;: 5 }. By default, with local set to &#x60;false&#x60;, scope &#39;1&#39; will be { \&quot;foo\&quot;: 5 } and scope &#39;2&#39; will be { \&quot;bar\&quot;: 1 }. | [optional] [default to false]
**operationReference** | **int** | A reference key chosen by the user that will be part of all records resulting from this operation. Must be &gt; 0 if provided. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
