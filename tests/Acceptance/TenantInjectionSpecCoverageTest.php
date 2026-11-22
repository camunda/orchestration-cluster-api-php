<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use PHPUnit\Framework\TestCase;

/**
 * Derived from camunda-schema-bundler's spec metadata, not a list: every
 * operation whose request body (or a oneOf/anyOf variant of it) declares an
 * optional top-level `tenantId` must inject the default tenant in both flat
 * facades, and an operation with an optional `tenantIds` array must default
 * it. A new tenant-scoped operation upstream fails this test until the hook
 * covers it.
 */
final class TenantInjectionSpecCoverageTest extends TestCase
{
    public function testEveryOptionalBodyTenantIdOperationInjectsDefaultTenant(): void
    {
        $root = dirname(__DIR__, 2);
        $metadata = json_decode((string) file_get_contents($root . '/external-spec/bundled/spec-metadata.json'), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($metadata);
        self::assertIsArray($metadata['operations'] ?? null);
        $sync = (string) file_get_contents($root . '/src/GeneratedOperations.php');
        $async = (string) file_get_contents($root . '/src/GeneratedAsyncOperations.php');

        $checked = [];
        foreach ($metadata['operations'] as $op) {
            self::assertIsArray($op);
            self::assertIsString($op['operationId'] ?? null);
            $operationId = $op['operationId'];
            self::assertArrayHasKey('optionalTenantIdInBodyVariants', $op, "{$operationId}: metadata predates camunda-schema-bundler 2.5");
            self::assertArrayHasKey('optionalTenantIdsInBody', $op, "{$operationId}: metadata predates camunda-schema-bundler 2.5");
            self::assertIsArray($op['optionalTenantIdInBodyVariants']);
            /** @var list<string> $eligible */
            $eligible = $op['optionalTenantIdInBodyVariants'];
            $inBody = ($op['optionalTenantIdInBody'] ?? false) === true;
            $tenantIds = $op['optionalTenantIdsInBody'] === true;
            if ($eligible === [] && !$inBody && !$tenantIds) {
                continue;
            }
            /** @var list<string> $unionRefs */
            $unionRefs = is_array($op['requestBodyUnionRefs'] ?? null) ? $op['requestBodyUnionRefs'] : [];
            $allEligible = $inBody && $eligible !== [] && array_diff($unionRefs, $eligible) === [];
            foreach (['sync' => $sync, 'async' => $async] as $flavour => $trait) {
                $body = $this->methodBody($trait, $operationId);
                self::assertNotNull($body, "{$operationId} missing from {$flavour} facade");
                if ($tenantIds) {
                    self::assertStringContainsString('setTenantIds($this->config->defaultJobTenantIds())', $body, "{$operationId} ({$flavour}) does not default tenantIds");
                }
                if ($inBody && $eligible === []) {
                    self::assertStringContainsString('$tenantId = $this->config->defaultTenantId();', $body, "{$operationId} ({$flavour}) does not default its inline-body tenantId");
                }
                if ($eligible === []) {
                    continue;
                }
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
                    self::assertMatchesRegularExpression(
                        $this->injectionBlock($name),
                        $body,
                        "{$operationId} ({$flavour}) does not inject the default tenant on variant {$name} inside a null guard",
                    );
                }
            }
            $checked[$operationId] = true;
        }

        foreach (['suspendProcessDefinitions', 'resumeProcessDefinitions', 'createProcessInstance', 'publishMessage', 'createDeployment', 'activateJobs'] as $known) {
            self::assertArrayHasKey($known, $checked, "spec metadata did not flag {$known}");
        }
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
