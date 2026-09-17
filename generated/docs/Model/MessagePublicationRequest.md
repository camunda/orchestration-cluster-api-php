# MessagePublicationRequest

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** | The name of the message. |
**correlationKey** | **string** | The correlation key of the message. | [optional] [default to '']
**timeToLive** | **int** | Timespan (in ms) to buffer the message on the broker. | [optional] [default to 0]
**messageId** | **string** | The unique ID of the message. This is used to ensure only one message with the given ID will be published during the lifetime of the message (if &#x60;timeToLive&#x60; is set). | [optional]
**variables** | **array<string,mixed>** | The message variables as JSON document. | [optional]
**tenantId** | **string** | The tenant of the message sender. | [optional]
**businessId** | **string** | An optional business id used to enforce uniqueness of the process instance that a message start event would create. If provided and uniqueness enforcement is enabled, the engine rejects starting a new process instance when another root process instance with the same business id is already active for the same process definition. It has no effect when the message correlates to a catch, boundary, or intermediate event. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
