<?php

/**
 * Post-gen hook 0325 — reject async promises on non-2xx responses.
 *
 * The upstream php-nextgen templates generate `*AsyncWithHttpInfo` methods whose
 * promise *fulfilment* callback deserializes whatever body arrived into the
 * operation's return type without ever looking at the HTTP status code. Because
 * both SDK clients are built with `http_errors => false` (the sync `*WithHttpInfo`
 * path inspects status codes itself and must see error responses), Guzzle fulfils
 * 4xx/5xx responses — and the async path surfaced them as successful, usually
 * empty, result models. A broker-side `503` backpressure rejection therefore
 * looked like a successful `CreateProcessInstanceResult` with a null key
 * (issue #85).
 *
 * This hook injects the same status check the sync path performs
 * (`$statusCode < 200 || $statusCode > 299`) at the top of every async fulfilment
 * callback. On a non-2xx response the callback now throws an `ApiException`
 * carrying the status code, headers and raw body; the problem body is best-effort
 * deserialized into `ProblemDetail` and attached via `setResponseObject()`,
 * mirroring the sync path's per-status handling. Thrown inside the fulfilment
 * callback, the exception rejects the returned promise, so async callers observe
 * exactly what sync callers observe.
 */

declare(strict_types=1);

return static function (array $ctx): void {
    $outDir = $ctx['out_dir'] ?? null;
    if (!is_string($outDir) || $outDir === '') {
        throw new RuntimeException('hook 0325: output directory not provided');
    }

    $apiDir = $outDir . '/src/Api';
    if (!is_dir($apiDir)) {
        throw new RuntimeException('hook 0325: generated Api directory not found');
    }

    // Anchor: the generated fulfilment callback of every `*AsyncWithHttpInfo`
    // method. The check is injected right after it, before any deserialization.
    $anchor = <<<'PHP'
                function ($response) use ($returnType) {
PHP;

    $check = <<<'PHP'

                    $statusCode = $response->getStatusCode();
                    if ($statusCode < 200 || $statusCode > 299) {
                        $responseBody = (string) $response->getBody();
                        $apiException = new ApiException(
                            sprintf(
                                '[%d] Error connecting to the API',
                                $statusCode
                            ),
                            $statusCode,
                            $response->getHeaders(),
                            $responseBody
                        );
                        try {
                            $apiException->setResponseObject(
                                ObjectSerializer::deserialize(
                                    json_decode($responseBody),
                                    '\Camunda\Orchestration\Api\Model\ProblemDetail',
                                    []
                                )
                            );
                        } catch (\Throwable) {
                            // Non-problem error bodies stay available via getResponseBody().
                        }
                        throw $apiException;
                    }

PHP;

    $patchedFiles = 0;
    $patchedCallbacks = 0;

    foreach (glob($apiDir . '/*Api.php') ?: [] as $apiFile) {
        $source = (string) file_get_contents($apiFile);
        $count = substr_count($source, $anchor);
        if ($count === 0) {
            continue;
        }
        if (str_contains($source, 'Non-problem error bodies stay available via getResponseBody().')) {
            continue; // already patched (idempotency)
        }

        $source = str_replace($anchor, $anchor . $check, $source);
        file_put_contents($apiFile, $source);
        ++$patchedFiles;
        $patchedCallbacks += $count;
    }

    if ($patchedCallbacks === 0) {
        throw new RuntimeException('hook 0325: no async fulfilment callbacks found to patch');
    }

    fwrite(STDOUT, "  [async-http-errors] $patchedCallbacks async operations across $patchedFiles API groups now reject on non-2xx responses\n");
};
