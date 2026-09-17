# ExpressionEvaluationResult

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**expression** | **string** | The evaluated expression |
**result** | **mixed** | The result value. Its type can vary. |
**warnings** | [**\Camunda\Orchestration\Api\Model\ExpressionEvaluationWarningItem[]**](ExpressionEvaluationWarningItem.md) | List of warnings generated during expression evaluation |
**referencedSecrets** | [**\Camunda\Orchestration\Api\Model\ExpressionSecretReferenceItem[]**](ExpressionSecretReferenceItem.md) | The secret references resolved from trusted sources while evaluating the expression: a &#x60;camunda.secrets.&lt;name&gt;&#x60; reference used directly in the expression, or a reference carried by a &#x60;SECRET_REFERENCE&#x60;-kind cluster variable the expression read. References appearing only in request-body variables or plain cluster variables are excluded. Callers use this to know which &#x60;camunda.secrets.&lt;name&gt;&#x60; occurrences in the result they may safely resolve. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
