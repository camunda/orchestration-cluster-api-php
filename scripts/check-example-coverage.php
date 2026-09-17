<?php

/**
 * Verify operation-to-example coverage.
 *
 * Usage:
 *   php scripts/check-example-coverage.php [--strict] [--root <repository-root>]
 */

declare(strict_types=1);

require_once __DIR__ . '/ExampleSupport.php';

use function Camunda\Orchestration\Scripts\all_example_regions;
use function Camunda\Orchestration\Scripts\read_text_file;
use function Camunda\Orchestration\Scripts\resolve_mapped_example_file;
use function Camunda\Orchestration\Scripts\split_root_argument;

/**
 * @return array<string, true>
 */
function spec_operations(string $path): array
{
    try {
        $metadata = json_decode(read_text_file($path), true, flags: JSON_THROW_ON_ERROR);
    } catch (JsonException $error) {
        throw new RuntimeException("Cannot parse spec metadata: {$error->getMessage()}", 0, $error);
    }

    if (!is_array($metadata) || !is_array($metadata['operations'] ?? null)) {
        throw new RuntimeException("Spec metadata has no operations list: $path");
    }

    $operations = [];
    foreach ($metadata['operations'] as $operation) {
        $operationId = is_array($operation) ? $operation['operationId'] ?? null : null;
        if (!is_string($operationId) || $operationId === '') {
            throw new RuntimeException("Spec metadata contains an operation without an operationId: $path");
        }
        if (isset($operations[$operationId])) {
            throw new RuntimeException("Spec metadata contains duplicate operationId '$operationId'.");
        }
        $operations[$operationId] = true;
    }

    return $operations;
}

/**
 * @return array<string, mixed>
 */
function operation_map(string $path): array
{
    try {
        $map = json_decode(read_text_file($path), true, flags: JSON_THROW_ON_ERROR);
    } catch (JsonException $error) {
        throw new RuntimeException("Cannot parse operation map: {$error->getMessage()}", 0, $error);
    }

    if (!is_array($map)) {
        throw new RuntimeException("Operation map is not an object: $path");
    }

    /** @var array<string, mixed> $map */
    return $map;
}

/**
 * @param array<string, true> $operations
 * @param array<string, mixed> $map
 * @param array<string, array{content: string, source: string}> $regions
 * @return list<string>
 */
function integrity_errors(string $root, array $operations, array $map, array $regions): array
{
    $errors = [];

    foreach ($map as $operationId => $entries) {
        if (!is_string($operationId) || !isset($operations[$operationId])) {
            $errors[] = "'$operationId' is not a known exact OpenAPI operationId.";
        }
        if (!is_array($entries) || !array_is_list($entries) || $entries === []) {
            $errors[] = "'$operationId' must map to a non-empty list of examples.";
            continue;
        }

        foreach ($entries as $entry) {
            if (!is_array($entry)) {
                $errors[] = "'$operationId' has a non-object example entry.";
                continue;
            }

            $file = $entry['file'] ?? null;
            $region = $entry['region'] ?? null;
            if (!is_string($file) || $file === '') {
                $errors[] = "'$operationId' has an entry without a file.";
                continue;
            }
            if (!is_string($region) || $region === '') {
                $errors[] = "'$operationId' has an entry without a region.";
                continue;
            }

            try {
                $resolvedFile = resolve_mapped_example_file($root, $file);
            } catch (RuntimeException $error) {
                $errors[] = "'$operationId' has an invalid example file '$file': {$error->getMessage()}";
                continue;
            }

            if (!isset($regions[$region])) {
                $errors[] = "'$operationId' references missing region '$region'.";
                continue;
            }

            $actualFile = substr($regions[$region]['source'], strlen('examples/'));
            if ($actualFile !== $resolvedFile) {
                $errors[] = "'$operationId' region '$region' is in $actualFile, not $file.";
            }
        }
    }

    return $errors;
}

/**
 * @param list<string> $argv
 */
function main(array $argv): int
{
    try {
        [$root, $arguments] = split_root_argument($argv, dirname(__DIR__));
        $strict = false;
        foreach ($arguments as $argument) {
            if ($argument === '--strict') {
                $strict = true;
                continue;
            }
            throw new RuntimeException("Unknown argument: $argument");
        }

        $operations = spec_operations($root . '/external-spec/bundled/spec-metadata.json');
        $map = operation_map($root . '/examples/operation-map.json');
        $regions = all_example_regions($root);
        $errors = integrity_errors($root, $operations, $map, $regions);

        if ($errors !== []) {
            fwrite(STDERR, "Example map integrity errors:\n  - " . implode("\n  - ", $errors) . "\n");
            return 1;
        }

        $covered = array_intersect_key($operations, $map);
        $missing = array_diff_key($operations, $map);
        $total = count($operations);
        $coveredCount = count($covered);
        $percentage = $total === 0 ? 100 : (int) round($coveredCount / $total * 100);

        printf("Spec operations: %d\n", $total);
        printf("Covered:         %d\n", $coveredCount);
        printf("Missing:         %d\n", count($missing));
        printf("Coverage:        %d%%\n", $percentage);

        if ($missing === []) {
            echo "\nExample coverage is complete.\n";
            return 0;
        }

        if (!$strict) {
            echo "\nCoverage is advisory until the final example-coverage PR. Run with --strict to require completion.\n";
            return 0;
        }

        $missingIds = array_keys($missing);
        sort($missingIds, SORT_STRING);
        fwrite(STDERR, "\nMissing examples for:\n  - " . implode("\n  - ", $missingIds) . "\n");

        return 1;
    } catch (RuntimeException $error) {
        fwrite(STDERR, $error->getMessage() . "\n");
        return 1;
    }
}

exit(main($argv));
