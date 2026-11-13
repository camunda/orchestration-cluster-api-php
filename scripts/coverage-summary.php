<?php

declare(strict_types=1);

/**
 * Print a per-file line-coverage table for the hand-written runtime from a PHPUnit Clover report,
 * as Markdown. When the GITHUB_STEP_SUMMARY environment variable is set (GitHub Actions) the same
 * table is appended to the job summary so the coverage gap is visible on every run.
 *
 * Usage: php scripts/coverage-summary.php build/coverage/clover.xml
 */

$path = $argv[1] ?? 'build/coverage/clover.xml';
if (!is_file($path)) {
    fwrite(STDERR, "Clover report not found: $path (run `make coverage` first)\n");
    exit(1);
}

$xml = simplexml_load_file($path);
if ($xml === false) {
    fwrite(STDERR, "Cannot parse Clover report: $path\n");
    exit(1);
}

$root = dirname(__DIR__) . '/';
$rows = [];
$totalStatements = 0;
$totalCovered = 0;
foreach ($xml->xpath('//file') ?: [] as $file) {
    $name = (string) $file['name'];
    $relative = str_starts_with($name, $root) ? substr($name, strlen($root)) : $name;
    $statements = 0;
    $covered = 0;
    foreach ($file->line as $line) {
        if ((string) $line['type'] !== 'stmt') {
            continue;
        }
        ++$statements;
        if ((int) $line['count'] > 0) {
            ++$covered;
        }
    }
    if ($statements === 0) {
        continue; // interfaces, exceptions and other files without executable statements
    }
    $rows[$relative] = [$covered, $statements];
    $totalStatements += $statements;
    $totalCovered += $covered;
}
ksort($rows);

$percent = static fn (int $covered, int $statements): string => sprintf('%.1f%%', $statements === 0 ? 100.0 : 100 * $covered / $statements);

$out = "### Hand-written runtime line coverage (`src/`, generated code excluded)\n\n";
$out .= "| File | Covered | Statements | Coverage |\n|---|---:|---:|---:|\n";
foreach ($rows as $file => [$covered, $statements]) {
    $out .= sprintf("| `%s` | %d | %d | %s |\n", $file, $covered, $statements, $percent($covered, $statements));
}
$out .= sprintf(
    "| **Total** | **%d** | **%d** | **%s** |\n",
    $totalCovered,
    $totalStatements,
    $percent($totalCovered, $totalStatements),
);

echo $out;

$summaryFile = getenv('GITHUB_STEP_SUMMARY');
if (is_string($summaryFile) && $summaryFile !== '') {
    file_put_contents($summaryFile, $out . "\n", FILE_APPEND);
}
