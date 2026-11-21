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

    /** @var (Closure(array{out_dir: string, root: string, spec_path: string}): void)|null */
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

    public function testUnionNestedUnderAllOfFailsClosed(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unsupported composed request body for evaluateExpression');

        $this->runHook(null, null, ['allOf' => [['oneOf' => [['$ref' => '#/components/schemas/Other']]]]]);
    }

    public function testUnionWithEnclosingConstraintsFailsClosed(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unsupported composed request body for evaluateExpression');

        $this->runHook(null, null, ['required' => ['tenantId'], 'oneOf' => [['$ref' => '#/components/schemas/Other']]]);
    }

    public function testRequestBodyComponentReferenceIsResolved(): void
    {
        [$sync, $async] = $this->runHook('public function setTenantId(?string $tenant_id): self', null, null, ['$ref' => '#/components/requestBodies/EvalBody']);

        self::assertStringContainsString(self::STRING_INJECTION, $sync);
        self::assertStringContainsString(self::STRING_INJECTION, $async);
    }

    public function testInlineBodyWithTenantIdFailsClosed(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unsupported composed request body for evaluateExpression');

        $this->runHook(null, null, null, ['content' => ['application/json' => ['schema' => ['type' => 'object', 'properties' => ['tenantId' => ['type' => 'string']]]]]]);
    }

    public function testInlineUnionVariantWithTenantIdFailsClosed(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unsupported composed request body for evaluateExpression');

        $this->runHook(null, null, ['oneOf' => [['$ref' => '#/components/schemas/Other'], ['type' => 'object', 'properties' => ['tenantId' => ['type' => 'string']]]]]);
    }

    public function testNestedUnionInsideVariantFailsClosed(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unsupported composed request body for evaluateExpression');

        $this->runHook(null, null, ['oneOf' => [['$ref' => '#/components/schemas/Nested']]]);
    }

    /**
     * @param array<string, mixed>|null $bodySchema
     * @param array<string, mixed>|null $requestBody
     * @return array{0: string, 1: string}
     */
    private function runHook(?string $setterSignature, ?string $param = null, ?array $bodySchema = null, ?array $requestBody = null): array
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

            $spec = [
                'paths' => ['/expression/evaluation' => ['post' => [
                    'operationId' => 'evaluateExpression',
                    'requestBody' => $requestBody ?? ['content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/ExpressionEvaluationRequest']]]],
                ]]],
                'components' => ['requestBodies' => [
                    'EvalBody' => ['content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/ExpressionEvaluationRequest']]]],
                ], 'schemas' => [
                    'ExpressionEvaluationRequest' => $bodySchema ?? [
                        'type' => 'object',
                        'properties' => ['tenantId' => ['type' => 'string']],
                    ],
                    'Other' => ['type' => 'object', 'properties' => ['tenantId' => ['type' => 'string']]],
                    'Nested' => ['oneOf' => [['$ref' => '#/components/schemas/Other']]],
                ]],
            ];
            self::assertNotFalse(file_put_contents($dir . '/spec.json', (string) json_encode($spec)));

            ob_start();
            try {
                self::hook()(['out_dir' => $dir . '/out', 'root' => $dir . '/root', 'spec_path' => $dir . '/spec.json']);
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
     * @return Closure(array{out_dir: string, root: string, spec_path: string}): void
     */
    private static function hook(): Closure
    {
        if (self::$hook === null) {
            // The hook file declares global helper functions, so it can only be loaded once.
            /** @var Closure(array{out_dir: string, root: string, spec_path: string}): void $hook */
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
