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

    // Tenant-aware operations: when the caller omits tenantId, inject the
    // effective default tenant (CAMUNDA_TENANT_ID, or `<default>` when nothing is
    // configured) so the request is scoped to the default tenant. This matches the
    // Camunda Java client, and makes a zero-config client work on multi-tenant
    // clusters (issues #138, #178).
    //
    // Which operations get the default is derived from the spec, not a list: an
    // operation's JSON request body (or each oneOf variant of it) that declares
    // an optional top-level `tenantId` is tenant-aware. Union bodies where only
    // some variants carry tenantId (e.g. suspendProcessDefinitions by id vs by
    // key) inject only on those variants. Whether a setter takes a plain string
    // or the semantic TenantId is detected from the generated model.
    $spec = json_decode((string) file_get_contents($ctx['spec_path']), true);
    if (!is_array($spec)) {
        throw new \RuntimeException('Cannot read bundled spec at ' . $ctx['spec_path']);
    }
    $tenantAwareBodyOps = [];
    foreach (tenant_aware_body_schemas($spec) as $op => [$variants, $allVariants]) {
        $withSetter = [];
        foreach ($variants as $variant) {
            $withSetter[$variant] = tenant_setter_takes_string($ctx['out_dir'] . '/src/Model/' . $variant . '.php');
        }
        $tenantAwareBodyOps[$op] = [$withSetter, $allVariants];
    }

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

            $tenantInjection = tenant_injection($name, $m[3], $tenantAwareBodyOps);
            // createDeployment takes tenantId as a direct query parameter rather
            // than inside a body model; default it when the caller omits it.
            if ($name === 'createDeployment') {
                $tenantInjection = <<<'PHP'
        if ($tenantId === null) {
            $tenantId = $this->config->defaultTenantId();
        }

PHP;
            }
            // activateJobs: with tenantFilter PROVIDED (or unset) and no tenant ids,
            // activate for the default tenant(s). ASSIGNED is left alone — the
            // server ignores tenantIds there.
            if ($name === 'activateJobs') {
                $tenantInjection = <<<'PHP'
        $tenantFilter = $jobActivationRequest->getTenantFilter();
        if (($tenantFilter === null || $tenantFilter === \Camunda\Orchestration\Api\Model\TenantFilterEnum::PROVIDED)
            && ($jobActivationRequest->getTenantIds() ?? []) === []) {
            $jobActivationRequest = clone $jobActivationRequest;
            $jobActivationRequest->setTenantIds($this->config->defaultJobTenantIds());
        }

PHP;
            }
            $syncBody = $tenantInjection;
            $syncBody .= $isVoid ? "        {$call};\n" : "        return {$call};\n";
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
                    . $tenantInjection
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
 * Inspect a generated model's setTenantId() signature: true when it takes a
 * plain string, false when it takes the semantic TenantId.
 *
 * Union (oneOf) body models declare no setter of their own; their variants are
 * retyped to TenantId by 0300_retype_models, so they default to TenantId.
 */
function tenant_setter_takes_string(string $modelFile): bool
{
    $src = is_file($modelFile) ? (string) file_get_contents($modelFile) : '';
    if (!str_contains($src, 'function setTenantId(')) {
        return false;
    }
    if (preg_match('/function setTenantId\(\s*\??\s*([^\s?$]+)\s+\$/', $src, $m) !== 1) {
        throw new \RuntimeException("Cannot determine setTenantId() parameter type in {$modelFile}");
    }

    return strtolower(ltrim($m[1], '\\')) === 'string';
}

/**
 * Walk the spec for operations whose JSON request body carries an optional
 * top-level `tenantId`, per oneOf/anyOf variant.
 *
 * Returns operationId => [list of variant schema names with an optional
 * tenantId, whether every variant of the body has it]. A non-union body is a
 * single "variant" named after its schema.
 *
 * @param array<mixed> $spec
 * @return array<string, array{0: list<string>, 1: bool}>
 */
function tenant_aware_body_schemas(array $spec): array
{
    $schemas = $spec['components']['schemas'] ?? [];
    $result = [];
    foreach ($spec['paths'] ?? [] as $pathItem) {
        if (!is_array($pathItem)) {
            continue;
        }
        foreach ($pathItem as $op) {
            if (!is_array($op) || !isset($op['operationId'], $op['requestBody']['content']['application/json']['schema'])) {
                continue;
            }
            $schema = $op['requestBody']['content']['application/json']['schema'];
            $name = schema_ref_name($schema);
            $resolved = resolve_schema($schema, $schemas);
            $union = $resolved['oneOf'] ?? $resolved['anyOf'] ?? null;
            // Fail closed on shapes this walk cannot model faithfully: a union
            // nested under allOf, or a union with enclosing properties/required
            // that would constrain every variant.
            if (schema_has_nested_union($resolved, $schemas)
                || (is_array($union) && (isset($resolved['properties']) || isset($resolved['required']) || isset($resolved['allOf'])))) {
                throw new \RuntimeException('Unsupported composed request body for ' . $op['operationId'] . ': extend tenant_aware_body_schemas()');
            }
            $variants = [];
            if (is_array($union)) {
                foreach ($union as $v) {
                    $vn = schema_ref_name($v);
                    if ($vn === null) {
                        continue 2; // inline variant: no generated model to target
                    }
                    $variants[$vn] = $v;
                }
            } elseif ($name !== null) {
                $variants[$name] = $schema;
            }
            $tenantVariants = [];
            foreach ($variants as $vn => $v) {
                [$props, $required] = schema_properties($v, $schemas);
                if (isset($props['tenantId']) && !in_array('tenantId', $required, true)) {
                    $tenantVariants[] = $vn;
                }
            }
            if ($tenantVariants !== []) {
                $result[(string) $op['operationId']] = [$tenantVariants, count($tenantVariants) === count($variants)];
            }
        }
    }

    return $result;
}

