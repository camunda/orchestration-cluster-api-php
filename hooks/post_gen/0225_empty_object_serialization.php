<?php

/**
 * Post-gen hook 0225 — serialize empty object-typed maps as `{}`, never `[]`.
 *
 * Object-typed request fields (`variables`, `metadata`, `customProperties`, …) are
 * declared in the spec as `type: object` with `additionalProperties`, which php-nextgen
 * maps to the PHP type `array<string,mixed>` (or `array<string,Model>`). When the caller
 * passes an empty map, PHP's `json_encode` renders an empty array as `[]` — a JSON array,
 * not an object — and the server may reject it.
 *
 * Teach the generated ObjectSerializer to collapse an empty array to `new \stdClass()`
 * whenever the declared OpenAPI type is object-shaped (`object` or `array<...>`), so the
 * wire payload carries `{}`. Plain list types (`array`, `string[]`, `Model[]`, …) are
 * untouched: they are JSON arrays and `[]` is correct for them.
 */

declare(strict_types=1);

return static function (array $ctx): void {
    $file = $ctx['out_dir'] . '/src/ObjectSerializer.php';
    if (!is_file($file)) {
        throw new RuntimeException('hook 0225: ObjectSerializer.php not found');
    }
    $src = (string) file_get_contents($file);

    if (str_contains($src, 'serializeEmptyObjectAsObject')) {
        fwrite(STDOUT, "  [empty-object] ObjectSerializer already emits {} for empty object maps\n");
        return;
    }

    $anchor = <<<'PHP'
        if (is_array($data)) {
            foreach ($data as $property => $value) {
                $data[$property] = self::sanitizeForSerialization($value);
            }
            return $data;
        }
PHP;

    $replacement = <<<'PHP'
        if (is_array($data)) {
            if ($data === [] && self::isObjectShapedType($type)) {
                // An empty map declared as an object in the OpenAPI schema must
                // serialize as `{}`, not `[]` — the server rejects JSON arrays here.
                return new \stdClass();
            }
            foreach ($data as $property => $value) {
                $data[$property] = self::sanitizeForSerialization($value);
            }
            return $data;
        }
PHP;

    $src = str_replace_once_0225($src, $anchor, $replacement, 'is_array branch');

    // --- helper: does the declared OpenAPI type denote a JSON object? ----------------
    $helperAnchor = "    /**\n     * Sanitize filename by removing path.";
    $helper = <<<'PHP'
    /**
     * Whether the declared OpenAPI type denotes a JSON object (a map), as opposed to a
     * JSON array (a list). Object-shaped types are `object` and string-keyed maps
     * (`array<string,mixed>`, `array<string,Model>`, `map[string,mixed]`, …).
     *
     * @param string|null $type the OpenAPITools type of the data
     *
     * @return bool true when an empty value must serialize as `{}` rather than `[]`
     */
    private static function isObjectShapedType(?string $type): bool
    {
        if ($type === null) {
            return false;
        }
        $type = ltrim(strtolower($type), '\\');
        if ($type === 'object' || $type === 'stdclass' || $type === '\\stdclass') {
            return true;
        }
        // Map types: array<string,...> / map[string,...] — but not list types such as
        // `array`, `string[]` or `\Model[]`, which are JSON arrays.
        return (bool) preg_match('/^(array|map)\s*[<\[]\s*string\s*[,>\]]/i', $type);
    }

    /**
     * Sanitize filename by removing path.
PHP;

    $src = str_replace_once_0225($src, $helperAnchor, $helper, 'helper insertion point');

    file_put_contents($file, $src);
    fwrite(STDOUT, "  [empty-object] patched ObjectSerializer to emit {} for empty object-typed maps\n");
};

function str_replace_once_0225(string $haystack, string $needle, string $replace, string $what): string
{
    $pos = strpos($haystack, $needle);
    if ($pos === false) {
        throw new RuntimeException("hook 0225: anchor for '$what' not found in ObjectSerializer.php");
    }
    return substr($haystack, 0, $pos) . $replace . substr($haystack, $pos + strlen($needle));
}
