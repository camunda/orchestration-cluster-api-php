<?php

/**
 * Convert the SDK README and generated surface into Docusaurus-compatible markdown
 * pages for the PHP SDK documentation published at https://docs.camunda.io.
 *
 * This mirrors the sibling SDKs (Rust/C#/Python/TS): the camunda-docs
 * `sync-php-sdk-docs.yaml` workflow runs this generator and copies the output into
 * `docs/apis-tools/php-sdk[.md]`.
 *
 * Input:
 *   README.md                 – guide content (split by H2 into section pages)
 *   generated/src/Api/*.php    – the generated API groups (API reference)
 *   generated/semantic/*.php   – the semantic value objects (domain type system)
 *
 * Output:
 *   docs-md/php-sdk.md                 – landing page (sibling of the section directory)
 *   docs-md/php-sdk/*.md               – per-section guide pages (from README H2s)
 *   docs-md/php-sdk/api-reference/     – API reference pages + _category_.json
 *
 * Usage: php scripts/generate-docusaurus-md.php [--validate-links]
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$readmePath = $root . '/README.md';
$apiDir = $root . '/generated/src/Api';
$semanticDir = $root . '/generated/semantic';
$docsMdDir = $root . '/docs-md';
$sectionDir = $docsMdDir . '/php-sdk';
$apiRefDir = $sectionDir . '/api-reference';

// The SDK ref the docs are generated from. Defaults to `main`, but the docs-sync
// workflow sets `DOCS_SDK_REF` (e.g. `stable/8.10`) when generating versioned docs
// so repository links point at the version whose README was copied, not `main`.
define('GITHUB_REF', getenv('DOCS_SDK_REF') ?: 'main');
define('GITHUB_BLOB', 'https://github.com/camunda/orchestration-cluster-api-php/blob/' . GITHUB_REF);
define('GITHUB_TREE', 'https://github.com/camunda/orchestration-cluster-api-php/tree/' . GITHUB_REF);
const API_REFERENCE_POSITION = 100;
const LANDING_ID = 'php-sdk';
const LANDING_TITLE = 'PHP SDK (Technical Preview)';

// Depth (number of `../` hops) from a page back to the `apis-tools/` root.
const LANDING_PAGE_DEPTH = 1;   // apis-tools/php-sdk.md
const SECTION_PAGE_DEPTH = 2;   // apis-tools/php-sdk/<slug>.md
const API_REFERENCE_DEPTH = 3;  // apis-tools/php-sdk/api-reference/<slug>.md

const URL_PATH_OVERRIDES = [
    'camunda-api-rest' => 'orchestration-cluster-api-rest',
];

const TECH_PREVIEW_BANNER = "\n:::caution Technical Preview\n"
    . "The PHP SDK is a **technical preview**. Its API surface may still evolve and "
    . "changes may not follow semantic versioning. Pin an exact version if you need "
    . "stability.\n"
    . ":::\n";

$validateLinks = in_array('--validate-links', $argv, true);

// ---------------------------------------------------------------------------
// Text helpers
// ---------------------------------------------------------------------------

function escape_yaml(string $s): string
{
    return str_replace('"', '\\"', $s);
}

function escape_table_cell(string $s): string
{
    // A literal `|` (even inside a code span) is parsed as a column delimiter in a
    // GFM table, so escape it before the value is placed into a cell.
    return str_replace('|', '\\|', $s);
}

function code_table_cell(string $s): string
{
    // Render a value as an inline-code table cell that survives a Prettier reformat of
    // the Markdown table. A `\|` escaped inside a backtick code span is still treated
    // as a column delimiter by Prettier (it re-splits the row and adds phantom
    // columns), so emit an HTML <code> cell with |, <, > (and the & they rely on)
    // entity-escaped instead — those entities are never parsed as delimiters.
    $s = str_replace(['&', '<', '>', '|'], ['&amp;', '&lt;', '&gt;', '&#124;'], $s);

    return '<code>' . $s . '</code>';
}

function clean_empty_lines(string $content): string
{
    return preg_replace('/\n{4,}/', "\n\n\n", $content) ?? $content;
}

function strip_cut_sections(string $content): string
{
    return preg_replace('/<!-- docs:cut:start -->.*?<!-- docs:cut:end -->\n?/s', '', $content) ?? $content;
}

function strip_html_comments(string $content): string
{
    // Remove standalone HTML comment lines (docs warning, config-reference markers,
    // snippet-source provenance). The `s` flag makes `.` span newlines so a standalone
    // comment block that wraps across multiple lines is stripped whole, not left behind.
    return preg_replace('/^[ \t]*<!--.*?-->[ \t]*\n/ms', '', $content) ?? $content;
}

function strip_contributing(string $content): string
{
    return preg_replace('/\n## Contributing\b.*/s', '', $content) ?? $content;
}

