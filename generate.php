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
    // 0. pre-gen spec transforms (hooks/pre_gen/*.php, sorted). Each returns a
    //    callable(\stdClass $spec): \stdClass. The bundled spec on disk is left untouched;
    //    the generator reads a transformed temporary copy.
    $preHooks = glob($root . '/hooks/pre_gen/*.php') ?: [];
    sort($preHooks);
    if ($preHooks !== []) {
        $doc = json_decode((string) file_get_contents($specPath), false, 512, JSON_THROW_ON_ERROR);
        foreach ($preHooks as $hook) {
            $transform = require $hook;
            if (!is_callable($transform)) {
                fwrite(STDERR, "warning: pre-gen hook " . basename($hook) . " did not return a callable; skipping\n");
                continue;
            }
            log_step('pre-gen hook: ' . basename($hook));
            $doc = $transform($doc);
        }
        // Create a private, unpredictable directory (mode 0700, mkdir fails if the path
        // already exists) so another local user cannot pre-plant a symlink at the path.
        $genSpecDir = sys_get_temp_dir() . '/camunda-php-sdk-spec-' . bin2hex(random_bytes(16));
        if (!@mkdir($genSpecDir, 0700)) {
            fwrite(STDERR, "error: could not create temporary directory {$genSpecDir}\n");
            exit(1);
        }
        $genSpecPath = $genSpecDir . '/spec.json';
        $fh = @fopen($genSpecPath, 'x');
        if ($fh === false
            || fwrite($fh, json_encode($doc, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)) === false
            || !fclose($fh)) {
            fwrite(STDERR, "error: could not write temporary spec {$genSpecPath}\n");
            exit(1);
        }
        register_shutdown_function(static function () use ($genSpecPath, $genSpecDir): void {
            @unlink($genSpecPath);
            @rmdir($genSpecDir);
        });
    } else {
        $genSpecPath = $specPath;
    }

    log_step('running openapi-generator (php-nextgen)...');
    // Regenerate cleanly so removed operations/models do not linger.
    exec('rm -rf ' . escapeshellarg($outDir));
    $cmd = 'npx --yes @openapitools/openapi-generator-cli generate -c '
        . escapeshellarg($config)
        . ' -i ' . escapeshellarg($genSpecPath)
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
