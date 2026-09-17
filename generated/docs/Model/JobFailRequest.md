# JobFailRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**retries** | **int** | The amount of retries the job should have left | [optional] [default to 0]
**errorMessage** | **string** | An optional error message describing why the job failed; if not provided, an empty string is used. | [optional]
**retryBackOff** | **int** | An optional retry back off for the failed job. The job will not be retryable before the current time plus the back off time. The default is 0 which means the job is retryable immediately. | [optional] [default to 0]
**variables** | **array<string,mixed>** | JSON object that will instantiate the variables at the local scope of the job&#39;s associated task. | [optional]
**leaseToken** | **string** | The token identifying a leased job&#39;s activation, obtained from &#x60;ActivatedJobResult.leaseToken&#x60;. For a leased job, the matching token must be supplied to prove the command comes from the worker that holds the current lease; a command with no token is rejected. A command carrying a stale token is likewise rejected, fencing the job against a superseded activation (for example, after the job timed out or failed and was re-activated by another worker). A job that was activated without a lease requires no token. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