function slugify(string $title): string
{
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9\s_-]/', '', $slug) ?? $slug;
    $slug = preg_replace('/\s+/', '-', trim($slug)) ?? $slug;
    return preg_replace('/-+/', '-', $slug) ?? $slug;
}

/**
 * @return array{0: string, 1: list<array{0: string, 1: string}>}
 */
function split_by_h2(string $content): array
{
    $parts = preg_split('/(?=^## )/m', $content) ?: [];
    $preamble = $parts[0] ?? '';
    $sections = [];
    foreach (array_slice($parts, 1) as $part) {
        if (preg_match('/^## (.+)\n/', $part, $m) === 1) {
            $sections[] = [trim($m[1]), $part];
        }
    }

    return [$preamble, $sections];
}

/**
 * @param list<array{0: string, 1: string}> $sections
 * @return array<string, string>
 */
function build_anchor_map(array $sections): array
{
    $map = [];
    foreach ($sections as [$title, $body]) {
        $pageSlug = slugify($title);
        if (preg_match_all('/^#{2,6}\s+(.+)$/m', $body, $matches) !== false) {
            foreach ($matches[1] as $heading) {
                $map[slugify(trim($heading))] = $pageSlug;
            }
        }
    }

    return $map;
}

/**
 * @param array<string, string> $anchorMap
 */
function rewrite_internal_anchors(string $content, string $currentSlug, array $anchorMap): string
{
    return preg_replace_callback('/\[([^\]]+)\]\(#([^)]+)\)/', static function (array $m) use ($currentSlug, $anchorMap): string {
        $target = $anchorMap[$m[2]] ?? null;
        if ($target !== null && $target !== $currentSlug) {
            return "[{$m[1]}]({$target}.md#{$m[2]})";
        }
        return $m[0];
    }, $content) ?? $content;
}

function promote_headings(string $content): string
{
    return preg_replace_callback('/^(#{1,6}) (.+)$/m', static function (array $m): string {
        $hashes = $m[1];
        if (strlen($hashes) > 1) {
            return str_repeat('#', strlen($hashes) - 1) . ' ' . $m[2];
        }
        return $m[0];
    }, $content) ?? $content;
}

function rewrite_docs_links(string $content, int $depth): string
{
    $prefix = str_repeat('../', $depth);

    return preg_replace_callback('/\[([^\]]*)\]\(https?:\/\/docs\.camunda\.io\/docs\/(?:next\/)?(.*?)\)/', static function (array $m) use ($prefix): string {
        $urlPath = rtrim($m[2], '/');
        foreach (URL_PATH_OVERRIDES as $old => $new) {
            $urlPath = str_replace($old, $new, $urlPath);
        }
        return "[{$m[1]}]({$prefix}{$urlPath}.md)";
    }, $content) ?? $content;
}

