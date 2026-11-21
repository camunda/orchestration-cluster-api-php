<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use PHPUnit\Framework\TestCase;

/**
 * Derived from the bundled spec, not a list: every operation whose JSON request
 * body (or a oneOf/anyOf variant of it) declares an optional top-level
 * `tenantId` must inject the default tenant in both flat facades. A new
 * tenant-scoped operation upstream fails this test until the hook covers it.
 */
final class TenantInjectionSpecCoverageTest extends TestCase
{
    public function testEveryOptionalBodyTenantIdOperationInjectsDefaultTenant(): void
    {
        $root = dirname(__DIR__, 2);
        $spec = json_decode((string) file_get_contents($root . '/external-spec/bundled/rest-api.bundle.json'), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($spec);
        /** @var array{paths?: array<string, array<string, mixed>>, components?: array{schemas?: array<string, array<string, mixed>>}} $spec */
        /** @var array<string, array<string, mixed>> $schemas */
        $schemas = $spec['components']['schemas'] ?? [];
        $sync = (string) file_get_contents($root . '/src/GeneratedOperations.php');
        $async = (string) file_get_contents($root . '/src/GeneratedAsyncOperations.php');

        $checked = [];
        /** @var array<string, array<string, mixed>> $paths */
        $paths = $spec['paths'] ?? [];
        foreach ($paths as $pathItem) {
            foreach ($pathItem as $op) {
                if (!is_array($op) || !isset($op['operationId']) || !is_string($op['operationId'])) {
                    continue;
                }
                /** @var array{operationId: string, requestBody?: array{content?: array<string, array{schema?: mixed}>}} $op */
                $schema = $op['requestBody']['content']['application/json']['schema'] ?? null;
                if (!is_array($schema)) {
                    continue;
                }
                $resolved = $this->resolve($schema, $schemas);
                $union = $resolved['oneOf'] ?? $resolved['anyOf'] ?? null;
                $variants = is_array($union) ? $union : [$schema];
                $eligible = [];
                foreach ($variants as $variant) {
                    if (is_array($variant) && $this->hasOptionalTenantId($variant, $schemas)) {
                        $eligible[] = $this->refName($variant);
                    }
                }
                if ($eligible === []) {
                    continue;
                }
                $operationId = $op['operationId'];
                $allEligible = count($eligible) === count($variants);
                foreach (['sync' => $sync, 'async' => $async] as $flavour => $trait) {
                    $body = $this->methodBody($trait, $operationId);
                    self::assertNotNull($body, "{$operationId} missing from {$flavour} facade");
                    // An optional body is guarded by `$x !== null && ` ahead of the injection guard.
                    $body = (string) preg_replace('/if \((\$\w+) !== null && (?=\1[ -])/', 'if (', $body);
                    $unguarded = $this->injectionBlock(null);
                    if ($allEligible && preg_match($unguarded, $body) === 1) {
                        continue;
                    }
                    self::assertDoesNotMatchRegularExpression(
                        $unguarded,
                        $body,
                        "{$operationId} ({$flavour}) injects on variants that carry no optional tenantId",
                    );
                    foreach ($eligible as $name) {
                        self::assertNotNull($name, "{$operationId} has an inline union variant; cannot verify its guard");
                        self::assertMatchesRegularExpression(
                            $this->injectionBlock($name),
                            $body,
                            "{$operationId} ({$flavour}) does not inject the default tenant on variant {$name} inside a null guard",
                        );
                    }
                }
                $checked[$operationId] = true;
            }
        }

        foreach (['suspendProcessDefinitions', 'resumeProcessDefinitions', 'createProcessInstance', 'publishMessage'] as $known) {
            self::assertArrayHasKey($known, $checked, "spec walk did not find {$known}");
        }
    }

    /**
     * @param array<mixed> $schema
     * @param array<string, array<string, mixed>> $schemas
     * @return array<mixed>
     */
    private function resolve(array $schema, array $schemas): array
    {
        while (isset($schema['$ref']) && is_string($schema['$ref'])) {
            $schema = $schemas[substr($schema['$ref'], strrpos($schema['$ref'], '/') + 1)] ?? [];
        }

        return $schema;
    }

    /**
     * @param array<mixed> $schema
     * @param array<string, array<string, mixed>> $schemas
     */
    private function hasOptionalTenantId(array $schema, array $schemas): bool
    {
        [$props, $required] = $this->collect($schema, $schemas);

        return isset($props['tenantId']) && !in_array('tenantId', $required, true);
    }

    /**
     * Properties and required names merged across the whole allOf composition.
     *
     * @param array<mixed> $schema
     * @param array<string, array<string, mixed>> $schemas
     * @return array{0: array<mixed>, 1: list<mixed>}
     */
    private function collect(array $schema, array $schemas): array
    {
        $schema = $this->resolve($schema, $schemas);
        $props = is_array($schema['properties'] ?? null) ? $schema['properties'] : [];
        $required = is_array($schema['required'] ?? null) ? array_values($schema['required']) : [];
        foreach (is_array($schema['allOf'] ?? null) ? $schema['allOf'] : [] as $part) {
            if (is_array($part)) {
                [$p, $r] = $this->collect($part, $schemas);
                $props += $p;
                $required = array_merge($required, $r);
            }
        }

        return [$props, $required];
    }

    /**
     * @param array<mixed> $schema
     */
    private function refName(array $schema): ?string
    {
        if (!isset($schema['$ref']) || !is_string($schema['$ref'])) {
            return null;
        }

        return substr($schema['$ref'], strrpos($schema['$ref'], '/') + 1);
    }

    /**
     * Regex for an `if (<guard> getTenantId() === null) { clone; setTenantId(default) }` block.
     */
    private function injectionBlock(?string $variant): string
    {
        $guard = $variant === null
            ? '\\$(\\w+)->getTenantId\\(\\) === null'
            : '\\$(\\w+) instanceof \\\\Camunda\\\\Orchestration\\\\Api\\\\Model\\\\' . preg_quote($variant, '/') . ' && \\$\\1->getTenantId\\(\\) === null';

        return '/if \\(' . $guard . '\\) \\{\\n\\s*\\$\\1 = clone \\$\\1;\\n\\s*\\$\\1->setTenantId\\([^\\n]*defaultTenantId\\(\\)\\)?\\);\\n\\s*\\}/';
    }

    private function methodBody(string $trait, string $operationId): ?string
    {
        if (preg_match('/public function ' . preg_quote($operationId, '/') . '\(.*?\n    \{\n(.*?)\n    \}\n/s', $trait, $m) !== 1) {
            return null;
        }

        return $m[1];
    }
}
