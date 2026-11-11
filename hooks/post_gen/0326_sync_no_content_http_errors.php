<?php

/**
 * Post-gen hook 0326 — throw ApiException on non-2xx sync no-content responses.
 *
 * The upstream php-nextgen templates generate `*WithHttpInfo` methods for
 * operations without a response body (completeJob, failJob, cancelProcessInstance,
 * deletes, …) that return `[null, $statusCode, $headers]` without ever checking
 * the status code. Because `CamundaClient` builds its Guzzle client with
 * `http_errors => false` (the sync path inspects status codes itself and must see
 * error responses), Guzzle never throws for 4xx/5xx — so every no-content
 * operation on the sync client reported HTTP errors as success: the facade
 * returned `null`, which callers read as "no ProblemDetail, it worked" (issue
 * #94). A `404`/`409` on job completion therefore looked like a completed job.
 *
 * This hook injects the same status check the body-returning sync operations
 * perform (`$statusCode < 200 || $statusCode > 299`) right before the no-content
 * return statement. On a non-2xx response the method now throws an `ApiException`
 * carrying the status code, headers and raw body; the existing
 * `catch (ApiException $e)` switch below then deserializes the declared
 * `ProblemDetail` for the status and rethrows, so sync no-content callers observe
 * exactly what body-returning and async (hook 0325) callers observe.
 */

declare(strict_types=1);

return static function (array $ctx): void {
    $outDir = $ctx['out_dir'] ?? null;
    if (!is_string($outDir) || $outDir === '') {
        throw new RuntimeException('hook 0326: output directory not provided');
    }

    $apiDir = $outDir . '/src/Api';
    if (!is_dir($apiDir)) {
        throw new RuntimeException('hook 0326: generated Api directory not found');
    }

    // Anchor: the no-content return statement of every sync `*WithHttpInfo`
    // method for an operation without a response body. The check is injected
    // right before it, inside the try block whose `catch (ApiException $e)`
    // switch enriches the exception with the declared ProblemDetail model.
    $anchor = <<<'PHP'
            return [null, $statusCode, $response->getHeaders()];
PHP;

    $check = <<<'PHP'
            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

PHP;

    $patchedFiles = 0;
    $patchedOperations = 0;
    $alreadyPatched = 0;

    // A no-content return already preceded by the injected check marks the
    // operation as patched (idempotent reruns). Body-returning operations
    // contain the same check text against a different return statement and
    // must not count.
    $patchedMarker = $check . $anchor;

    foreach (glob($apiDir . '/*Api.php') ?: [] as $apiFile) {
        $source = (string) file_get_contents($apiFile);
        $done = substr_count($source, $patchedMarker);
        $todo = substr_count($source, $anchor) - $done;
        if ($todo === 0) {
            $alreadyPatched += $done;
            continue;
        }

        // Patch every no-content return not already preceded by the check.
        $out = '';
        $offset = 0;
        while (($pos = strpos($source, $anchor, $offset)) !== false) {
            $before = substr($source, 0, $pos);
            if (str_ends_with($before, $check)) {
                $out .= substr($source, $offset, $pos - $offset + strlen($anchor));
            } else {
                $out .= substr($source, $offset, $pos - $offset) . $patchedMarker;
            }
            $offset = $pos + strlen($anchor);
        }
        $out .= substr($source, $offset);
        file_put_contents($apiFile, $out);
        ++$patchedFiles;
        $patchedOperations += $todo;
        $alreadyPatched += $done;
    }

    if ($patchedOperations + $alreadyPatched === 0) {
        throw new RuntimeException('hook 0326: no sync no-content operations found to patch');
    }

    fwrite(STDOUT, "  [sync-no-content-http-errors] $patchedOperations sync no-content operations across $patchedFiles API groups now throw on non-2xx responses ($alreadyPatched already patched)\n");
};