function rewrite_repo_links(string $content): string
{
    return preg_replace_callback('/\[([^\]]+)\]\((?!https?:\/\/|#|mailto:|\.\.\/)([^)\s]+)\)/', static function (array $m): string {
        $target = $m[2];
        if (str_ends_with($target, '.md') && !str_contains($target, '/')) {
            // Sibling generated page — leave alone.
            return $m[0];
        }
        $clean = ltrim($target, './');
        // Directory targets need GitHub's `/tree/` base; file targets need `/blob/`.
        // A trailing slash, or a final path segment with no file extension, marks a directory.
        $base = (str_ends_with($clean, '/') || !str_contains(basename($clean), '.'))
            ? GITHUB_TREE
            : GITHUB_BLOB;

        return "[{$m[1]}]({$base}/{$clean})";
    }, $content) ?? $content;
}

function inject_tech_preview_banner(string $content): string
{
    if (preg_match('/^#\s+.+$/m', $content, $m, PREG_OFFSET_CAPTURE) === 1) {
        $pos = $m[0][1] + strlen($m[0][0]);
        return substr($content, 0, $pos) . "\n" . TECH_PREVIEW_BANNER . substr($content, $pos);
    }

    return $content;
}

function landing_frontmatter(): string
{
    return "---\n"
        . 'id: ' . LANDING_ID . "\n"
        . 'title: "' . escape_yaml(LANDING_TITLE) . "\"\n"
        . 'sidebar_label: "' . escape_yaml(LANDING_TITLE) . "\"\n"
        . "sidebar_position: 1\n"
        . "mdx:\n"
        . "  format: md\n"
        . "---\n\n";
}

function section_frontmatter(string $id, string $title, int $position): string
{
    return "---\n"
        . "id: {$id}\n"
        . 'title: "' . escape_yaml($title) . "\"\n"
        . 'sidebar_label: "' . escape_yaml($title) . "\"\n"
        . "sidebar_position: {$position}\n"
        . "mdx:\n"
        . "  format: md\n"
        . "---\n\n";
}

// ---------------------------------------------------------------------------
// Generated-surface parsing (API reference)
// ---------------------------------------------------------------------------

/**
 * @return list<string>
 */
function operations_of(string $file): array
{
    $text = (string) file_get_contents($file);
    $ops = [];
    if (preg_match_all('/public function (\w+)\(/', $text, $matches) === false) {
        return [];
    }
    foreach ($matches[1] as $name) {
        if (str_ends_with($name, 'WithHttpInfo')
            || str_ends_with($name, 'Async')
            || str_ends_with($name, 'Request')
            || in_array($name, ['__construct', 'getConfig', 'getHostIndex', 'setHostIndex'], true)
        ) {
            continue;
        }
        $ops[$name] = true;
    }

    return array_keys($ops);
}

/**
 * Split a PascalCase API group name into words: "JobApi" -> "Job Api".
 */
function humanize(string $name): string
{
    return trim(preg_replace('/(?<!^)([A-Z])/', ' $1', $name) ?? $name);
}

// ---------------------------------------------------------------------------
// Reset output tree
// ---------------------------------------------------------------------------

function rrmdir(string $dir): void
{
    if (!is_dir($dir)) {
        return;
    }
    foreach (scandir($dir) ?: [] as $entry) {
        if ($entry === '.' || $entry === '..') {
            continue;
        }
        $path = $dir . '/' . $entry;
        is_dir($path) ? rrmdir($path) : unlink($path);
    }
    rmdir($dir);
}

rrmdir($sectionDir);
@unlink($docsMdDir . '/php-sdk.md');
if (!is_dir($apiRefDir) && !mkdir($apiRefDir, 0o755, true) && !is_dir($apiRefDir)) {
    fwrite(STDERR, "Cannot create {$apiRefDir}\n");
    exit(1);
}

// ---------------------------------------------------------------------------
// README-derived pages (landing + sections)
// ---------------------------------------------------------------------------

$content = (string) file_get_contents($readmePath);
$content = strip_cut_sections($content);
$content = strip_html_comments($content);
$content = strip_contributing($content);
$content = clean_empty_lines($content);
$content = preg_replace('/^#\s+.*$/m', '# ' . LANDING_TITLE, $content, 1) ?? $content;
// Badge images add no value in the docs site.
$content = preg_replace('/^\[!\[.*?\)$\n?/m', '', $content) ?? $content;
$content = trim($content) . "\n";

