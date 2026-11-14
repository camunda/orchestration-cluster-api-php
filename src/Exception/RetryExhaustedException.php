<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Exception;

use Camunda\Orchestration\Api\ApiException;

/**
 * Thrown when the SDK's transient-failure retry loop used every allowed attempt
 * and the request still failed.
 *
 * It is an {@see ApiException}, so existing `catch (ApiException $e)` handlers keep
 * working: {@see ApiException::getCode()} is the HTTP status of the **final**
 * attempt (`0` when the final attempt never received a response), and the response
 * headers and body are those of the final response. {@see getAttempts()} reports
 * how many requests were sent in total.
 *
 * The attempt budget is configured through `CAMUNDA_SDK_HTTP_RETRY_MAX_ATTEMPTS`.
 */
final class RetryExhaustedException extends ApiException
{
    /**
     * @param string[][]|null $responseHeaders
     */
    public function __construct(
        string $message,
        int $code,
        private readonly int $attempts,
        ?array $responseHeaders = [],
        \stdClass|string|null $responseBody = null,
    ) {
        parent::__construct($message, $code, $responseHeaders, $responseBody);
    }

    /** Total number of requests sent, including the first. */
    public function getAttempts(): int
    {
        return $this->attempts;
    }
}
