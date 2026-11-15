<?php

/**
 * Post-gen hook 0240 — treat scalar `*ExactMatch` filter schemas as plain strings.
 *
 * In the spec, the exact-match variant of key and enum filter properties is a named
 * string schema, e.g.
 *
 *   ProcessDefinitionKeyExactMatch:
 *     type: string
 *     allOf: [ $ref ProcessDefinitionKey ]
 *
 * php-nextgen emits such schemas as *empty model classes* (`$openAPITypes = []`). A key
 * or enum string then has nowhere to live: the oneOf filter property deserialises it
 * into an empty model and serialises it back as `{}`/nothing, so the filter property
 * is silently dropped from search and batch-operation requests (issue #145).
 *
 * This hook finds every generated `*ExactMatch` model whose spec schema is a scalar `type: string`
 * (no properties), rewrites every reference to it as `string` — exactly like
 * `StringFilterProperty`'s `string` member — and removes the empty model, its doc and
 * its generated test.
 */

declare(strict_types=1);

return static function (array $ctx): void {
    $specPath = $ctx['spec_path'] ?? null;
    if (!is_string($specPath) || !is_file($specPath)) {
        fwrite(STDERR, "  [scalar-exact-match] spec missing — skipping\n");
        return;
    }

    $spec = json_decode((string) file_get_contents($specPath), true);
    $schemas = $spec['components']['schemas'] ?? [];
    $outDir = $ctx['out_dir'];

    $names = [];
    foreach ($schemas as $name => $schema) {
        if (!is_string($name) || !is_array($schema)) {
            continue;
        }
        // Scope to exact-match filter members; other scalar models (e.g. semantic
        // union aliases like ScopeKey) are retyped by later hooks.
        if (!str_ends_with($name, 'ExactMatch')) {
            continue;
        }
        if (($schema['type'] ?? null) !== 'string' || isset($schema['properties'])) {
            continue;
        }
        $model = $outDir . '/src/Model/' . $name . '.php';
        if (!is_file($model)) {
            continue;
        }
        $src = (string) file_get_contents($model);
        // Only rewrite empty object models — never enum classes or real models.
        if (!str_contains($src, 'implements ModelInterface')
            || !preg_match('/\$openAPITypes = \[\s*\];/', $src)) {
            continue;
        }
        $names[] = $name;
    }

    if ($names === []) {
        fwrite(STDOUT, "  [scalar-exact-match] no scalar models to rewrite\n");
        return;
    }

    $fqnPattern = '/\\\\?Camunda\\\\Orchestration\\\\Api\\\\Model\\\\(' . implode('|', array_map(
        static fn (string $n): string => preg_quote($n, '/'),
        $names,
    )) . ')(?![A-Za-z0-9_])/';
    $mdLinkPattern = '/\[\*\*\\\\?Camunda\\\\Orchestration\\\\Api\\\\Model\\\\(' . implode('|', $names) . ')\*\*\]\(\1\.md\)/';

    $files = array_merge(
        glob($outDir . '/src/Api/*.php') ?: [],
        glob($outDir . '/src/Model/*.php') ?: [],
        glob($outDir . '/docs/Model/*.md') ?: [],
        glob($outDir . '/docs/Api/*.md') ?: [],
    );

    $touched = 0;
    foreach ($files as $file) {
        $src = (string) file_get_contents($file);
        $orig = $src;
        $src = preg_replace($mdLinkPattern, '**string**', $src) ?? $src;
        $src = preg_replace($fqnPattern, 'string', $src) ?? $src;
        if ($src !== $orig) {
            file_put_contents($file, $src);
            ++$touched;
        }
    }

    $nameSet = array_flip($names);
    foreach ($names as $name) {
        @unlink($outDir . '/src/Model/' . $name . '.php');
        @unlink($outDir . '/docs/Model/' . $name . '.md');
        @unlink($outDir . '/tests/Model/' . $name . 'Test.php');
    }

    // Drop the removed models from the generated README model index and FILES manifest.
    $readme = $outDir . '/README.md';
    if (is_file($readme)) {
        $lines = explode("\n", (string) file_get_contents($readme));
        $lines = array_filter($lines, static function (string $line) use ($nameSet): bool {
            return !(preg_match('/^- \[([A-Za-z0-9_]+)\]\(docs\/Model\/\1\.md\)$/', $line, $m) && isset($nameSet[$m[1]]));
        });
        file_put_contents($readme, implode("\n", $lines));
    }
    $manifest = $outDir . '/.openapi-generator/FILES';
    if (is_file($manifest)) {
        $lines = explode("\n", (string) file_get_contents($manifest));
        $lines = array_filter($lines, static function (string $line) use ($nameSet): bool {
            return !(preg_match('/^(?:src|docs|tests)\/Model\/([A-Za-z0-9_]+?)(?:Test)?\.(?:php|md)$/', $line, $m) && isset($nameSet[$m[1]]));
        });
        file_put_contents($manifest, implode("\n", $lines));
    }

    fwrite(STDOUT, '  [scalar-exact-match] rewrote ' . count($names) . " scalar models as string across {$touched} files\n");

    patch_one_of_numeric_string_decode($outDir . '/src/ObjectSerializer.php');
};

