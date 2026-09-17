<?php

/**
 * generate.php — Orchestrates generation of the Camunda Orchestration Cluster PHP SDK.
 *
 * Pipeline:
 *   1. Run openapi-generator (php-nextgen) against the bundled spec -> generated/
 *   2. Run post-generation hooks in hooks/post_gen/ (sorted) that:
 *        - emit validated semantic value-object types (generated/semantic/)
 *        - integrate the ObjectSerializer with those value objects
 *        - retype generated models/apis to use them
 *        - relicense / clean up generator boilerplate
 *
 * The generated/ tree is NEVER hand-edited. Regenerate via `make generate`.
 *
 * Usage:
 *   php generate.php [--spec external-spec/bundled/rest-api.bundle.json]
 *                    [--metadata external-spec/bundled/spec-metadata.json]
 *                    [--out generated] [--skip-openapi-generator]
 */

declare(strict_types=1);

$root = __DIR__;
$opts = getopt('', [
    'spec:',
    'metadata:',
    'out:',
    'config:',
    'skip-openapi-generator',
]);

$spec = $opts['spec'] ?? 'external-spec/bundled/rest-api.bundle.json';
$metadata = $opts['metadata'] ?? 'external-spec/bundled/spec-metadata.json';
$out = $opts['out'] ?? 'generated';
$config = $opts['config'] ?? 'openapi-generator-config.yaml';

$specPath = realpathOrFail($root . '/' . $spec, 'bundled spec');
$metadataPath = file_exists($root . '/' . $metadata) ? realpath($root . '/' . $metadata) : null;
$outDir = $root . '/' . $out;

function realpathOrFail(string $path, string $what): string
{
    $rp = realpath($path);
    if ($rp === false) {
        fwrite(STDERR, "error: $what not found at $path — run `make bundle-spec` first\n");
        exit(1);
    }
    return $rp;
}

function log_step(string $msg): void
{
    fwrite(STDOUT, "[generate] $msg\n");
}

// 1. openapi-generator ---------------------------------------------------------
if (!isset($opts['skip-openapi-generator'])) {
    log_step('running openapi-generator (php-nextgen)...');
    // Regenerate cleanly so removed operations/models do not linger.
    exec('rm -rf ' . escapeshellarg($outDir));
    $cmd = 'npx --yes @openapitools/openapi-generator-cli generate -c '
        . escapeshellarg($config)
        . ' -i ' . escapeshellarg($specPath)
        . ' -o ' . escapeshellarg($outDir)
        . ' 2>&1';
    passthru($cmd, $code);
    if ($code !== 0) {
        fwrite(STDERR, "error: openapi-generator failed\n");
        exit($code);
    }
} else {
    log_step('skipping openapi-generator (--skip-openapi-generator)');
}

// 2. post-gen hooks ------------------------------------------------------------
$context = [
    'root' => $root,
    'out_dir' => $outDir,
    'spec_path' => $specPath,
    'metadata_path' => $metadataPath,
];

$hooksDir = $root . '/hooks/post_gen';
$hooks = glob($hooksDir . '/*.php') ?: [];
sort($hooks);
foreach ($hooks as $hook) {
    $run = require $hook;
    if (!is_callable($run)) {
        fwrite(STDERR, "warning: hook " . basename($hook) . " did not return a callable; skipping\n");
        continue;
    }
    log_step('hook: ' . basename($hook));
    $run($context);
}

log_step('done.');
