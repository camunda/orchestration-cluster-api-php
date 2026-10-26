<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Closure;
use PHPUnit\Framework\TestCase;

/**
 * Regression test for #63/#65: the `0300_retype_models.php` hook must follow
 * `allOf` `$ref`s to parent schemas when collecting a model's properties.
 *
 * After upstream shared the common process-creation fields into
 * `ProcessInstanceCreationInstructionBase` (camunda/camunda@cd1294e5, backported
 * to `stable/8.10` in @9df44701), `tenantId`/`businessId` are inherited by the
 * `ById`/`ByKey`/`ByVersionTag` variants through `allOf`. The hook previously read
 * only `$schema['properties']`, so the inherited identifiers silently regressed
 * from `Semantic\TenantId` / `Semantic\BusinessId` back to `string`.
 */
final class RetypeInheritedSemanticHookTest extends TestCase
{
    private static ?Closure $hook = null;

    /**
     * @return iterable<string, array{0: string}>
     */
    public static function variantProvider(): iterable
    {
        yield 'ById' => ['ProcessInstanceCreationInstructionById'];
        yield 'ByKey' => ['ProcessInstanceCreationInstructionByKey'];
        yield 'ByVersionTag' => ['ProcessInstanceCreationInstructionByVersionTag'];
    }

    /**
     * @dataProvider variantProvider
     */
    public function testInheritedIdentifiersKeepSemanticTypes(string $variant): void
    {
        $dir = $this->createFixture();

        try {
            $hook = self::retypeHook();
            $hook($this->context($dir));

            $retyped = file_get_contents($dir . '/out/src/Model/' . $variant . '.php');
            self::assertNotFalse($retyped);

            self::assertStringContainsString(
                "'tenantId' => '\\Camunda\\Orchestration\\Semantic\\TenantId'",
                $retyped,
                $variant . ' should retain Semantic\\TenantId for its inherited tenantId property',
            );
            self::assertStringContainsString(
                "'businessId' => '\\Camunda\\Orchestration\\Semantic\\BusinessId'",
                $retyped,
                $variant . ' should retain Semantic\\BusinessId for its inherited businessId property',
            );
            self::assertStringNotContainsString(
                "'tenantId' => 'string'",
                $retyped,
                $variant . ' must not regress tenantId to a bare string',
            );
            self::assertStringNotContainsString(
                "'businessId' => 'string'",
                $retyped,
                $variant . ' must not regress businessId to a bare string',
            );
        } finally {
            $this->removeDirectory($dir);
        }
    }

    public function testOwnPropertiesStillRetyped(): void
    {
        $dir = $this->createFixture();

        try {
            $hook = self::retypeHook();
            $hook($this->context($dir));

            // The variant's *own* (non-inherited) identifier must keep working too.
            $byId = file_get_contents($dir . '/out/src/Model/ProcessInstanceCreationInstructionById.php');
            self::assertNotFalse($byId);
            self::assertStringContainsString(
                "'processDefinitionId' => '\\Camunda\\Orchestration\\Semantic\\ProcessDefinitionId'",
                $byId,
            );
        } finally {
            $this->removeDirectory($dir);
        }
    }

    /**
     * @return Closure(array<string, mixed>): void
     */
    private static function retypeHook(): Closure
    {
        // The hook declares global helper functions, so it can only be required
        // once per PHP process — cache the closure across tests.
        if (self::$hook !== null) {
            return self::$hook;
        }

        /** @var Closure(array<string, mixed>): void $hook */
        $hook = require dirname(__DIR__, 2) . '/hooks/post_gen/0300_retype_models.php';

        return self::$hook = $hook;
    }

    /**
     * @return array<string, mixed>
     */
    private function context(string $dir): array
    {
        return [
            'out_dir' => $dir . '/out',
            'spec_path' => $dir . '/spec.json',
            'metadata_path' => $dir . '/metadata.json',
        ];
    }

    private function createFixture(): string
    {
        $dir = dirname(__DIR__, 2) . '/.retype-inherited-hook-' . bin2hex(random_bytes(8));
        self::assertTrue(mkdir($dir . '/out/src/Model', 0777, true));

        file_put_contents($dir . '/spec.json', json_encode($this->spec(), JSON_PRETTY_PRINT));
        file_put_contents($dir . '/metadata.json', json_encode($this->metadata(), JSON_PRETTY_PRINT));

        foreach (self::variantProvider() as [$variant]) {
            $own = $variant === 'ProcessInstanceCreationInstructionById'
                ? "        'processDefinitionId' => 'string',\n"
                : '';
            file_put_contents(
                $dir . '/out/src/Model/' . $variant . '.php',
                $this->modelSource($variant, $own),
            );
        }

        return $dir;
    }

    /**
     * A synthetic post-refactor spec: the three variants inherit `tenantId` /
     * `businessId` from a shared base via `allOf`.
     *
     * @return array<string, mixed>
     */
    private function spec(): array
    {
        $semanticRef = static fn (string $key): array => [
            'allOf' => [['$ref' => '#/components/schemas/' . $key]],
        ];

        $variant = static fn (array $extra): array => [
            'allOf' => [
                ['$ref' => '#/components/schemas/ProcessInstanceCreationInstructionBase'],
                ['type' => 'object', 'properties' => $extra],
            ],
        ];

        return [
            'components' => [
                'schemas' => [
                    'TenantId' => ['type' => 'string'],
                    'BusinessId' => ['type' => 'string'],
                    'ProcessDefinitionId' => ['type' => 'string'],
                    'ProcessInstanceCreationInstructionBase' => [
                        'type' => 'object',
                        'properties' => [
                            'tenantId' => $semanticRef('TenantId'),
                            'businessId' => $semanticRef('BusinessId'),
                        ],
                    ],
                    'ProcessInstanceCreationInstructionById' => $variant([
                        'processDefinitionId' => $semanticRef('ProcessDefinitionId'),
                    ]),
                    'ProcessInstanceCreationInstructionByKey' => $variant([]),
                    'ProcessInstanceCreationInstructionByVersionTag' => $variant([]),
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function metadata(): array
    {
        return [
            'semanticKeys' => [
                ['name' => 'TenantId'],
                ['name' => 'BusinessId'],
                ['name' => 'ProcessDefinitionId'],
            ],
        ];
    }

    private function modelSource(string $class, string $ownProps): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace Camunda\\Orchestration\\Api\\Model;

class {$class}
{
    protected static \$openAPITypes = [
{$ownProps}        'tenantId' => 'string',
        'businessId' => 'string'
    ];
}
PHP;
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $items = scandir($dir);
        if ($items === false) {
            return;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $this->removeDirectory($path);

                continue;
            }

            self::assertTrue(unlink($path));
        }

        self::assertTrue(rmdir($dir));
    }
}
