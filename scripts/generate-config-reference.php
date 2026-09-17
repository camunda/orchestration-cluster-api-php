<?php

/**
 * Generate the configuration reference table in README.md.
 *
 * The table is derived from {@see \Camunda\Orchestration\Config\ConfigResolver::configReference()}
 * — the single source of truth for supported environment variables — and injected
 * between marker comments so the README can never drift from the code.
 *
 * Usage:
 *   php scripts/generate-config-reference.php           # rewrite README.md
 *   php scripts/generate-config-reference.php --check    # CI mode: exit 1 if out of date
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Camunda\Orchestration\Config\ConfigResolver;

$root = dirname(__DIR__);
$readmePath = $root . '/README.md';
$check = in_array('--check', $argv, true);

const BEGIN_MARKER = '<!-- BEGIN_CONFIG_REFERENCE -->';
const END_MARKER = '<!-- END_CONFIG_REFERENCE -->';

/**
 * @param list<array{variable: string, default: string, description: string}> $rows
 */
function build_table(array $rows): string
{
    $lines = ['| Variable | Default | Description |', '| --- | --- | --- |'];
    foreach ($rows as $row) {
        $default = $row['default'] === '' ? '—' : '`' . $row['default'] . '`';
        $lines[] = sprintf('| `%s` | %s | %s |', $row['variable'], $default, $row['description']);
    }

    return implode("\n", $lines);
}

function inject(string $readme, string $table): string
{
    $begin = strpos($readme, BEGIN_MARKER);
    $end = strpos($readme, END_MARKER);

    if ($begin === false || $end === false || $end < $begin) {
        fwrite(STDERR, "ERROR: Could not find config-reference markers in README.md.\n"
            . 'Add ' . BEGIN_MARKER . ' and ' . END_MARKER . " where the table should appear.\n");
        exit(1);
    }

    $before = substr($readme, 0, $begin + strlen(BEGIN_MARKER));
    $after = substr($readme, $end);

    return $before . "\n\n" . $table . "\n\n" . $after;
}

$readme = file_get_contents($readmePath);
if ($readme === false) {
    fwrite(STDERR, "ERROR: Unable to read README.md\n");
    exit(1);
}

$table = build_table(ConfigResolver::configReference());
$updated = inject($readme, $table);

if ($check) {
    if ($readme !== $updated) {
        fwrite(STDERR, "ERROR: Configuration reference in README.md is out of date.\n"
            . "Run: make config-reference\n");
        exit(1);
    }
    echo "OK: Configuration reference is up to date.\n";
    exit(0);
}

if ($readme === $updated) {
    echo "Configuration reference already up to date.\n";
    exit(0);
}

file_put_contents($readmePath, $updated);
echo "Updated configuration reference in README.md.\n";
