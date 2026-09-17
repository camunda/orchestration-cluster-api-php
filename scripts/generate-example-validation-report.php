<?php

/**
 * Generate the self-contained HTML proof report for the live example showcase.
 *
 * Usage: php scripts/generate-example-validation-report.php
 */

declare(strict_types=1);

require_once __DIR__ . '/ExampleSupport.php';
require_once __DIR__ . '/../examples/advanced/internal/ShowcaseCatalog.php';

$root = dirname(__DIR__);
$scenarios = \Camunda\Orchestration\Examples\Advanced\Internal\ShowcaseCatalog::load($root);
$sourceScenarios = \Camunda\Orchestration\Examples\Advanced\Internal\ShowcaseCatalog::scenarioForExampleSource($scenarios);
$regions = \Camunda\Orchestration\Scripts\all_example_regions($root);
$results = load_results($root);

/** @var array<string, list<array{name: string, source: string, content: string}>> $regionsByScenario */
$regionsByScenario = array_fill_keys(array_column($scenarios, 'id'), []);
foreach ($regions as $name => $region) {
    $scenario = $sourceScenarios[basename($region['source'])] ?? null;
    if ($scenario === null) {
        throw new \RuntimeException("Snippet '$name' has no scenario assignment.");
    }
    $regionsByScenario[$scenario][] = [
        'name' => $name,
        'source' => $region['source'],
        'content' => $region['content'],
    ];
}
foreach ($regionsByScenario as &$scenarioRegions) {
    usort($scenarioRegions, static fn (array $left, array $right): int => [$left['source'], $left['name']] <=> [$right['source'], $right['name']]);
}
unset($scenarioRegions);

$html = render_report($scenarios, $regionsByScenario, $results, count($regions));
$output = $root . '/docs/example-validation.html';
if (!is_dir(dirname($output)) && !mkdir(dirname($output), 0o755, true) && !is_dir(dirname($output))) {
    fwrite(STDERR, "Cannot create report directory: " . dirname($output) . "\n");
    exit(1);
}
if (file_put_contents($output, $html) === false) {
    fwrite(STDERR, "Cannot write $output\n");
    exit(1);
}

fwrite(STDOUT, 'Wrote docs/example-validation.html (' . count($regions) . " snippets).\n");

/**
 * @return array{run: array<string, mixed>|null, snippets: array<string, mixed>|null, scenarios: array<string, array<string, mixed>>}
 */
function load_results(string $root): array
{
    $path = $root . '/docs/example-validation-results.json';
    if (!is_file($path)) {
        return ['run' => null, 'snippets' => null, 'scenarios' => []];
    }

    $contents = file_get_contents($path);
    if ($contents === false) {
        throw new \RuntimeException("Cannot read showcase results: $path");
    }
    try {
        $decoded = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
    } catch (\JsonException $error) {
        throw new \RuntimeException("Cannot parse showcase results: {$error->getMessage()}", 0, $error);
    }
    if (!is_array($decoded) || ($decoded['schemaVersion'] ?? null) !== 1) {
        throw new \RuntimeException('Showcase results must use schemaVersion 1.');
    }

    $scenarioResults = [];
    foreach ($decoded['scenarios'] ?? [] as $scenario) {
        if (is_array($scenario) && is_string($scenario['id'] ?? null)) {
            $scenarioResults[$scenario['id']] = $scenario;
        }
    }

    return [
        'run' => is_array($decoded['run'] ?? null) ? $decoded['run'] : null,
        'snippets' => is_array($decoded['snippets'] ?? null) ? $decoded['snippets'] : null,
        'scenarios' => $scenarioResults,
    ];
}

/**
 * @param list<array{id: string, title: string, summary: string, command: string, sources: list<string>}> $scenarios
 * @param array<string, list<array{name: string, source: string, content: string}>> $regionsByScenario
 * @param array{run: array<string, mixed>|null, snippets: array<string, mixed>|null, scenarios: array<string, array<string, mixed>>} $results
 */
