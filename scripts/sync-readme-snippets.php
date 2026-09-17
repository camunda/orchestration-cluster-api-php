<?php

/**
 * Synchronize README PHP snippets from region-tagged examples.
 *
 * Usage:
 *   php scripts/sync-readme-snippets.php [--check] [--root <repository-root>]
 */

declare(strict_types=1);

require_once __DIR__ . '/ExampleSupport.php';

use function Camunda\Orchestration\Scripts\all_example_regions;
use function Camunda\Orchestration\Scripts\resolve_snippet_source;
use function Camunda\Orchestration\Scripts\read_text_file;
use function Camunda\Orchestration\Scripts\split_root_argument;

/**
 * @return array{regions: string, sources: string|null}
 */
function snippet_marker(string $line): ?array
{
    if (preg_match(
        '/^\s*<!--\s*snippet-source:\s*([^|]+?)\s*\|\s*regions:\s*([\w.+-]+)\s*-->\s*$/',
        $line,
        $matches,
    ) === 1) {
        return ['regions' => $matches[2], 'sources' => $matches[1]];
    }

    if (preg_match('/^\s*<!--\s*snippet:([\w.+-]+)\s*-->\s*$/', $line, $matches) === 1) {
        return ['regions' => $matches[1], 'sources' => null];
    }

    return null;
}

/**
 * @param array<string, array{content: string, source: string}> $regions
 * @param array{regions: string, sources: string|null} $marker
 * @return array{content: string, marker: string}
 */
function resolve_regions(string $root, array $regions, array $marker): array
{
    $parts = explode('+', $marker['regions']);
    $content = [];
    $sources = [];

    foreach ($parts as $name) {
        if (!isset($regions[$name])) {
            throw new RuntimeException("Region '$name' does not exist in examples/.");
        }
        $content[] = $regions[$name]['content'];
        $sources[$regions[$name]['source']] = $regions[$name]['source'];
    }

    $expectedSources = array_values($sources);
    sort($expectedSources, SORT_STRING);

    if ($marker['sources'] !== null) {
        $declaredSources = [];
        foreach (explode(',', $marker['sources']) as $source) {
            $resolved = resolve_snippet_source($root, $source);
            $declaredSources[$resolved] = $resolved;
        }
        $declaredSources = array_values($declaredSources);
        sort($declaredSources, SORT_STRING);

        if ($declaredSources !== $expectedSources) {
            throw new RuntimeException(
                "Snippet source does not match the region source for '{$marker['regions']}'."
            );
        }
    }

    return [
        'content' => implode("\n\n", $content),
        'marker' => '<!-- snippet-source: ' . implode(',', $expectedSources)
            . ' | regions: ' . $marker['regions'] . ' -->',
    ];
}

/**
 * @param list<string> $lines
 * @return list<int>
 */
function uninjected_php_blocks(array $lines): array
{
    $uninjected = [];

    foreach ($lines as $index => $line) {
        if (preg_match('/^\s*```php\s*$/i', $line) !== 1) {
            continue;
        }

        $previous = $index - 1;
        while ($previous >= 0 && trim($lines[$previous]) === '') {
            --$previous;
        }
        if ($previous >= 0
            && (snippet_marker($lines[$previous]) !== null
                || preg_match('/^\s*<!--\s*snippet-exempt:\s*.+?-->\s*$/', $lines[$previous]) === 1)
        ) {
            continue;
        }

        $uninjected[] = $index + 1;
    }

    return $uninjected;
}

/**
 * @param array<string, array{content: string, source: string}> $regions
 * @return array{content: string, snippets: int}
 */
function synchronize_readme(string $root, string $readme, array $regions): array
{
    $lines = explode("\n", $readme);
    $output = [];
    $errors = [];
    $snippetCount = 0;

    for ($index = 0, $count = count($lines); $index < $count;) {
        $marker = snippet_marker($lines[$index]);
        if ($marker === null) {
            $output[] = $lines[$index];
            ++$index;
            continue;
        }

        try {
            $resolved = resolve_regions($root, $regions, $marker);
        } catch (RuntimeException $error) {
            $errors[] = 'line ' . ($index + 1) . ': ' . $error->getMessage();
            $output[] = $lines[$index];
            ++$index;
            continue;
        }

        ++$snippetCount;
        $output[] = $resolved['marker'];
        ++$index;

        while ($index < $count && trim($lines[$index]) === '') {
            $output[] = $lines[$index];
            ++$index;
        }

        if ($index >= $count || preg_match('/^\s*```php\s*$/i', $lines[$index]) !== 1) {
            $errors[] = 'line ' . ($index + 1)
                . ": snippet '{$marker['regions']}' must be followed by a PHP code fence.";
            if ($index < $count) {
                $output[] = $lines[$index];
            }
            ++$index;
            continue;
        }

        $fence = trim($lines[$index]);
        $closing = $index + 1;
        while ($closing < $count && trim($lines[$closing]) !== '```') {
            ++$closing;
        }
        if ($closing >= $count) {
            $errors[] = "line " . ($index + 1) . ": snippet '{$marker['regions']}' has no closing code fence.";
            $output[] = $lines[$index];
            ++$index;
            continue;
        }

        $output[] = $fence . "\n" . $resolved['content'] . "\n```";
        $index = $closing + 1;
    }

    if ($errors !== []) {
        throw new RuntimeException("README snippet errors:\n  - " . implode("\n  - ", $errors));
    }

    $content = implode("\n", $output);
    $uninjected = uninjected_php_blocks(explode("\n", $content));
    if ($uninjected !== []) {
        throw new RuntimeException(
            'PHP code blocks without a snippet source or exemption at README lines: '
            . implode(', ', $uninjected)
            . '. Add a snippet-source marker or <!-- snippet-exempt: reason -->.'
        );
    }

    return ['content' => $content, 'snippets' => $snippetCount];
}

/**
 * @param list<string> $argv
 */
function main(array $argv): int
{
    try {
        [$root, $arguments] = split_root_argument($argv, dirname(__DIR__));
        $check = false;
        foreach ($arguments as $argument) {
            if ($argument === '--check') {
                $check = true;
                continue;
            }
            throw new RuntimeException("Unknown argument: $argument");
        }

        $readmePath = $root . '/README.md';
        $readme = read_text_file($readmePath);
        $result = synchronize_readme($root, $readme, all_example_regions($root));

        if ($check) {
            if ($result['content'] !== $readme) {
                fwrite(STDERR, "README.md is out of sync with examples/. Run: php scripts/sync-readme-snippets.php\n");
                return 1;
            }
            echo "README.md snippets are in sync ({$result['snippets']} snippets).\n";
            return 0;
        }

        if ($result['content'] === $readme) {
            echo "README.md snippets are already in sync ({$result['snippets']} snippets).\n";
            return 0;
        }

        if (file_put_contents($readmePath, $result['content']) === false) {
            throw new RuntimeException('Cannot write README.md');
        }
        echo "Updated README.md snippets ({$result['snippets']} snippets).\n";

        return 0;
    } catch (RuntimeException $error) {
        fwrite(STDERR, $error->getMessage() . "\n");
        return 1;
    }
}

exit(main($argv));