/**
 * True when any allOf part (recursively) of the schema is itself a union.
 *
 * @param array<mixed> $schema
 * @param array<mixed> $schemas
 */
function schema_has_nested_union(array $schema, array $schemas, int $depth = 0): bool
{
    if ($depth > 50) {
        return false;
    }
    foreach ($schema['allOf'] ?? [] as $part) {
        if (!is_array($part)) {
            continue;
        }
        $part = resolve_schema($part, $schemas);
        if (isset($part['oneOf']) || isset($part['anyOf']) || schema_has_nested_union($part, $schemas, $depth + 1)) {
            return true;
        }
    }

    return false;
}

/** @param mixed $schema */
function schema_ref_name($schema): ?string
{
    if (is_array($schema) && isset($schema['$ref']) && is_string($schema['$ref'])) {
        return substr($schema['$ref'], strrpos($schema['$ref'], '/') + 1);
    }

    return null;
}

/**
 * @param array<mixed> $schema
 * @param array<mixed> $schemas
 * @return array<mixed>
 */
function resolve_schema(array $schema, array $schemas): array
{
    $guard = 0;
    while (($ref = schema_ref_name($schema)) !== null && ++$guard < 50) {
        $schema = $schemas[$ref] ?? [];
    }

    return $schema;
}

/**
 * Top-level properties and required names of a schema, following allOf.
 *
 * @param array<mixed> $schema
 * @param array<mixed> $schemas
 * @return array{0: array<string, mixed>, 1: list<string>}
 */
function schema_properties(array $schema, array $schemas): array
{
    $schema = resolve_schema($schema, $schemas);
    $props = is_array($schema['properties'] ?? null) ? $schema['properties'] : [];
    $required = is_array($schema['required'] ?? null) ? array_values($schema['required']) : [];
    foreach ($schema['allOf'] ?? [] as $part) {
        if (is_array($part)) {
            [$p, $r] = schema_properties($part, $schemas);
            $props += $p;
            $required = array_merge($required, $r);
        }
    }

    return [$props, $required];
}

/**
 * Emit the tenant-default injection prelude for tenant-aware operations.
 *
 * When the caller leaves tenantId null on the body model, forward a clone
 * carrying the effective default tenant (CAMUNDA_TENANT_ID, else `<default>`). The model
 * is cloned before the default is applied so the caller-owned instance is
 * never mutated: reusing one tenantless model across clients with different
 * configured defaults must not leak the first client's tenant into the next
 * call. For union bodies where only some variants carry tenantId, the
 * injection is guarded by an instanceof check per variant.
 *
 * @param array<string, array{0: array<string, bool>, 1: bool}> $tenantAwareBodyOps
 */
function tenant_injection(string $operationName, string $rawParams, array $tenantAwareBodyOps): string
{
    if (!isset($tenantAwareBodyOps[$operationName])) {
        return '';
    }

    [$variants, $allVariants] = $tenantAwareBodyOps[$operationName];
    $first = array_key_first($variants);
    if (preg_match('/Model\\\\' . preg_quote((string) $first, '/') . '\b[^$]*\$(\w+)/', $rawParams, $pm) !== 1) {
        throw new \RuntimeException("Cannot locate the {$first} body parameter of {$operationName}");
    }
    $param = $pm[1];
    // An optional body parameter (`?Model $x = null`) may be omitted: guard it.
    $nullable = preg_match('/\?\s*\\\\?Camunda\\\\Orchestration\\\\Api\\\\Model\\\\[^$]*\$' . $param . '\b|\|\s*null\s+\$' . $param . '\b|\$' . $param . '\s*=\s*null\b/', $rawParams) === 1;
    $nullGuard = $nullable ? "\${$param} !== null && " : '';

    $value = static fn (bool $isString): string => $isString
        ? '$this->config->defaultTenantId()'
        : 'new \\Camunda\\Orchestration\\Semantic\\TenantId($this->config->defaultTenantId())';

    $distinctTypes = array_unique(array_values($variants));
    if ($allVariants && count($distinctTypes) === 1) {
        $valueExpr = $value(reset($variants));

        return <<<PHP
        if ({$nullGuard}\${$param}->getTenantId() === null) {
            \${$param} = clone \${$param};
            \${$param}->setTenantId({$valueExpr});
        }

PHP;
    }

    $out = '';
    foreach ($variants as $variant => $isString) {
        $valueExpr = $value($isString);
        $out .= <<<PHP
        if ({$nullGuard}\${$param} instanceof \\Camunda\\Orchestration\\Api\\Model\\{$variant} && \${$param}->getTenantId() === null) {
            \${$param} = clone \${$param};
            \${$param}->setTenantId({$valueExpr});
        }

PHP;
    }

    return $out;
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
        . "{\n";

    file_put_contents($target, $header . $methods . "}\n");
}