function render_report(array $scenarios, array $regionsByScenario, array $results, int $snippetCount): string
{
    $run = is_array($results['run']) ? $results['run'] : null;
    $cluster = is_array($run['cluster'] ?? null) ? $run['cluster'] : [];
    $runSummary = $run === null
        ? 'No live showcase result has been recorded yet.'
        : 'Recorded against ' . html((string) ($cluster['restAddress'] ?? 'unknown endpoint'))
            . ' (Camunda ' . html((string) ($cluster['gatewayVersion'] ?? 'unknown version')) . ').';
    $snippetSummary = $results['snippets'] === null
        ? 'Not run'
        : html((string) ($results['snippets']['status'] ?? 'unknown'))
            . ' - ' . html((string) ($results['snippets']['summary'] ?? ''));

    $cards = '';
    $sections = '';
    foreach ($scenarios as $index => $scenario) {
        $result = $results['scenarios'][$scenario['id']] ?? [];
        $status = (string) ($result['status'] ?? 'NOT RUN');
        $statusClass = status_class($status);
        $resultEntries = is_array($result['results'] ?? null) ? $result['results'] : [];
        $ordinal = sprintf('%02d', $index + 1);
        $scenarioId = html($scenario['id']);
        $title = html($scenario['title']);
        $summary = html($scenario['summary']);
        $command = html($scenario['command']);
        $sourceCount = count($scenario['sources']);
        $regionCount = count($regionsByScenario[$scenario['id']]);
        $duration = is_int($result['durationMs'] ?? null) ? $result['durationMs'] . ' ms' : 'not run';
        $outcome = html((string) ($result['summary'] ?? 'Run this scenario to collect live evidence.'));

        $cards .= <<<HTML
          <article class="scenario-card">
            <span class="ordinal">{$ordinal}</span>
            <h3>{$title}</h3>
            <p>{$summary}</p>
            <div class="badges"><span class="status {$statusClass}">{$status}</span><span>{$regionCount} snippets</span><span>{$duration}</span></div>
            <code>{$command}</code>
          </article>
        HTML;

        $sourceList = implode('', array_map(
            static fn (string $source): string => '<li><code>examples/' . html($source) . '</code></li>',
            $scenario['sources'],
        ));
        $resultRows = '';
        foreach ($resultEntries as $entry) {
            if (!is_array($entry)) {
                continue;
            }
            $entryStatus = (string) ($entry['status'] ?? 'UNKNOWN');
            $resultRows .= '<tr><td><code>' . html((string) ($entry['function'] ?? 'unknown')) . '</code><br><small>'
                . html((string) ($entry['source'] ?? '')) . '</small></td><td><span class="status '
                . status_class($entryStatus) . '">' . html($entryStatus) . '</span></td><td>'
                . html((string) ($entry['durationMs'] ?? 0)) . ' ms</td><td>'
                . html((string) ($entry['evidence'] ?? '')) . '</td></tr>';
        }
        $resultsMarkup = $resultRows === ''
            ? '<p class="pending">No result entries yet.</p>'
            : '<table><thead><tr><th>Callable</th><th>Outcome</th><th>Time</th><th>Evidence</th></tr></thead><tbody>'
                . $resultRows . '</tbody></table>';

        $snippets = '';
        foreach ($regionsByScenario[$scenario['id']] as $region) {
            $name = html($region['name']);
            $source = html($region['source']);
            $content = html($region['content']);
            $link = '../' . implode('/', array_map('rawurlencode', explode('/', $region['source'])));
            $snippets .= <<<HTML
              <details class="snippet" data-search="{$name} {$source}">
                <summary><span><strong>{$name}</strong><small>{$source}</small></span><em>source-backed</em></summary>
                <pre><code>{$content}</code></pre>
                <a href="{$link}">Open source</a>
              </details>
            HTML;
        }

        $sections .= <<<HTML
          <section id="{$scenarioId}" class="scenario-detail">
            <header><span class="eyebrow">Scenario {$ordinal}</span><h2>{$title}</h2><p>{$summary}</p><code>{$command}</code></header>
            <div class="metrics"><span><b>{$regionCount}</b> snippets</span><span><b>{$sourceCount}</b> source files</span><span><b>{$duration}</b> duration</span></div>
            <section class="evidence"><h3>Live evidence</h3><p>{$outcome}</p>{$resultsMarkup}</section>
            <h3>Mapped source files</h3><ul class="files">{$sourceList}</ul>
            <h3>Snippet gallery</h3><div class="snippet-list">{$snippets}</div>
          </section>
        HTML;
    }

    return <<<HTML
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PHP SDK live example showcase</title>
  <style>
    :root { --ink:#ebf2ff; --muted:#a8b7d2; --bg:#080d18; --panel:#101a2d; --line:#2a3b5c; --aqua:#42ddc1; --blue:#8bb7ff; --warn:#f0cd72; --fail:#ff98ae; }
    * { box-sizing:border-box; } body { margin:0; color:var(--ink); background:radial-gradient(circle at 15% 0,#244b79 0,transparent 32rem),radial-gradient(circle at 86% 8%,#3d285f 0,transparent 30rem),var(--bg); font:16px/1.55 Inter,ui-sans-serif,system-ui,sans-serif; }
    code,pre { font-family:"SFMono-Regular",Consolas,monospace; } a { color:var(--aqua); } .wrap { width:min(1180px,calc(100% - 2rem)); margin:auto; } .hero { padding:5rem 0 2rem; } .eyebrow { color:var(--aqua); font:700 .75rem/1 monospace; letter-spacing:.13em; text-transform:uppercase; } h1 { max-width:800px; margin:.7rem 0; font-size:clamp(2.8rem,7vw,5.5rem); line-height:.92; letter-spacing:-.06em; } h2,h3 { line-height:1.15; } .hero p,.scenario-card p,.scenario-detail header p,.pending { color:var(--muted); } .run { margin-top:1.5rem; padding:1rem 1.2rem; border:1px solid var(--line); border-radius:12px; background:#0e1728; } .grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:1rem; } .scenario-card,.scenario-detail { border:1px solid var(--line); border-radius:16px; background:#101a2ddd; box-shadow:0 20px 60px #0005; } .scenario-card { position:relative; padding:1.4rem; overflow:hidden; } .ordinal { float:right; color:#b8a2ff; font:800 1rem monospace; } .scenario-card h3 { margin:0; } .badges,.metrics { display:flex; flex-wrap:wrap; gap:.45rem; } .badges { margin:1rem 0; } .badges span,.status { display:inline-block; border:1px solid #405675; border-radius:999px; padding:.18rem .55rem; color:#c6d8f5; font-size:.74rem; } .status.pass { border-color:#277255; color:#8de9b4; } .status.partial,.status.expected-constraint { border-color:#796326; color:var(--warn); } .status.fail { border-color:#8a4055; color:var(--fail); } .status.not-run,.status.unknown { color:var(--muted); } .scenario-card code { color:var(--aqua); font-size:.76rem; } .toolbar { display:flex; justify-content:space-between; gap:1rem; align-items:end; margin:2.5rem 0 1rem; } input { width:min(380px,100%); padding:.7rem .9rem; border:1px solid var(--line); border-radius:9px; color:var(--ink); background:#0a1323; font:inherit; } .scenario-detail { margin:2rem 0; padding:clamp(1rem,3vw,2rem); } .scenario-detail header code { display:inline-block; margin-top:.8rem; color:var(--aqua); font-size:.8rem; } .metrics { margin:1.2rem 0; padding:1rem 0; border-block:1px solid var(--line); color:var(--muted); } .metrics b { color:var(--blue); font-size:1.3rem; } .evidence { overflow:auto; margin:1.25rem 0; border:1px solid var(--line); border-radius:10px; background:#0b1423; } .evidence h3,.evidence > p { margin:.8rem 1rem; } table { width:100%; border-collapse:collapse; font-size:.78rem; } th,td { padding:.7rem .8rem; border-top:1px solid var(--line); text-align:left; vertical-align:top; } th { color:var(--muted); } td:last-child { color:var(--muted); } .files { display:flex; flex-wrap:wrap; gap:.45rem; padding:0; list-style:none; } .files code { padding:.2rem .45rem; border:1px solid var(--line); border-radius:6px; color:#cddbf3; font-size:.75rem; } .snippet-list { display:grid; gap:.65rem; } .snippet { overflow:hidden; border:1px solid var(--line); border-radius:10px; background:#152139; } .snippet summary { display:flex; justify-content:space-between; gap:1rem; padding:.85rem 1rem; cursor:pointer; } .snippet summary span { display:grid; } .snippet small { color:var(--muted); font:.75rem monospace; } .snippet em { color:var(--aqua); font-size:.75rem; font-style:normal; } pre { overflow:auto; margin:0; padding:1rem; border-top:1px solid var(--line); background:#070c15; font-size:.78rem; } .snippet a { display:inline-block; padding:.6rem 1rem; font-size:.78rem; } footer { padding:2rem 0 4rem; color:var(--muted); font-size:.8rem; } .hidden { display:none; } @media (max-width:700px) { .grid { grid-template-columns:1fr; } .toolbar { display:grid; } }
  </style>
</head>
<body>
  <header class="wrap hero"><span class="eyebrow">Camunda Orchestration Cluster API / PHP</span><h1>Live example<br>showcase.</h1><p>Every source-backed snippet is indexed below and assigned to one of six live cluster scenarios. Outcomes are captured from the running cluster rather than synthesized for CI.</p><div class="run"><b>Latest run:</b> {$runSummary}<br><b>Snippet validation:</b> {$snippetSummary}</div></header>
  <main class="wrap"><div class="toolbar"><div><span class="eyebrow">Six scenarios</span><h2>Proof, source, and evidence together.</h2></div><input id="filter" type="search" placeholder="Filter snippet or source file"></div><section class="grid">{$cards}</section>{$sections}</main>
  <footer class="wrap">Generated from <code>examples/scenario-map.json</code>, source regions, and <code>docs/example-validation-results.json</code>. Do not edit this file by hand.</footer>
  <script>document.getElementById('filter').addEventListener('input', function () { const n=this.value.toLowerCase().trim(); document.querySelectorAll('.snippet').forEach((e) => e.classList.toggle('hidden', n !== '' && !e.dataset.search.toLowerCase().includes(n))); });</script>
</body>
</html>
HTML;
}

function html(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function status_class(string $status): string
{
    return match ($status) {
        'PASS' => 'pass',
        'PARTIAL' => 'partial',
        'EXPECTED-CONSTRAINT' => 'expected-constraint',
        'FAIL' => 'fail',
        default => 'not-run',
    };
}
