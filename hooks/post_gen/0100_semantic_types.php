<?php

/**
 * Post-gen hook 0100 — emit validated semantic value-object types.
 *
 * The Camunda Domain Type System (semantic keys such as ProcessInstanceKey,
 * ProcessDefinitionId, JobKey, TenantId, ElementId, …) is declared in the bundled
 * spec metadata under `semanticKeys`. openapi-generator collapses every one of them
 * to a bare `string`, so this hook emits a distinct, immutable, self-validating
 * value-object class per semantic key into generated/semantic/.
 *
 * These make identifiers distinct at the type level: PHPStan flags passing a
 * ProcessInstanceKey where a ProcessDefinitionKey is expected. They wrap a string,
 * serialize transparently to/from JSON, and stringify to their underlying value.
 */

declare(strict_types=1);

return static function (array $ctx): void {
    $metadataPath = $ctx['metadata_path'] ?? null;
    if (!$metadataPath || !is_file($metadataPath)) {
        fwrite(STDERR, "  [semantic-types] no spec-metadata.json — skipping\n");
        return;
    }

    $meta = json_decode((string) file_get_contents($metadataPath), true);
    $semanticKeys = $meta['semanticKeys'] ?? [];
    if (!$semanticKeys) {
        fwrite(STDERR, "  [semantic-types] metadata has no semanticKeys — skipping\n");
        return;
    }

    $dir = $ctx['out_dir'] . '/semantic';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    emit_marker_interface($dir);

    $names = [];
    foreach ($semanticKeys as $entry) {
        $name = $entry['name'] ?? null;
        if (!$name || !preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name)) {
            continue;
        }
        $names[] = $name;
        emit_value_object($dir, $name, $entry);
    }

    // Union aliases whose branches are all semantic keys become "key set" helpers
    // (e.g. ScopeKey = ProcessInstanceKey | ElementInstanceKey). PHP has no union
    // classes, so we emit a factory that lifts a raw string into the first branch
    // that accepts it. Only unions whose every branch is itself a semantic key.
    $keySet = array_flip($names);
    foreach (($meta['unions'] ?? []) as $union) {
        $branches = $union['branches'] ?? [];
        $refs = [];
        $ok = (bool) $branches;
        foreach ($branches as $b) {
            if (($b['branchType'] ?? '') !== 'ref' || !isset($keySet[$b['ref'] ?? ''])) {
                $ok = false;
                break;
            }
            $refs[] = $b['ref'];
        }
        if ($ok && preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $union['name'] ?? '')) {
            emit_union_factory($dir, $union['name'], $refs);
        }
    }

    sort($names);
    fwrite(STDOUT, '  [semantic-types] emitted ' . count($names) . " value objects\n");
};

function emit_marker_interface(string $dir): void
{
    $php = <<<'PHP'
<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Semantic;

use JsonSerializable;
use Stringable;

/**
 * Marker interface for Camunda semantic identifier value objects.
 *
 * Semantic keys wrap a single string, validate it against the spec constraints,
 * serialize transparently to/from JSON, and stringify to the underlying value.
 * They exist to make distinct identifier kinds distinct at the type level.
 */
interface SemanticKey extends Stringable, JsonSerializable
{
    /** The underlying string value of this identifier. */
    public function value(): string;
}

PHP;
    file_put_contents($dir . '/SemanticKey.php', $php);
}

/**
 * @param array<string, mixed> $entry
 */
function emit_value_object(string $dir, string $name, array $entry): void
{
    $constraints = $entry['constraints'] ?? [];
    $description = trim((string) ($entry['description'] ?? "Semantic identifier: $name."));
    $description = str_replace(["\r", "\n"], ' ', $description);

    $pattern = isset($constraints['pattern']) && is_string($constraints['pattern'])
        ? $constraints['pattern']
        : null;
    $minLength = isset($constraints['minLength']) ? (int) $constraints['minLength'] : null;
    $maxLength = isset($constraints['maxLength']) ? (int) $constraints['maxLength'] : null;

    $checks = [];
    if ($pattern !== null) {
        // PCRE with the /u modifier supports the \p{L}, \p{N} unicode escapes used in the spec.
        $delimited = '/' . str_replace('/', '\\/', $pattern) . '/u';
        $checks[] = "        if (preg_match(self::PATTERN, \$value) !== 1) {\n"
            . "            throw new InvalidArgumentException("
            . "sprintf('%s must match %s, got \"%s\"', self::NAME, self::PATTERN, \$value));\n"
            . "        }";
    }
    if ($minLength !== null) {
        $checks[] = "        if (mb_strlen(\$value) < $minLength) {\n"
            . "            throw new InvalidArgumentException("
            . "sprintf('%s must be at least $minLength character(s)', self::NAME));\n"
            . "        }";
    }
    if ($maxLength !== null) {
        $checks[] = "        if (mb_strlen(\$value) > $maxLength) {\n"
            . "            throw new InvalidArgumentException("
            . "sprintf('%s must be at most $maxLength character(s)', self::NAME));\n"
            . "        }";
    }
    $ctorBody = "    {\n" . ($checks ? implode("\n", $checks) . "\n" : '') . "    }";

    $patternConst = $pattern !== null
        ? "    public const PATTERN = '" . str_replace("'", "\\'", '/' . str_replace('/', '\\/', $pattern) . '/u') . "';\n\n"
        : '';

    $php = <<<PHP
<?php

declare(strict_types=1);

namespace Camunda\\Orchestration\\Semantic;

use InvalidArgumentException;

/**
 * $description
 *
 * Distinct, immutable, self-validating semantic identifier. Wraps a string and
 * serializes transparently to/from JSON.
 */
final class $name implements SemanticKey
{
    public const NAME = '$name';

$patternConst    public function __construct(private readonly string \$value)
$ctorBody

    /** Construct from a raw string (alias for the constructor). */
    public static function of(string \$value): self
    {
        return new self(\$value);
    }

    public function value(): string
    {
        return \$this->value;
    }

    public function equals(self \$other): bool
    {
        return \$this->value === \$other->value;
    }

    public function __toString(): string
    {
        return \$this->value;
    }

    public function jsonSerialize(): string
    {
        return \$this->value;
    }
}

PHP;
    file_put_contents($dir . '/' . $name . '.php', $php);
}

/**
 * @param list<string> $refs
 */
function emit_union_factory(string $dir, string $name, array $refs): void
{
    $tries = '';
    foreach ($refs as $ref) {
        $tries .= "        try {\n"
            . "            return new $ref(\$value);\n"
            . "        } catch (InvalidArgumentException) {\n"
            . "        }\n";
    }
    $branchList = implode(', ', $refs);

    $php = <<<PHP
<?php

declare(strict_types=1);

namespace Camunda\\Orchestration\\Semantic;

use InvalidArgumentException;

/**
 * Factory for the $name key set ($branchList).
 *
 * PHP has no union types for classes, so this lifts a raw string into the first
 * branch whose constraints accept it.
 */
final class $name
{
    public static function of(string \$value): SemanticKey
    {
$tries        throw new InvalidArgumentException(
            sprintf('%s: "%s" matched no branch ($branchList)', '$name', \$value)
        );
    }
}

PHP;
    file_put_contents($dir . '/' . $name . '.php', $php);
}
