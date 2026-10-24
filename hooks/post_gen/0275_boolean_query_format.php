<?php

/**
 * Post-gen hook 0275 — serialize boolean query parameters as `true`/`false`.
 *
 * The upstream php-nextgen templates stringify boolean query parameters
 * PHP-style (`1`/`0`): `Configuration` defaults `booleanFormatForQueryString`
 * to `BOOLEAN_FORMAT_INT`, and `ObjectSerializer::toQueryValue()` only applies
 * the boolean conversion when the parameter is typed `boolean`. The Camunda 8
 * Orchestration Cluster API expects JSON-style `true`/`false` (like every other
 * SDK), so:
 *
 *   1. Flip the generated `Configuration` default to `BOOLEAN_FORMAT_STRING`.
 *      The generated `ObjectSerializer` consults the *default* configuration
 *      (`Configuration::getDefaultConfiguration()`), not the per-API instance,
 *      so the default itself must carry the string format.
 *   2. Harden `ObjectSerializer::toQueryValue()` so any boolean value is
 *      converted regardless of the declared OpenAPI type — a boolean must never
 *      leak into a query string as `1`/`0`.
 */

declare(strict_types=1);

return static function (array $ctx): void {
    $replaceOnce = static function (
        string $source,
        string $needle,
        string $replacement,
        string $description,
    ): string {
        $position = strpos($source, $needle);
        if ($position === false) {
            throw new RuntimeException("hook 0275: anchor for $description not found");
        }

        return substr($source, 0, $position) . $replacement . substr($source, $position + strlen($needle));
    };

    $outDir = $ctx['out_dir'] ?? null;
    if (!is_string($outDir) || $outDir === '') {
        throw new RuntimeException('hook 0275: output directory not provided');
    }

    // --- 1. Configuration: default to the string format -------------------------
    $configuration = $outDir . '/src/Configuration.php';
    if (!is_file($configuration)) {
        throw new RuntimeException('hook 0275: Configuration.php not found');
    }

    $configurationSource = (string) file_get_contents($configuration);
    if (!str_contains($configurationSource, 'protected string $booleanFormatForQueryString = self::BOOLEAN_FORMAT_STRING;')) {
        $configurationSource = $replaceOnce(
            $configurationSource,
            'protected string $booleanFormatForQueryString = self::BOOLEAN_FORMAT_INT;',
            'protected string $booleanFormatForQueryString = self::BOOLEAN_FORMAT_STRING;',
            'booleanFormatForQueryString default',
        );
        file_put_contents($configuration, $configurationSource);
    }

    // --- 2. ObjectSerializer: convert any boolean query value -------------------
    $serializer = $outDir . '/src/ObjectSerializer.php';
    if (!is_file($serializer)) {
        throw new RuntimeException('hook 0275: ObjectSerializer.php not found');
    }

    $serializerSource = (string) file_get_contents($serializer);
    if (!str_contains($serializerSource, "\n        if (is_bool(\$value)) {")) {
        $serializerSource = $replaceOnce(
            $serializerSource,
            <<<'PHP'
        if ('boolean' === $openApiType && is_bool($value)) {
            $value = self::convertBoolToQueryStringFormat($value);
        }
PHP,
            <<<'PHP'
        if (is_bool($value)) {
            $value = self::convertBoolToQueryStringFormat($value);
        }
PHP,
            'toQueryValue boolean conversion',
        );
        file_put_contents($serializer, $serializerSource);
    }

    fwrite(STDOUT, "  [boolean-query] boolean query parameters serialize as 'true'/'false'\n");
};
