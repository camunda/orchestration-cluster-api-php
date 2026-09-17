<?php

/**
 * Synchronize code snippets in README.md from the compilable example files.
 *
 * Replaces the code block that follows each snippet marker in README.md with the
 * corresponding region-tagged code extracted from examples/*.php.
 *
 * Region tags in .php files use `// region RegionName` ... `// endregion RegionName`.
 * Markers in README.md use:
 *
 *     <!-- snippet-source: examples/readme.php | regions: RegionName -->
 *
 * Composite regions: `regions: A+B` concatenates multiple regions (blank-separated).
 *
 * Usage:
 *   php scripts/sync-readme-snippets.php           # update README.md in-place
 *   php scripts/sync-readme-snippets.php --check    # CI mode: exit 1 if out of sync
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$readmePath = $root . '/README.md';
$examplesDir = $root . '/examples';

$check = in_array('--check', $argv, true);

/**
 * Extract region-tagged code blocks from a PHP example file.
 *
 * @return array<string, string>
 */
function parse_region_tags(string $path): array
{
    $text = file_get_contents($path);
    if ($text === false) {
        return [];
    }

    $regions = [];
    $current = null;
    $buffer = [];
    foreach (explode("\n", $text) as $line) {
        $trimmed = trim($line);
        if (preg_match('/^\/\/\s*region\s+(\w+)\s*$/', $trimmed, $m) === 1) {
            $current = $m[1];
            $buffer = [];
        } elseif (preg_match('/^\/\/\s*endregion\s+(\w+)\s*$/', $trimmed, $m) === 1 && $current === $m[1]) {
            $regions[$current] = dedent(implode("\n", $buffer));
            $current = null;
            $buffer = [];
        } elseif ($current !== null) {
            $buffer[] = $line;
        }
    }

    return $regions;
}

function dedent(string $block): string
{
    $lines = explode("\n", $block);
    $indent = null;
    foreach ($lines as $line) {
        if (trim($line) === '') {
            continue;
        }
        preg_match('/^[ \t]*/', $line, $m);
        $lead = strlen($m[0]);
        $indent = $indent === null ? $lead : min($indent, $lead);
    }
    if ($indent === null || $indent === 0) {
        return trim($block, "\n");
    }
    $out = array_map(
        static fn (string $line): string => substr($line, $indent) !== false ? substr($line, $indent) : $line,
        $lines,
    );

    return trim(implode("\n", $out), "\n");
}

/**
 * @param array<string, array<string, string>> $cache
 */
function load_regions(string $file, string $examplesDir, array &$cache): array
{
    if (!isset($cache[$file])) {
        $cache[$file] = parse_region_tags($examplesDir . '/' . basename($file));
    }

    return $cache[$file];
}

$readme = file_get_contents($readmePath);
if ($readme === false) {
    fwrite(STDERR, "Cannot read README.md\n");
    exit(1);
}

$cache = [];
$pattern = '/(<!-- snippet-source:\s*(\S+)\s*\|\s*regions:\s*([\w+]+)\s*-->\n)```php\n.*?\n```/s';

$updated = preg_replace_callback($pattern, static function (array $match) use ($examplesDir, &$cache): string {
    [$full, $marker, $file, $regionSpec] = $match;
    $regions = load_regions($file, $examplesDir, $cache);

    $parts = [];
    foreach (explode('+', $regionSpec) as $name) {
        if (!isset($regions[$name])) {
            fwrite(STDERR, "warning: region '$name' not found in $file\n");
            continue;
        }
        $parts[] = $regions[$name];
    }
    $code = implode("\n\n", $parts);

    return $marker . "```php\n" . $code . "\n```";
}, $readme);

if ($updated === null) {
    fwrite(STDERR, "Failed to process README.md\n");
    exit(1);
}

if ($check) {
    if ($updated !== $readme) {
        fwrite(STDERR, "README.md is out of sync with examples/. Run: php scripts/sync-readme-snippets.php\n");
        exit(1);
    }
    echo "README.md snippets are in sync.\n";
    exit(0);
}

if ($updated !== $readme) {
    file_put_contents($readmePath, $updated);
    echo "Updated README.md snippets.\n";
} else {
    echo "README.md snippets already up to date.\n";
}