[$preamble, $sections] = split_by_h2($content);
$anchorMap = build_anchor_map($sections);

// Landing page: sibling of the section directory.
$landing = rewrite_docs_links($preamble, LANDING_PAGE_DEPTH);
$landing = rewrite_repo_links($landing);
$landing = inject_tech_preview_banner($landing);
file_put_contents(
    $docsMdDir . '/php-sdk.md',
    landing_frontmatter() . trim(clean_empty_lines($landing)) . "\n",
);
echo "  Wrote landing page docs-md/php-sdk.md\n";

// Section pages: one per H2.
foreach ($sections as $i => [$title, $body]) {
    $slug = slugify($title);
    $position = $i + 2; // landing page is 1
    $sectionContent = rewrite_docs_links($body, SECTION_PAGE_DEPTH);
    $sectionContent = rewrite_internal_anchors($sectionContent, $slug, $anchorMap);
    $sectionContent = rewrite_repo_links($sectionContent);
    $pageContent = promote_headings($sectionContent);
    $pageContent = inject_tech_preview_banner($pageContent);
    file_put_contents(
        $sectionDir . '/' . $slug . '.md',
        section_frontmatter($slug, $title, $position) . trim(clean_empty_lines($pageContent)) . "\n",
    );
    echo "  Wrote section docs-md/php-sdk/{$slug}.md\n";
}

// ---------------------------------------------------------------------------
// API reference pages
// ---------------------------------------------------------------------------

$apiFiles = glob($apiDir . '/*.php') ?: [];
sort($apiFiles);

$groups = [];
$totalOps = 0;
foreach ($apiFiles as $file) {
    $group = basename($file, '.php');
    $ops = operations_of($file);
    sort($ops);
    if ($ops === []) {
        continue;
    }
    $groups[$group] = $ops;
    $totalOps += count($ops);
}

// Overview page.
$index = section_frontmatter('php-sdk-api-reference', 'Overview', 0);
$index .= "# API reference\n\n";
$index .= inject_intro_note();
$index .= sprintf(
    "The SDK exposes **%d operations** across **%d API groups**. Every operation is available "
    . "directly on the client (the flat facade) and through the typed `api()` accessor:\n\n",
    $totalOps,
    count($groups),
);
$index .= "```php\n";
$index .= "\$client = CamundaClient::fromEnvironment();\n\n";
$index .= "// Flat facade — every operation is a method on the client:\n";
$index .= "\$topology = \$client->getTopology();\n\n";
$index .= "// Or via the typed API-group accessor:\n";
$index .= "\$jobs = \$client->api(\\Camunda\\Orchestration\\Api\\Api\\JobApi::class);\n";
$index .= "```\n\n";
$index .= "| API group | Operations |\n| --- | --- |\n";
foreach ($groups as $group => $ops) {
    $slug = slugify(humanize($group));
    $index .= sprintf("| [%s](%s.md) | %d |\n", humanize($group), $slug, count($ops));
}
$index .= sprintf("| [Domain type system](%s.md) | — |\n", 'domain-type-system');
file_put_contents($apiRefDir . '/index.md', $index . "\n");

// One page per API group.
$position = 1;
foreach ($groups as $group => $ops) {
    $title = humanize($group);
    $slug = slugify($title);
    $page = section_frontmatter($slug, $title, $position);
    $page .= "# {$title}\n\n";
    $page .= sprintf("`%s` — %d operations. Call any of these directly on the client, or via ", $group, count($ops));
    $page .= sprintf("`\$client->api(\\Camunda\\Orchestration\\Api\\Api\\%s::class)`.\n\n", $group);
    foreach ($ops as $op) {
        $page .= "- `{$op}()`\n";
    }
    file_put_contents($apiRefDir . '/' . $slug . '.md', $page . "\n");
    ++$position;
}

