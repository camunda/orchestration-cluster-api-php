<?php

/**
 * Post-gen hook 0327 — let async rejections that are not response-bearing Guzzle
 * exceptions through unchanged.
 *
 * The upstream php-nextgen templates generate, for every `*AsyncWithHttpInfo`
 * method, a promise *rejection* callback that unconditionally calls
 * `$exception->getResponse()` and reads the status code, headers and body from the
 * result. That is only valid for a `RequestException` that carries a response.
 * Anything else that rejects the promise — a transport failure such as
 * `ConnectException` (no response at all), or an `ApiException` that SDK
 * middleware already built (for example `RetryExhaustedException`, issue #112) —
 * made the callback fail with a PHP `Error` ("Call to undefined method …
 * getResponse()"), hiding the real failure and its status from the caller.
 *
 * This hook injects a guard at the top of every rejection callback: a rejection
 * that is not a `RequestException` with a response is rethrown as is, so async
 * callers observe the same exception the middleware raised.
 */

declare(strict_types=1);

return static function (array $ctx): void {
    $outDir = $ctx['out_dir'] ?? null;
    if (!is_string($outDir) || $outDir === '') {
        throw new RuntimeException('hook 0327: output directory not provided');
    }

    $apiDir = $outDir . '/src/Api';
    if (!is_dir($apiDir)) {
        throw new RuntimeException('hook 0327: generated Api directory not found');
    }

    // Anchor: the generated rejection callback of every `*AsyncWithHttpInfo` method.
    $anchor = <<<'PHP'
                function ($exception) {
                    $response = $exception->getResponse();
PHP;

    $patched = <<<'PHP'
                function ($exception) {
                    if (!$exception instanceof \GuzzleHttp\Exception\RequestException || $exception->getResponse() === null) {
                        // Not a response-bearing HTTP failure (transport error, or an
                        // ApiException raised by SDK middleware): propagate unchanged.
                        throw $exception;
                    }
                    $response = $exception->getResponse();
PHP;

    $patchedFiles = 0;
    $patchedCallbacks = 0;
    $alreadyPatched = 0;

    foreach (glob($apiDir . '/*Api.php') ?: [] as $apiFile) {
        $source = (string) file_get_contents($apiFile);
        $count = substr_count($source, $anchor);
        $alreadyPatched += substr_count($source, $patched);
        if ($count === 0) {
            continue;
        }

        file_put_contents($apiFile, str_replace($anchor, $patched, $source));
        ++$patchedFiles;
        $patchedCallbacks += $count;
    }

    if ($patchedCallbacks + $alreadyPatched === 0) {
        throw new RuntimeException('hook 0327: no async rejection callbacks found to patch');
    }

    fwrite(STDOUT, "  [async-rejection-passthrough] $patchedCallbacks async rejection callbacks across $patchedFiles API groups now propagate non-HTTP failures ($alreadyPatched already patched)\n");
};
