<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Closure;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Fixture-based regression tests for hook 0500's default-tenant injection:
 * the injected value must follow the generated model's setTenantId() type.
 */
final class FlatFacadeTenantHookTest extends TestCase
{
    private const STRING_INJECTION = '$expressionEvaluationRequest->setTenantId($this->config->defaultTenantId());';
    private const SEMANTIC_INJECTION = '$expressionEvaluationRequest->setTenantId(new \Camunda\Orchestration\Semantic\TenantId($this->config->defaultTenantId()));';

    /** @var (Closure(array{out_dir: string, root: string, spec_path: string, metadata_path?: string}): void)|null */
    private static ?Closure $hook = null;

    public function testStringSetterInjectsPlainStringInBothTraits(): void
    {
        [$sync, $async] = $this->runHook('public function setTenantId(?string $tenant_id)');

        foreach ([$sync, $async] as $trait) {
            self::assertStringContainsString(self::STRING_INJECTION, $trait);
            self::assertStringNotContainsString(self::SEMANTIC_INJECTION, $trait);
        }
    }

    public function testSemanticSetterInjectsTenantIdInBothTraits(): void
    {
        [$sync, $async] = $this->runHook('public function setTenantId(?\Camunda\Orchestration\Semantic\TenantId $tenant_id)');

        foreach ([$sync, $async] as $trait) {
            self::assertStringContainsString(self::SEMANTIC_INJECTION, $trait);
            self::assertStringNotContainsString(self::STRING_INJECTION, $trait);
        }
    }

    public function testSetterlessUnionModelDefaultsToTenantId(): void
    {
        [$sync, $async] = $this->runHook(null);

        foreach ([$sync, $async] as $trait) {
            self::assertStringContainsString(self::SEMANTIC_INJECTION, $trait);
        }
    }

    public function testConfiguredMetadataPathIsHonoured(): void
    {
        [$sync, $async] = $this->runHook('public function setTenantId(?string $tenant_id)', null, [], true);

        foreach ([$sync, $async] as $trait) {
            self::assertStringContainsString(self::STRING_INJECTION, $trait);
        }
    }

    public function testUnparseableSetterThrows(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot determine setTenantId() parameter type');

        $this->runHook('public function setTenantId($tenant_id)');
    }

    public function testOptionalBodyIsNullGuardedBeforeInjection(): void
    {
        $param = '?\Camunda\Orchestration\Api\Model\ExpressionEvaluationRequest $expressionEvaluationRequest = null';
        [$sync, $async] = $this->runHook('public function setTenantId(?string $tenant_id)', $param);

        foreach ([$sync, $async] as $trait) {
            self::assertStringContainsString('if ($expressionEvaluationRequest !== null && $expressionEvaluationRequest->getTenantId() === null) {', $trait);
        }
    }

    public function testRequiredBodyIsNotNullGuarded(): void
    {
        [$sync] = $this->runHook('public function setTenantId(?string $tenant_id)');

        self::assertStringContainsString('if ($expressionEvaluationRequest->getTenantId() === null) {', $sync);
    }

    public function testPartialUnionEmitsGuardedInjection(): void
    {
        // Only some variants of the union carry an optional tenantId: the
        // injection must be guarded per variant, never unguarded.
        [$sync, $async] = $this->runHook(null, null, [
            'optionalTenantIdInBody' => false,
            'optionalTenantIdInBodyVariants' => ['ExpressionEvaluationRequest'],
            'requestBodyUnionRefs' => ['ExpressionEvaluationRequest', 'Other'],
        ]);

        foreach ([$sync, $async] as $trait) {
            self::assertStringContainsString(
                'if ($expressionEvaluationRequest instanceof \Camunda\Orchestration\Api\Model\ExpressionEvaluationRequest && $expressionEvaluationRequest->getTenantId() === null) {',
                $trait,
            );
            self::assertStringNotContainsString('if ($expressionEvaluationRequest->getTenantId() === null) {', $trait);
        }
    }

    public function testUnionNotFullyListedIsGuardedEvenWhenBodyFlagIsSet(): void
    {
        [$sync] = $this->runHook(null, null, [
            'optionalTenantIdInBody' => true,
            'optionalTenantIdInBodyVariants' => ['ExpressionEvaluationRequest'],
            'requestBodyUnionRefs' => ['ExpressionEvaluationRequest', 'Other'],
        ]);

        self::assertStringNotContainsString('if ($expressionEvaluationRequest->getTenantId() === null) {', $sync);
        self::assertStringContainsString('$expressionEvaluationRequest instanceof', $sync);
    }

    public function testInlineBodyDefaultsTenantIdParameter(): void
    {
        [$sync, $async] = $this->runHook(null, '?string $tenantId = null', [
            'optionalTenantIdInBody' => true,
            'optionalTenantIdInBodyVariants' => [],
        ]);

        foreach ([$sync, $async] as $trait) {
            self::assertStringContainsString("if (\$tenantId === null) {\n            \$tenantId = \$this->config->defaultTenantId();", $trait);
        }
    }

