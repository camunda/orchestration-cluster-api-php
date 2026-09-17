<?php

/**
 * Post-gen hook 0500 — (re)generate the flat operation facades.
 *
 * Emits two traits that expose every generated operation directly on the client,
 * so callers can write `$client->createProcessInstance(...)` instead of reaching
 * through an API group. This mirrors the flat facade shipped by the sibling SDKs.
 *
 *   - src/GeneratedOperations.php       — synchronous, used by CamundaClient
 *   - src/GeneratedAsyncOperations.php  — promise-based, used by CamundaAsyncClient
 *
 * Each method is a thin, fully-typed forwarder to `$this->api(FooApi::class)->op(...)`.
 * Regenerated on every pipeline run so the facade always covers exactly the
 * generated API surface.
 */

declare(strict_types=1);

return static function (array $ctx): void {
    $apiDir = $ctx['out_dir'] . '/src/Api';

    // Names that must not be shadowed by a generated forwarder because they are
    // defined by the client facades or the ApiAccessors trait.
    $reserved = ['api', 'configuration'];

    $syncMethods = '';
    $asyncMethods = '';
    $seen = [];
    $count = 0;

    $files = glob($apiDir . '/*.php') ?: [];
    sort($files);

    foreach ($files as $file) {
        $class = basename($file, '.php');
        $fqcn = '\\Camunda\\Orchestration\\Api\\Api\\' . $class;
        $text = (string) file_get_contents($file);

        if (preg_match_all('/(\/\*\*(?:[^*]|\*(?!\/))*?\*\/)?\s*public function (\w+)\(([^)]*)\)\s*:\s*([^{]+?)\s*\{/s', $text, $matches, PREG_SET_ORDER) === false) {
            continue;
        }

        foreach ($matches as $m) {
            $name = $m[2];
            if (preg_match('/(WithHttpInfo|Async|Request)$/', $name) === 1) {
                continue;
            }
            if (in_array($name, ['__construct', 'getConfig', 'getHostIndex', 'setHostIndex'], true)) {
                continue;
            }
            if (in_array($name, $reserved, true) || isset($seen[$name])) {
                continue;
            }
            $seen[$name] = true;

            $doc = doc_block($m[1] ?? '');
            $params = normalize_params($m[3], $fqcn);
            $args = argument_list($m[3]);
            $return = normalize_return($m[4]);
            $isVoid = in_array($return, ['void', 'never'], true);
            $call = "\$this->api({$fqcn}::class)->{$name}({$args})";

            $syncBody = $isVoid ? "        {$call};\n" : "        return {$call};\n";
            $syncMethods .= $doc
                . "    public function {$name}({$params}): {$return}\n"
                . "    {\n"
                . $syncBody
                . "    }\n\n";

            if (strpos($text, "public function {$name}Async(") !== false) {
                $asyncDoc = doc_block($m[1] ?? '', true);
                $asyncMethods .= $asyncDoc
                    . "    public function {$name}({$params}): \\GuzzleHttp\\Promise\\PromiseInterface\n"
                    . "    {\n"
                    . "        return \$this->api({$fqcn}::class)->{$name}Async({$args});\n"
                    . "    }\n\n";
            }

            ++$count;
        }
    }

    write_trait(
        $ctx['root'] . '/src/GeneratedOperations.php',
        'GeneratedOperations',
        'synchronous',
        $syncMethods,
    );
    write_trait(
        $ctx['root'] . '/src/GeneratedAsyncOperations.php',
        'GeneratedAsyncOperations',
        'asynchronous (Guzzle promise)',
        $asyncMethods,
    );

    fwrite(STDOUT, "  [flat-facade] wrote {$count} operations to src/GeneratedOperations.php + src/GeneratedAsyncOperations.php\n");
};

/**
 * Collapse a multi-line parameter list to a single line and re-root `self::`
 * constant references at the declaring API class so defaults stay valid.
 */
function normalize_params(string $params, string $fqcn): string
{
    $params = trim(preg_replace('/\s+/', ' ', $params) ?? $params);
    return str_replace('self::', $fqcn . '::', $params);
}

/**
 * Build the forwarded call argument list ($a, $b, ...) from a parameter list.
 */
function argument_list(string $params): string
{
    $args = [];
    foreach (split_top_level($params) as $part) {
        if (preg_match('/(\.\.\.)?\s*&?\$(\w+)/', $part, $m) === 1) {
            $args[] = ($m[1] ?? '') . '$' . $m[2];
        }
    }
    return implode(', ', $args);
}

/**
 * Split a parameter list on top-level commas (ignoring commas inside brackets).
 *
 * @return list<string>
 */
function split_top_level(string $params): array
{
    $parts = [];
    $depth = 0;
    $current = '';
    $length = strlen($params);
    for ($i = 0; $i < $length; ++$i) {
        $char = $params[$i];
        if ($char === '[' || $char === '(') {
            ++$depth;
        } elseif ($char === ']' || $char === ')') {
            --$depth;
        }
        if ($char === ',' && $depth === 0) {
            $parts[] = trim($current);
            $current = '';
            continue;
        }
        $current .= $char;
    }
    if (trim($current) !== '') {
        $parts[] = trim($current);
    }
    return $parts;
}

function normalize_return(string $return): string
{
    return trim(preg_replace('/\s+/', ' ', $return) ?? $return);
}

/**
 * Re-indent a captured generated docblock for use in the facade trait and drop
 * `@throws` tags (their unqualified class names don't resolve in the trait's
 * namespace, and they are an implementation detail of the low-level client).
 * `@param`/`@return` are preserved so the forwarders keep precise generic types.
 */
function doc_block(string $raw, bool $stripReturn = false): string
{
    $raw = trim($raw);
    if ($raw === '' || !str_starts_with($raw, '/**')) {
        return '';
    }

    $out = [];
    foreach (explode("\n", $raw) as $line) {
        $line = rtrim($line);
        if (preg_match('/^\s*\*\s*@throws\b/', $line) === 1) {
            continue;
        }
        if ($stripReturn && preg_match('/^\s*\*\s*@return\b/', $line) === 1) {
            continue;
        }
        if (str_starts_with(ltrim($line), '/**')) {
            $out[] = '    /**';
        } else {
            $normalized = ltrim($line);
            if (preg_match('/^\*\s*@param\b/', $normalized) === 1) {
                $normalized = preg_replace('/(@param\s+)array(\s+\$)/', '$1array<string, mixed>$2', $normalized);
                $normalized = preg_replace('/(@param\s+)object(\b(?!<))/', '$1array<string, mixed>', $normalized);
            }
            $out[] = '     ' . $normalized;
        }
    }

    return implode("\n", $out) . "\n";
}

function write_trait(string $target, string $traitName, string $flavour, string $methods): void
{
    $header = "<?php\n\n"
        . "declare(strict_types=1);\n\n"
        . "namespace Camunda\\Orchestration;\n\n"
        . "/**\n"
        . " * Flat, {$flavour} facade over every generated Camunda Orchestration Cluster\n"
        . " * API operation.\n"
        . " *\n"
        . " * Each method forwards to the matching generated API group via \$this->api().\n"
        . " * This trait is regenerated from the generated API classes by\n"
        . " * hooks/post_gen/0500_flat_facade.php; do not edit by hand.\n"
        . " *\n"
        . " * @internal\n"
        . " */\n"
        . "trait {$traitName}\n"
        . "{\n\n";

    file_put_contents($target, $header . $methods . "}\n");
}