// Domain type system page (semantic value objects).
$semanticFiles = glob($semanticDir . '/*.php') ?: [];
sort($semanticFiles);

$sem = section_frontmatter('domain-type-system', 'Domain type system', $position);
$sem .= "# Domain type system\n\n";
$sem .= "Each identifier is a distinct, self-validating value object in the "
    . "`Camunda\\Orchestration\\Semantic` namespace. Concrete identifier types "
    . "implement `Stringable` and `JsonSerializable` and expose `::of()`, `->value()`, "
    . "and `->equals()`. `ResourceKey` and `ScopeKey` are factories that lift a raw "
    . "string into the matching concrete type via `::of()`.\n\n";
$sem .= "| Type | Pattern | Length | Description |\n| --- | --- | --- | --- |\n";
foreach ($semanticFiles as $file) {
    $name = basename($file, '.php');
    if ($name === 'SemanticKey') {
        continue;
    }
    $text = (string) file_get_contents($file);
    $pattern = preg_match("/public const PATTERN = '(.+?)';/", $text, $m) === 1 ? code_table_cell($m[1]) : '—';
    $min = preg_match('/must be at least (\d+) character/', $text, $m) === 1 ? (int) $m[1] : null;
    $max = preg_match('/must be at most (\d+) character/', $text, $m) === 1 ? (int) $m[1] : null;
    $length = match (true) {
        $min !== null && $max !== null => "{$min}–{$max}",
        $max !== null => "≤ {$max}",
        $min !== null => "≥ {$min}",
        default => '—',
    };
    $desc = '';
    if (preg_match('/\/\*\*\s*\n\s*\*\s*(.+?)\s*\n/', $text, $m) === 1) {
        $desc = escape_table_cell(trim($m[1]));
    }
    $sem .= "| `{$name}` | {$pattern} | {$length} | {$desc} |\n";
}
file_put_contents($apiRefDir . '/domain-type-system.md', $sem . "\n");

// API Reference category metadata.
file_put_contents(
    $apiRefDir . '/_category_.json',
    json_encode(['label' => 'API Reference', 'position' => API_REFERENCE_POSITION], JSON_PRETTY_PRINT) . "\n",
);

echo sprintf(
    "  Wrote API reference: index + %d groups + domain-type-system (%d operations)\n",
    count($groups),
    $totalOps,
);

function inject_intro_note(): string
{
    return ":::caution Technical Preview\n"
        . "The PHP SDK is a **technical preview**. Its API surface may still evolve and "
        . "changes may not follow semantic versioning. Pin an exact version if you need "
        . "stability.\n:::\n\n";
}

// ---------------------------------------------------------------------------
// Optional link validation
// ---------------------------------------------------------------------------

if ($validateLinks) {
    $broken = [];
    $pages = array_merge(
        [$docsMdDir . '/php-sdk.md'],
        glob($sectionDir . '/*.md') ?: [],
        glob($apiRefDir . '/*.md') ?: [],
    );
    foreach ($pages as $page) {
        $text = (string) file_get_contents($page);
        if (preg_match_all('/\[([^\]]*)\]\((?!https?:\/\/|#|mailto:)([^)]+)\)/', $text, $matches, PREG_SET_ORDER) === false) {
            continue;
        }
        foreach ($matches as $m) {
            [$target] = explode('#', $m[2], 2);
            if ($target === '' || str_starts_with($target, '../')) {
                // Empty (pure anchor) or cross-site docs link — valid only once
                // the page is published into camunda-docs, so skip.
                continue;
            }
            $resolved = realpath(dirname($page) . '/' . $target);
            if ($resolved === false) {
                $broken[] = basename($page) . " -> {$m[2]}";
            }
        }
    }
    if ($broken !== []) {
        fwrite(STDERR, "Broken relative links:\n  - " . implode("\n  - ", $broken) . "\n");
        exit(1);
    }
    echo "  Link validation passed.\n";
}
