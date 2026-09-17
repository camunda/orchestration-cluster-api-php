<?php

/**
 * Check example coverage for the SDK.
 *
 * Two responsibilities:
 *
 *   1. Integrity (always enforced): every entry in examples/operation-map.json must
 *      reference (a) a real operation from the bundled OpenAPI spec, and (b) an
 *      example file + region tag that actually exists. Broken references fail CI.
 *
 *   2. Coverage (advisory by default): report how many spec operations have a curated
 *      example. Pass --strict to fail when any operation is missing an example.
 *
 * Usage:
 *   php scripts/check-example-coverage.php            # integrity gate + coverage report
 *   php scripts/check-example-coverage.php --strict    # additionally require full coverage
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$specPath = $root . '/external-spec/bundled/rest-api.bundle.json';
$mapPath = $root . '/examples/operation-map.json';
$examplesDir = $root . '/examples';
$strict = in_array('--strict', $argv, true);

const HTTP_METHODS = ['get', 'post', 'put', 'patch', 'delete', 'head', 'options', 'trace'];

function to_snake_case(string $name): string
{
    $s = preg_replace('/([A-Z]+)([A-Z][a-z])/', '$1_$2', $name) ?? $name;
    $s = preg_replace('/([a-z0-9])([A-Z])/', '$1_$2', $s) ?? $s;
    return strtolower($s);
}

/** @return array<string, string> region name => region name (set) */
function extract_regions(string $path): array
{
    $regions = [];
    $text = file_get_contents($path);
    if ($text === false) {
        return $regions;
    }
    foreach (explode("\n", $text) as $line) {
        if (preg_match('/^\s*\/\/\s*region\s+(.+?)\s*$/', $line, $m) === 1) {
            $regions[$m[1]] = $m[1];
        }
    }
    return $regions;
}

if (!is_file($specPath)) {
    fwrite(STDERR, "Spec not found at {$specPath}\nRun 'make bundle-spec' first.\n");
    exit(2);
}
if (!is_file($mapPath)) {
    fwrite(STDERR, "Operation map not found at {$mapPath}\n");
    exit(2);
}

/** @var array{paths?: array<string, array<string, mixed>>} $spec */
$spec = json_decode((string) file_get_contents($specPath), true, flags: JSON_THROW_ON_ERROR);
/** @var array<string, list<array{file?: string, region?: string, label?: string}>> $map */
$map = json_decode((string) file_get_contents($mapPath), true, flags: JSON_THROW_ON_ERROR);

// Collect operationIds from the spec, keyed by snake_case name.
$specOps = [];
foreach ($spec['paths'] ?? [] as $path => $item) {
    if (!is_array($item)) {
        continue;
    }
    foreach ($item as $method => $operation) {
        if (!in_array(strtolower((string) $method), HTTP_METHODS, true) || !is_array($operation)) {
            continue;
        }
        $operationId = $operation['operationId'] ?? null;
        if (is_string($operationId) && $operationId !== '') {
            $specOps[to_snake_case($operationId)] = [
                'operationId' => $operationId,
                'method' => strtoupper((string) $method),
                'path' => (string) $path,
            ];
        }
    }
}

// ── Integrity check ──────────────────────────────────────────────────────────
$integrityErrors = [];
$regionCache = [];
foreach ($map as $opId => $entries) {
    if (!isset($specOps[$opId])) {
        $integrityErrors[] = "{$opId}: not a known spec operation (check the operationId → snake_case mapping).";
    }
    if (!is_array($entries)) {
        $integrityErrors[] = "{$opId}: value is not a list.";
        continue;
    }
    foreach ($entries as $entry) {
        if (!is_array($entry) || !is_string($entry['file'] ?? null) || ($entry['file'] ?? '') === '') {
            $integrityErrors[] = "{$opId}: entry missing 'file' field.";
            continue;
        }
        if (!is_string($entry['region'] ?? null) || ($entry['region'] ?? '') === '') {
            $integrityErrors[] = "{$opId}: entry missing 'region' field.";
            continue;
        }
        $file = $examplesDir . '/' . $entry['file'];
        if (!is_file($file)) {
            $integrityErrors[] = "{$opId}: example file not found: {$entry['file']}";
            continue;
        }
        $regionCache[$file] ??= extract_regions($file);
        if (!isset($regionCache[$file][$entry['region']])) {
            $integrityErrors[] = "{$opId}: region '{$entry['region']}' not found in {$entry['file']}.";
        }
    }
}

if ($integrityErrors !== []) {
    fwrite(STDERR, "Example map integrity errors:\n");
    foreach ($integrityErrors as $err) {
        fwrite(STDERR, "  - {$err}\n");
    }
    exit(1);
}

// ── Coverage report ──────────────────────────────────────────────────────────
$covered = array_intersect_key($specOps, $map);
$missing = array_diff_key($specOps, $map);
$total = count($specOps);
$coveredCount = count($covered);
$pct = $total > 0 ? (int) round($coveredCount / $total * 100) : 0;

printf("Spec operations: %d\n", $total);
printf("Covered:         %d\n", $coveredCount);
printf("Missing:         %d\n", count($missing));
printf("Coverage:        %d%%\n", $pct);

if ($missing !== []) {
    if ($strict) {
        uasort($missing, static fn ($a, $b) => strcmp($a['operationId'], $b['operationId']));
        fwrite(STDERR, "\nMissing examples for:\n");
        foreach ($missing as $op) {
            fwrite(STDERR, "  - {$op['operationId']} ({$op['method']} {$op['path']})\n");
        }
        fwrite(STDERR, "\nAdd a region-tagged example and an operation-map.json entry for each.\n");
        exit(1);
    }
    echo "\nNote: coverage is advisory. Run with --strict to require full coverage.\n";
}

echo "\nExample map integrity OK.\n";
