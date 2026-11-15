<?php

/**
 * Pre-generation spec transform: collapse scalar `*ExactMatch` schemas into aliases.
 *
 * The spec models the exact-match branch of key/enum filter properties as a named
 * string schema wrapping its target via a single-element `allOf`:
 *
 *   ProcessDefinitionKeyExactMatch:
 *     type: string
 *     allOf: [ $ref ProcessDefinitionKey ]
 *
 * php-nextgen emits such a schema as an EMPTY model class (no properties), so the
 * key/enum string has nowhere to live and is silently dropped from search and
 * batch-operation filters (see #145 / #147). Rewriting the schema to a plain
 * `$ref` of its target makes the generator treat the oneOf member as the scalar
 * (`string`) or the enum type, so both the exact-match and the advanced form
 * round-trip.
 *
 * The callable receives and returns the spec decoded as objects (stdClass), so
 * empty JSON objects survive the round-trip.
 */

declare(strict_types=1);

return static function (\stdClass $spec): \stdClass {
    $schemas = $spec->components->schemas ?? null;
    if (!$schemas instanceof \stdClass) {
        return $spec;
    }

    $rewritten = 0;
    foreach (get_object_vars($schemas) as $name => $schema) {
        if (!str_ends_with((string) $name, 'ExactMatch') || !$schema instanceof \stdClass) {
            continue;
        }
        if (($schema->type ?? null) !== 'string' || isset($schema->properties)) {
            continue;
        }
        $allOf = $schema->allOf ?? null;
        if (!is_array($allOf) || count($allOf) !== 1 || !$allOf[0] instanceof \stdClass || !is_string($allOf[0]->{'$ref'} ?? null)) {
            continue;
        }
        $schemas->{$name} = (object) ['$ref' => $allOf[0]->{'$ref'}];
        $rewritten++;
    }

    fwrite(STDOUT, "  [exact-match-scalars] collapsed $rewritten *ExactMatch schemas to scalar aliases\n");

    return $spec;
};
