<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Closure;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class SemanticUnionHydrationHookTest extends TestCase
{
    private static ?Closure $hook = null;

    public function testHookIsIdempotentForPreviouslyHydratedModels(): void
    {
        $dir = $this->createGeneratedFixture($this->unpatchedModelSource());

        try {
            $hook = self::semanticUnionHydrationHook();
            $context = $this->hookContext($dir);

            $hook($context);
            $hydrated = $this->readModel($dir);
            self::assertStringContainsString("\$isArray = str_ends_with(\$semanticType, '[]');", $hydrated);

            $hook($context);

            self::assertSame($hydrated, $this->readModel($dir));
        } finally {
            $this->removeDirectory($dir);
        }
    }

    public function testHookFailsWhenSemanticUnionModelShapeChanges(): void
    {
        $dir = $this->createGeneratedFixture($this->unexpectedModelSource());

        try {
            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage(
                '[semantic-union-hydration] expected to patch setIfExists() in AdvancedResourceKeyFilter.php',
            );

            self::semanticUnionHydrationHook()($this->hookContext($dir));
        } finally {
            $this->removeDirectory($dir);
        }
    }

    /**
     * @return Closure(array{out_dir: string, metadata_path: string}): void
     */
    private static function semanticUnionHydrationHook(): Closure
    {
        if (self::$hook !== null) {
            return self::$hook;
        }

        /** @var Closure(array{out_dir: string, metadata_path: string}): void $hook */
        $hook = require dirname(__DIR__, 2) . '/hooks/post_gen/0350_semantic_union_constructor_hydration.php';

        return self::$hook = $hook;
    }

    /**
     * @return array{out_dir: string, metadata_path: string}
     */
    private function hookContext(string $dir): array
    {
        return [
            'out_dir' => $dir,
            'metadata_path' => $dir . '/metadata.json',
        ];
    }

    private function createGeneratedFixture(string $modelSource): string
    {
        $dir = dirname(__DIR__, 2) . '/.semantic-union-hydration-hook-' . bin2hex(random_bytes(8));
        self::assertTrue(mkdir($dir . '/src/Model', 0o777, true));
        self::assertNotFalse(file_put_contents($dir . '/src/Model/AdvancedResourceKeyFilter.php', $modelSource));
        self::assertNotFalse(file_put_contents($dir . '/metadata.json', json_encode([
            'semanticKeys' => [
                ['name' => 'ProcessDefinitionKey'],
                ['name' => 'DecisionRequirementsKey'],
            ],
            'unions' => [
                [
                    'name' => 'ResourceKey',
                    'branches' => [
                        ['branchType' => 'ref', 'ref' => 'ProcessDefinitionKey'],
                        ['branchType' => 'ref', 'ref' => 'DecisionRequirementsKey'],
                    ],
                ],
            ],
        ], JSON_THROW_ON_ERROR)));

        return $dir;
    }

    private function readModel(string $dir): string
    {
        $source = file_get_contents($dir . '/src/Model/AdvancedResourceKeyFilter.php');
        self::assertNotFalse($source);

        return $source;
    }

    private function unpatchedModelSource(): string
    {
        return <<<'PHP'
<?php

final class AdvancedResourceKeyFilter
{
    /**
     * @var array<string, string>
     */
    protected static array $openAPITypes = [
        'eq' => '\Camunda\Orchestration\Semantic\ResourceKey',
        'in' => '\Camunda\Orchestration\Semantic\ResourceKey[]',
    ];

    /**
     * @param array<string, mixed> $fields
     */
    protected function setIfExists(string $variableName, array $fields, mixed $defaultValue): void
    {
        $this->container[$variableName] = $fields[$variableName] ?? $defaultValue;
    }
}
PHP;
    }

    private function unexpectedModelSource(): string
    {
        return str_replace(
            '$this->container[$variableName] = $fields[$variableName] ?? $defaultValue;',
            '$this->container[$variableName] = $defaultValue;',
            $this->unpatchedModelSource(),
        );
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        foreach (scandir($dir) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $path = $dir . '/' . $entry;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                self::assertTrue(unlink($path));
            }
        }

        self::assertTrue(rmdir($dir));
    }
}
