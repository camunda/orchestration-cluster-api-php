<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Scripts;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

/**
 * @param list<string> $argv
 * @return array{0: string, 1: list<string>}
 */
function split_root_argument(array $argv, string $defaultRoot): array
{
    $root = $defaultRoot;
    $arguments = [];

    for ($i = 1, $count = count($argv); $i < $count; ++$i) {
        $argument = $argv[$i];
        if ($argument !== '--root') {
            $arguments[] = $argument;
            continue;
        }

        if (!isset($argv[++$i]) || str_starts_with($argv[$i], '--')) {
            throw new RuntimeException('--root requires a repository path.');
        }
        $root = $argv[$i];
    }

    $resolvedRoot = realpath($root);
    if ($resolvedRoot === false || !is_dir($resolvedRoot)) {
        throw new RuntimeException("Repository root does not exist: $root");
    }

    return [$resolvedRoot, $arguments];
}

/**
 * @return list<string>
 */
function example_php_files(string $root): array
{
    $examplesDir = $root . '/examples';
    if (!is_dir($examplesDir)) {
        throw new RuntimeException("Examples directory does not exist: $examplesDir");
    }

    $files = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($examplesDir, FilesystemIterator::SKIP_DOTS),
    );
    foreach ($iterator as $file) {
        if (!$file->isFile() || !str_ends_with($file->getFilename(), '.php')) {
            continue;
        }
        $files[] = $file->getPathname();
    }

    sort($files, SORT_STRING);

    return $files;
}

function read_text_file(string $path): string
{
    $text = file_get_contents($path);
    if ($text === false) {
        throw new RuntimeException("Cannot read $path");
    }

    return str_replace("\r\n", "\n", $text);
}

function relative_to_root(string $root, string $path): string
{
    $prefix = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    if (!str_starts_with($path, $prefix)) {
        throw new RuntimeException("Path is outside the repository root: $path");
    }

    return str_replace(DIRECTORY_SEPARATOR, '/', substr($path, strlen($prefix)));
}

function dedent(string $block): string
{
    $lines = explode("\n", $block);
    $indent = null;

    foreach ($lines as $line) {
        if (trim($line) === '') {
            continue;
        }
        preg_match('/^[ \t]*/', $line, $matches);
        $leading = strlen($matches[0]);
        $indent = $indent === null ? $leading : min($indent, $leading);
    }

    if ($indent === null || $indent === 0) {
        return trim($block, "\n");
    }

    $dedented = array_map(
        static fn (string $line): string => substr($line, $indent),
        $lines,
    );

    return trim(implode("\n", $dedented), "\n");
}

/**
 * @return array<string, string>
 */
function regions_in_file(string $path): array
{
    $regions = [];
    $current = null;
    $buffer = [];

    foreach (explode("\n", read_text_file($path)) as $lineNumber => $line) {
        if (preg_match('/^\s*\/\/\s*region\s+([\w.-]+)\s*$/', $line, $matches) === 1) {
            if ($current !== null) {
                throw new RuntimeException(
                    "Nested region '{$matches[1]}' at $path:" . ($lineNumber + 1)
                    . "; close '$current' first."
                );
            }
            $current = $matches[1];
            $buffer = [];
            continue;
        }

        if (preg_match('/^\s*\/\/\s*endregion\s+([\w.-]+)\s*$/', $line, $matches) === 1) {
            if ($current === null) {
                throw new RuntimeException("Unexpected endregion '{$matches[1]}' at $path:" . ($lineNumber + 1));
            }
            if ($current !== $matches[1]) {
                throw new RuntimeException(
                    "Mismatched endregion '{$matches[1]}' at $path:" . ($lineNumber + 1)
                    . "; expected '$current'."
                );
            }
            if (isset($regions[$current])) {
                throw new RuntimeException("Duplicate region '$current' in $path.");
            }
            $regions[$current] = dedent(implode("\n", $buffer));
            $current = null;
            $buffer = [];
            continue;
        }

        if ($current !== null) {
            $buffer[] = $line;
        }
    }

    if ($current !== null) {
        throw new RuntimeException("Unclosed region '$current' in $path.");
    }

    return $regions;
}

/**
 * @return array<string, array{content: string, source: string}>
 */
function all_example_regions(string $root): array
{
    $regions = [];

    foreach (example_php_files($root) as $path) {
        $source = relative_to_root($root, $path);
        foreach (regions_in_file($path) as $name => $content) {
            if (isset($regions[$name])) {
                throw new RuntimeException(
                    "Duplicate region '$name' in {$regions[$name]['source']} and $source."
                );
            }
            $regions[$name] = ['content' => $content, 'source' => $source];
        }
    }

    return $regions;
}

function resolve_snippet_source(string $root, string $source): string
{
    $source = str_replace('\\', '/', trim($source));
    if (preg_match('#^examples/(?:[A-Za-z0-9_.-]+/)*[A-Za-z0-9_.-]+\.php$#D', $source) !== 1) {
        throw new RuntimeException("Snippet source must be a PHP file below examples/: $source");
    }

    $examplesRoot = realpath($root . '/examples');
    $resolved = realpath($root . '/' . $source);
    if ($examplesRoot === false || $resolved === false || !is_file($resolved)) {
        throw new RuntimeException("Snippet source does not exist: $source");
    }

    $examplesPrefix = rtrim($examplesRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    if (!str_starts_with($resolved, $examplesPrefix)) {
        throw new RuntimeException("Snippet source is outside examples/: $source");
    }

    return relative_to_root($root, $resolved);
}

function resolve_mapped_example_file(string $root, string $file): string
{
    $file = str_replace('\\', '/', trim($file));
    if ($file === '' || str_starts_with($file, '/') || str_starts_with($file, 'examples/')) {
        throw new RuntimeException("Example map file must be relative to examples/: $file");
    }

    return substr(resolve_snippet_source($root, 'examples/' . $file), strlen('examples/'));
}
