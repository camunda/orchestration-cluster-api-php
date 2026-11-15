<?php

/**
 * Post-gen hook 0200 — teach the generated ObjectSerializer about semantic value objects.
 *
 * Once models are retyped (hook 0300) so that identifier properties hold semantic value
 * objects, the (de)serialization layer must round-trip them:
 *   - serialization: a SemanticKey collapses to its underlying string.
 *   - deserialization: a string is lifted into the target SemanticKey (with validation).
 */

declare(strict_types=1);

return static function (array $ctx): void {
    $file = $ctx['out_dir'] . '/src/ObjectSerializer.php';
    if (!is_file($file)) {
        fwrite(STDERR, "  [serializer] ObjectSerializer.php not found — skipping\n");
        return;
    }
    $src = (string) file_get_contents($file);

    // --- serialization: SemanticKey -> string ------------------------------------
    $enumSerialize = "        if (\$data instanceof \\BackedEnum) {\n"
        . "            return \$data->value;\n"
        . "        }\n";
    $semanticSerialize = $enumSerialize
        . "\n        if (\$data instanceof \\Camunda\\Orchestration\\Semantic\\SemanticKey) {\n"
        . "            return \$data->value();\n"
        . "        }\n";
    if (!str_contains($src, 'instanceof \\Camunda\\Orchestration\\Semantic\\SemanticKey')) {
        $src = str_replace_once($src, $enumSerialize, $semanticSerialize, 'serialize branch');
    }

    // --- deserialization: string -> SemanticKey ----------------------------------
    $enumDeserialize = "        if (is_subclass_of(\$class, '\\BackedEnum')) {";
    $semanticDeserialize = "        if (is_subclass_of(\$class, '\\Camunda\\Orchestration\\Semantic\\SemanticKey')) {\n"
        . "            return new \$class(is_string(\$data) ? \$data : (string) \$data);\n"
        . "        }\n\n"
        . $enumDeserialize;
    if (!str_contains($src, "is_subclass_of(\$class, '\\Camunda\\Orchestration\\Semantic\\SemanticKey')")) {
        $src = str_replace_once($src, $enumDeserialize, $semanticDeserialize, 'deserialize branch');
    }

    // --- oneOf/anyOf: keep numeric-looking strings as strings ---------------------
    // A nested value such as a key filter "2251799813686749" is already a PHP string;
    // json_decode() would turn it into an int, so the `string` member of e.g.
    // ProcessDefinitionKeyFilterProperty no longer matches and the value is lost
    // (#145 / #147). For union dispatch, only accept the decoded form when it is
    // structured (array/object) or the literal null.
    $decodeAnchor = "                \$decoded = json_decode(\$data);\n"
        . "                // Keep the original string if decode fails (nested values are already PHP strings).\n"
        . "                \$data = (\$decoded !== null || \$data === 'null') ? \$decoded : \$data;\n";
    $decodePatched = "                \$decoded = json_decode(\$data);\n"
        . "                // Keep the original string if decode fails (nested values are already PHP strings).\n"
        . "                // For oneOf/anyOf unions also keep it when it decodes to a scalar, so numeric-looking\n"
        . "                // key strings still match a `string` member.\n"
        . "                \$isUnion = is_subclass_of(\$class, '\\Camunda\\Orchestration\\Api\\Model\\OneOfInterface')\n"
        . "                    || is_subclass_of(\$class, '\\Camunda\\Orchestration\\Api\\Model\\AnyOfInterface');\n"
        . "                if (\$isUnion && \$decoded !== null && !is_array(\$decoded) && !is_object(\$decoded)) {\n"
        . "                    \$decoded = null;\n"
        . "                }\n"
        . "                \$data = (\$decoded !== null || \$data === 'null') ? \$decoded : \$data;\n";
    if (!str_contains($src, '$isUnion = ')) {
        $src = str_replace_once($src, $decodeAnchor, $decodePatched, 'union scalar decode');
    }

    file_put_contents($file, $src);
    fwrite(STDOUT, "  [serializer] patched ObjectSerializer for semantic value objects\n");
};

function str_replace_once(string $haystack, string $needle, string $replace, string $what): string
{
    $pos = strpos($haystack, $needle);
    if ($pos === false) {
        throw new RuntimeException("hook 0200: anchor for '$what' not found in ObjectSerializer.php");
    }
    return substr($haystack, 0, $pos) . $replace . substr($haystack, $pos + strlen($needle));
}
