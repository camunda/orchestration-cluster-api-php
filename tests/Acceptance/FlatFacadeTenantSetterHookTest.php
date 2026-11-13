<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Hook 0500 injects the configured default tenant into tenant-aware request
 * bodies. Whether that value is a plain string or a Semantic\TenantId has to
 * follow the generated setTenantId() signature of every model the body
 * parameter accepts: upstream retyped ExpressionEvaluationRequest.tenantId to
 * TenantId, and a hardcoded "string" made the facade throw a TypeError at
 * runtime. These tests pin the derivation for any operation, not just that one.
 */
final class FlatFacadeTenantSetterHookTest extends TestCase
{
    private string $modelDir = '';

    protected function setUp(): void
    {
        if (!function_exists('tenant_setter_takes_string')) {
            // The hook file declares global helper functions; require it once per process.
            require dirname(__DIR__, 2) . '/hooks/post_gen/0500_flat_facade.php';
        }

        $this->modelDir = sys_get_temp_dir() . '/flat-facade-models-' . bin2hex(random_bytes(8));
        self::assertTrue(mkdir($this->modelDir, 0777, true));
        $this->model('StringTenant', '?string');
        $this->model('SemanticTenant', '?\Camunda\Orchestration\Semantic\TenantId');
        $this->model('OtherSemanticTenant', '?\Camunda\Orchestration\Semantic\TenantId');
        self::assertNotFalse(file_put_contents($this->modelDir . '/NoTenant.php', "<?php\nclass NoTenant {}\n"));
    }

    protected function tearDown(): void
    {
        foreach (glob($this->modelDir . '/*.php') ?: [] as $file) {
            unlink($file);
        }
        rmdir($this->modelDir);
    }

    public function testStringSetterInjectsPlainString(): void
    {
        self::assertTrue($this->derive('\Camunda\Orchestration\Api\Model\StringTenant $body'));
    }

    public function testSemanticSetterInjectsTenantId(): void
    {
        self::assertFalse($this->derive('\Camunda\Orchestration\Api\Model\SemanticTenant $body'));
    }

    public function testUnionBodyUsesTheSharedSetterType(): void
    {
        self::assertFalse($this->derive(
            '\Camunda\Orchestration\Api\Model\SemanticTenant|\Camunda\Orchestration\Api\Model\OtherSemanticTenant $body, string $contentType = self::contentTypes[\'op\'][0]',
        ));
    }

    public function testUnionMembersThatDisagreeFailGeneration(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('body models disagree');

        $this->derive('\Camunda\Orchestration\Api\Model\SemanticTenant|\Camunda\Orchestration\Api\Model\StringTenant $body');
    }

    public function testModelWithoutSetterFailsGeneration(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('model NoTenant has no setTenantId()');

        $this->derive('\Camunda\Orchestration\Api\Model\NoTenant $body');
    }

    public function testMissingBodyParameterFailsGeneration(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('has no $body body parameter');

        $this->derive('\Camunda\Orchestration\Api\Model\SemanticTenant $other');
    }

    public function testInjectionWrapsOnlyForSemanticSetters(): void
    {
        self::assertStringContainsString(
            'setTenantId($this->config->tenantIds[0])',
            tenant_injection('body', true),
        );
        self::assertStringContainsString(
            'setTenantId(new \Camunda\Orchestration\Semantic\TenantId($this->config->tenantIds[0]))',
            tenant_injection('body', false),
        );
    }

    private function derive(string $rawParams): bool
    {
        return tenant_setter_takes_string('op', $rawParams, 'body', $this->modelDir);
    }

    private function model(string $class, string $type): void
    {
        $source = <<<PHP
<?php
class {$class}
{
    public function setTenantId({$type} \$tenantId): static
    {
        return \$this;
    }
}

PHP;
        self::assertNotFalse(file_put_contents($this->modelDir . "/{$class}.php", $source));
    }
}
