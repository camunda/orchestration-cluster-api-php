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
                foreach ($variants as $variant) {
                    if (!is_array($variant) || !$this->hasOptionalTenantId($variant, $schemas)) {
                        continue;
                    }
                    $operationId = $op['operationId'];
                    foreach (['sync' => $sync, 'async' => $async] as $flavour => $trait) {
                        $body = $this->methodBody($trait, $operationId);
                        self::assertNotNull($body, "{$operationId} missing from {$flavour} facade");
                        self::assertMatchesRegularExpression(
                            '/->setTenantId\(.*defaultTenantId\(\)/',
                            $body,
                            "{$operationId} ({$flavour}) does not inject the default tenant",
                        );
                        if (is_array($union) && isset($variant['$ref']) && is_string($variant['$ref'])) {
                            $name = substr($variant['$ref'], strrpos($variant['$ref'], '/') + 1);
                            $guarded = str_contains($body, 'instanceof');
                            if ($guarded) {
                                self::assertStringContainsString(
                                    "instanceof \\Camunda\\Orchestration\\Api\\Model\\{$name} ",
                                    $body,
                                    "{$operationId} ({$flavour}) does not inject on variant {$name}",
                                );
                            }
                        }
                    }
                    $checked[$operationId] = true;
                }
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
        $schema = $this->resolve($schema, $schemas);
        $required = is_array($schema['required'] ?? null) ? $schema['required'] : [];
        if (is_array($schema['properties'] ?? null) && isset($schema['properties']['tenantId'])) {
            return !in_array('tenantId', $required, true);
        }
        foreach (is_array($schema['allOf'] ?? null) ? $schema['allOf'] : [] as $part) {
            if (is_array($part) && $this->hasOptionalTenantId($part, $schemas)) {
                return true;
            }
        }

        return false;
    }

    private function methodBody(string $trait, string $operationId): ?string
    {
        if (preg_match('/public function ' . preg_quote($operationId, '/') . '\(.*?\n    \{\n(.*?)\n    \}\n/s', $trait, $m) !== 1) {
            return null;
        }

        return $m[1];
    }
}
