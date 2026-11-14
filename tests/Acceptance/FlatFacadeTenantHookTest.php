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
    private const STRING_INJECTION = '$expressionEvaluationRequest->setTenantId($this->config->tenantIds[0]);';
    private const SEMANTIC_INJECTION = '$expressionEvaluationRequest->setTenantId(new \Camunda\Orchestration\Semantic\TenantId($this->config->tenantIds[0]));';

    /** @var (Closure(array{out_dir: string, root: string}): void)|null */
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

    /**
     * @return array{0: string, 1: string}
     */
    private function runHook(?string $setterSignature): array
    {
        $dir = dirname(__DIR__, 2) . '/.flat-facade-hook-' . bin2hex(random_bytes(8));
        self::assertTrue(mkdir($dir . '/out/src/Api', 0777, true));
        self::assertTrue(mkdir($dir . '/out/src/Model', 0777, true));
        self::assertTrue(mkdir($dir . '/root/src', 0777, true));

        try {
            self::assertNotFalse(file_put_contents($dir . '/out/src/Api/ExpressionApi.php', <<<'PHP'
<?php

final class ExpressionApi
{
    public function evaluateExpression(\Camunda\Orchestration\Api\Model\ExpressionEvaluationRequest $expressionEvaluationRequest): object
    {
    }

    public function evaluateExpressionAsync(\Camunda\Orchestration\Api\Model\ExpressionEvaluationRequest $expressionEvaluationRequest): object
    {
    }
}
PHP));
            $model = "<?php\n\nfinal class ExpressionEvaluationRequest\n{\n"
                . ($setterSignature !== null ? "    {$setterSignature}\n    {\n    }\n" : '')
                . "}\n";
            self::assertNotFalse(file_put_contents($dir . '/out/src/Model/ExpressionEvaluationRequest.php', $model));

            ob_start();
            try {
                self::hook()(['out_dir' => $dir . '/out', 'root' => $dir . '/root']);
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
     * @return Closure(array{out_dir: string, root: string}): void
     */
    private static function hook(): Closure
    {
        if (self::$hook === null) {
            // The hook file declares global helper functions, so it can only be loaded once.
            /** @var Closure(array{out_dir: string, root: string}): void $hook */
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
