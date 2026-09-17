<?php

/**
 * Post-gen hook 0300 — retype generated models to use semantic value objects.
 *
 * openapi-generator collapses every semantic identifier property to a bare `string`.
 * The bundled spec marks each such property as a single `$ref` (directly or via a
 * one-element `allOf`) to a semantic-key schema. This hook resolves that mapping and,
 * for every affected model property, rewrites:
 *
 *   - the `$openAPITypes` entry            'prop' => 'string'  ->  the value-object FQCN
 *   - the getter return type               : ?string          ->  : ?Semantic\Key
 *   - the setter                           canonicalised to accept the value object
 *                                          (the value object owns validation, so the
 *                                          generator's inline string checks are dropped)
 *   - setIfExists()                        lifts raw strings into value objects so the
 *                                          array constructor stays ergonomic
 *   - listInvalidProperties()              casts the value object back to string for the
 *                                          generator's residual length/pattern checks
 *
 * Only scalar single-ref properties are retyped; array-of-key and union-wrapped
 * properties keep their generated representation.
 */

declare(strict_types=1);

return static function (array $ctx): void {
    $specPath = $ctx['spec_path'] ?? null;
    $metadataPath = $ctx['metadata_path'] ?? null;
    if (!$specPath || !is_file($specPath) || !$metadataPath || !is_file($metadataPath)) {
        fwrite(STDERR, "  [retype] spec/metadata missing — skipping\n");
        return;
    }

    $spec = json_decode((string) file_get_contents($specPath), true);
    $meta = json_decode((string) file_get_contents($metadataPath), true);
    $keyNames = [];
    foreach ($meta['semanticKeys'] ?? [] as $k) {
        $keyNames[$k['name']] = true;
    }
    $schemas = $spec['components']['schemas'] ?? [];

    // schema name -> [property => SemanticKeyName]
    $map = [];
    foreach ($schemas as $schemaName => $schema) {
        $props = $schema['properties'] ?? null;
        if (!is_array($props)) {
            continue;
        }
        foreach ($props as $propName => $propSchema) {
            if (!is_array($propSchema)) {
                continue;
            }
            $ref = scalar_ref_target($propSchema);
            if ($ref !== null && isset($keyNames[$ref])) {
                $map[$schemaName][$propName] = $ref;
            }
        }
    }

    $ns = '\\Camunda\\Orchestration\\Semantic\\';
    $modelDir = $ctx['out_dir'] . '/src/Model';
    $retyped = 0;
    $modelsTouched = 0;

    foreach ($map as $schemaName => $props) {
        $file = $modelDir . '/' . $schemaName . '.php';
        if (!is_file($file)) {
            continue;
        }
        $src = (string) file_get_contents($file);
        $orig = $src;

        foreach ($props as $prop => $key) {
            $fqcn = $ns . $key;
            $studly = ucfirst($prop);

            // 1. $openAPITypes entry: 'prop' => 'string'
            $typeNeedle = "'" . $prop . "' => 'string'";
            if (str_contains($src, $typeNeedle)) {
                $src = str_replace($typeNeedle, "'" . $prop . "' => '" . $fqcn . "'", $src);
            } else {
                // Not a plain string scalar (array/other) — leave this property untouched.
                continue;
            }

            // 2. getter return type
            $src = preg_replace(
                '/(public function get' . preg_quote($studly, '/') . '\(\): )(\??)string\b/',
                '$1$2' . preg_quote_replacement($fqcn),
                $src,
                1
            ) ?? $src;

            // 3. setter — canonicalise (value object owns validation)
            $src = preg_replace_callback(
                '/    public function set' . preg_quote($studly, '/')
                    . '\((\??)string \$(\w+)\): static\n    \{.*?\n    \}\n/s',
                static function (array $m) use ($prop, $fqcn): string {
                    $nullable = $m[1];
                    $var = $m[2];
                    $body = "        \$this->container['" . $prop . "'] = \$" . $var . ";\n\n"
                        . "        return \$this;\n";
                    return "    public function set" . ucfirst($prop) . "(" . $nullable . $fqcn . " \$" . $var . "): static\n"
                        . "    {\n" . $body . "    }\n";
                },
                $src,
                1
            ) ?? $src;

            // 4. listInvalidProperties / other residual string checks: cast VO -> string.
            //    Matches `$this->container['prop'])` (mb_strlen/preg_match subject), never the
            //    `=== null` comparison (no trailing paren) nor the getter/assignment.
            $castNeedle = "\$this->container['" . $prop . "'])";
            $castRepl = "(string) \$this->container['" . $prop . "'])";
            $src = str_replace($castNeedle, $castRepl, $src);

            $retyped++;
        }

        if ($src !== $orig) {
            $src = patch_set_if_exists($src);
            file_put_contents($file, $src);
            $modelsTouched++;
        }
    }

    fwrite(STDOUT, "  [retype] retyped $retyped properties across $modelsTouched models\n");
};

/**
 * Resolve a property schema to a single referenced component name, or null.
 *
 * @param array<string, mixed> $propSchema
 */
function scalar_ref_target(array $propSchema): ?string
{
    if (isset($propSchema['$ref']) && is_string($propSchema['$ref'])) {
        return basename_ref($propSchema['$ref']);
    }
    if (isset($propSchema['allOf']) && is_array($propSchema['allOf'])) {
        $refs = [];
        foreach ($propSchema['allOf'] as $frag) {
            if (is_array($frag) && isset($frag['$ref']) && is_string($frag['$ref'])) {
                $refs[] = basename_ref($frag['$ref']);
            }
        }
        if (count($refs) === 1) {
            return $refs[0];
        }
    }
    return null;
}

function basename_ref(string $ref): string
{
    $parts = explode('/', $ref);
    return (string) end($parts);
}

/**
 * Lift raw strings into semantic value objects in the array constructor path.
 */
function patch_set_if_exists(string $src): string
{
    $needle = "        \$this->container[\$variableName] = \$fields[\$variableName] ?? \$defaultValue;";
    if (!str_contains($src, $needle)) {
        return $src;
    }
    $replacement = <<<'PHP'
        $value = $fields[$variableName] ?? $defaultValue;
        $semanticType = static::$openAPITypes[$variableName] ?? null;
        if (
            is_string($value)
            && is_string($semanticType)
            && is_subclass_of($semanticType, \Camunda\Orchestration\Semantic\SemanticKey::class)
        ) {
            $value = new $semanticType($value);
        }
        $this->container[$variableName] = $value;
PHP;
    return str_replace($needle, $replacement, $src);
}

function preg_quote_replacement(string $s): string
{
    return str_replace('\\', '\\\\', str_replace('$', '\\$', $s));
}