/**
 * ObjectSerializer::deserialize() json-decodes string data before dispatching to a
 * oneOf/anyOf member, which turns a numeric key string like "2251799813686749" into an
 * int — and then no `string` member matches. Keep the original string when the decoded
 * value is a scalar and the union has a `string` member and no numeric/boolean member
 * (so existing string|float unions still decode numbers as numbers).
 */
function patch_one_of_numeric_string_decode(string $file): void
{
    if (!is_file($file)) {
        return;
    }
    $src = (string) file_get_contents($file);
    if (str_contains($src, '[scalar-exact-match]')) {
        return;
    }

    $decodeNeedle = "            if (is_string(\$data)) {\n                \$decoded = json_decode(\$data);";
    $dispatchNeedle = "            if (is_subclass_of(\$class, '\\Camunda\\Orchestration\\Api\\Model\\OneOfInterface')) {";
    if (!str_contains($src, $decodeNeedle) || !str_contains($src, $dispatchNeedle)) {
        fwrite(STDERR, "  [scalar-exact-match] ObjectSerializer anchors not found — oneOf string patch skipped\n");
        return;
    }

    $src = str_replace(
        $decodeNeedle,
        "            \$rawData = \$data;\n" . $decodeNeedle,
        $src,
    );
    $src = str_replace(
        $dispatchNeedle,
        "            // [scalar-exact-match] A string member (e.g. a key exact-match filter) must keep a\n"
        . "            // numeric string as a string rather than its json-decoded int/float.\n"
        . "            if (\n"
        . "                is_string(\$rawData) && is_scalar(\$data) && !is_string(\$data)\n"
        . "                && (is_subclass_of(\$class, '\\Camunda\\Orchestration\\Api\\Model\\OneOfInterface')\n"
        . "                    || is_subclass_of(\$class, '\\Camunda\\Orchestration\\Api\\Model\\AnyOfInterface'))\n"
        . "                && (\$scalarExactMatchTypes = is_subclass_of(\$class, '\\Camunda\\Orchestration\\Api\\Model\\OneOfInterface') ? \$class::getOneOfTypes() : \$class::getAnyOfTypes()) !== []\n"
        . "                && in_array('string', \$scalarExactMatchTypes, true)\n"
        . "                // Unions with a numeric/boolean alternative (e.g. string|float) keep json decoding.\n"
        . "                && array_intersect(\$scalarExactMatchTypes, ['int', 'integer', 'float', 'double', 'number', 'bool', 'boolean']) === []\n"
        . "            ) {\n"
        . "                \$data = \$rawData;\n"
        . "            }\n"
        . $dispatchNeedle,
        $src,
    );
    file_put_contents($file, $src);
    fwrite(STDOUT, "  [scalar-exact-match] ObjectSerializer keeps numeric strings for string union members\n");
}