    public function testInlineBodyWithoutTenantIdParameterFailsClosed(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('inline body with an optional tenantId');

        $this->runHook(null, '?string $other = null', [
            'optionalTenantIdInBody' => true,
            'optionalTenantIdInBodyVariants' => [],
        ]);
    }

    public function testOperationWithoutTenantMetadataIsUntouched(): void
    {
        [$sync] = $this->runHook(null, null, [
            'optionalTenantIdInBody' => false,
            'optionalTenantIdInBodyVariants' => [],
        ]);

        self::assertStringNotContainsString('defaultTenantId', $sync);
    }

    public function testMetadataWithoutTenantFieldsFailsClosed(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('re-bundle with camunda-schema-bundler');

        $this->runHook(null, null, ['optionalTenantIdInBody' => true, 'optionalTenantIdsInBody' => null]);
    }

    /**
     * @param array<string, mixed> $metadata overrides for the operation's spec-metadata entry
     * @return array{0: string, 1: string}
     */
    private function runHook(?string $setterSignature, ?string $param = null, array $metadata = [], bool $externalMetadata = false): array
    {
        $dir = dirname(__DIR__, 2) . '/.flat-facade-hook-' . bin2hex(random_bytes(8));
        self::assertTrue(mkdir($dir . '/out/src/Api', 0777, true));
        self::assertTrue(mkdir($dir . '/out/src/Model', 0777, true));
        self::assertTrue(mkdir($dir . '/root/src', 0777, true));

        try {
            self::assertNotFalse(file_put_contents($dir . '/out/src/Api/ExpressionApi.php', str_replace('__PARAM__', $param ?? '\Camunda\Orchestration\Api\Model\ExpressionEvaluationRequest $expressionEvaluationRequest', <<<'PHP'
<?php

final class ExpressionApi
{
    public function evaluateExpression(__PARAM__): object
    {
    }

    public function evaluateExpressionAsync(__PARAM__): object
    {
    }
}
PHP)));
            $model = "<?php\n\nfinal class ExpressionEvaluationRequest\n{\n"
                . ($setterSignature !== null ? "    {$setterSignature}\n    {\n    }\n" : '')
                . "}\n";
            self::assertNotFalse(file_put_contents($dir . '/out/src/Model/ExpressionEvaluationRequest.php', $model));

            $operation = array_merge([
                'operationId' => 'evaluateExpression',
                'requestBodyUnionRefs' => [],
                'optionalTenantIdInBody' => true,
                'optionalTenantIdInBodyVariants' => ['ExpressionEvaluationRequest'],
                'optionalTenantIdsInBody' => false,
            ], $metadata);
            // A null override drops the field, simulating pre-2.5 metadata.
            $operation = array_filter($operation, static fn ($v): bool => $v !== null);
            self::assertNotFalse(file_put_contents($dir . '/spec.json', '{}'));
            $metadataJson = (string) json_encode(['operations' => [$operation]]);
            $ctx = ['out_dir' => $dir . '/out', 'root' => $dir . '/root', 'spec_path' => $dir . '/spec.json'];
            if ($externalMetadata) {
                // Configured --metadata path outside the spec directory, plus a
                // conflicting sibling file that must be ignored.
                self::assertTrue(mkdir($dir . '/meta', 0777, true));
                self::assertNotFalse(file_put_contents($dir . '/meta/custom-metadata.json', $metadataJson));
                self::assertNotFalse(file_put_contents($dir . '/spec-metadata.json', (string) json_encode(['operations' => []])));
                $ctx['metadata_path'] = $dir . '/meta/custom-metadata.json';
            } else {
                self::assertNotFalse(file_put_contents($dir . '/spec-metadata.json', $metadataJson));
            }

            ob_start();
            try {
                self::hook()($ctx);
            } finally {
                ob_end_clean();
            }

            $sync = file_get_contents($dir . '/root/src/GeneratedOperations.php');
            $async = file_get_contents($dir . '/root/src/GeneratedAsyncOperations.php');
            self::assertNotFalse($sync);
            self::assertNotFalse($async);

            return [$sync, $async];
        } finally {
            $this->removeDirectory($dir);
        }
    }

    /**
     * @return Closure(array{out_dir: string, root: string, spec_path: string, metadata_path?: string}): void
     */
    private static function hook(): Closure
    {
        if (self::$hook === null) {
            // The hook file declares global helper functions, so it can only be loaded once.
            /** @var Closure(array{out_dir: string, root: string, spec_path: string, metadata_path?: string}): void $hook */
            $hook = require dirname(__DIR__, 2) . '/hooks/post_gen/0500_flat_facade.php';
            self::$hook = $hook;
        }

        return self::$hook;
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST,
        );
        foreach ($items as $item) {
            /** @var \SplFileInfo $item */
            $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        }
        rmdir($dir);
    }
}
