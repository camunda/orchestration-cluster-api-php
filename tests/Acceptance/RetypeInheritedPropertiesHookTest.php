<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Closure;
use PHPUnit\Framework\TestCase;

/**
 * Regression test for the `allOf`-inheritance gap in post-generation hook 0300.
 *
 * Since upstream spec commit camunda/camunda@cd1294e5 ("share common fields of
 * process instance creation variants"), the shared creation fields (`tenantId`,
 * `businessId`, …) live on a `ProcessInstanceCreationInstructionBase` parent that
 * `…ById` / `…ByKey` / `…ByVersionTag` extend via `allOf`. openapi-generator
 * flattens those inherited properties into each variant as bare `string`.
 *
 * The hook used to read only each schema's own `properties`, so it never followed
 * the `allOf` `$ref` to the parent and silently dropped the semantic value objects
 * (`Semantic\TenantId`, `Semantic\BusinessId`) — which in turn broke phpstan on the
 * default-tenant injection from #58. This test reproduces the `allOf` refactor with
 * a synthetic spec and asserts the three variants keep their semantic types.
 */
final class RetypeInheritedPropertiesHookTest extends TestCase
{
    private const SEMANTIC_NS = '\\Camunda\\Orchestration\\Semantic\\';

    /** @var list<string> */
    private const VARIANTS = [
        'ProcessInstanceCreationInstructionById',
        'ProcessInstanceCreationInstructionByKey',
        'ProcessInstanceCreationInstructionByVersionTag',
    ];

    private static ?Closure $hook = null;

    public function testInheritedSemanticPropertiesAreRetyped(): void
    {
        $dir = $this->createFixture();

        try {
            $hook = self::retypeHook();
            $hook([
                'out_dir' => $dir,
                'spec_path' => $dir . '/spec.json',
                'metadata_path' => $dir . '/metadata.json',
            ]);

            foreach (self::VARIANTS as $variant) {
                $src = file_get_contents($dir . '/src/Model/' . $variant . '.php');
                self::assertNotFalse($src);

                self::assertStringContainsString(
                    "'tenantId' => '" . self::SEMANTIC_NS . "TenantId'",
                    $src,
                    "$variant must keep Semantic\\TenantId for the allOf-inherited tenantId"
                );
                self::assertStringContainsString(
                    "'businessId' => '" . self::SEMANTIC_NS . "BusinessId'",
                    $src,
                    "$variant must keep Semantic\\BusinessId for the allOf-inherited businessId"
                );
                self::assertStringNotContainsString("'tenantId' => 'string'", $src);
                self::assertStringNotContainsString("'businessId' => 'string'", $src);

                // The setter must accept the value object, not ?string — otherwise
                // default-tenant injection fails phpstan (and TypeErrors at runtime).
                self::assertMatchesRegularExpression(
                    '/public function setTenantId\(\??' . preg_quote(self::SEMANTIC_NS . 'TenantId', '/') . ' /',
                    $src,
                    "$variant setTenantId must accept Semantic\\TenantId"
                );
            }
        } finally {
            $this->removeDirectory($dir);
        }
    }

    /**
     * @return Closure(array{out_dir: string, spec_path: string, metadata_path: string}): void
     */
    private static function retypeHook(): Closure
    {
        // The hook file declares global helper functions, so it can only be required
        // once per PHP process — cache the closure across tests.
        if (self::$hook !== null) {
            return self::$hook;
        }

        /** @var Closure(array{out_dir: string, spec_path: string, metadata_path: string}): void $hook */
        $hook = require dirname(__DIR__, 2) . '/hooks/post_gen/0300_retype_models.php';

        return self::$hook = $hook;
    }

    private function createFixture(): string
    {
        $dir = dirname(__DIR__, 2) . '/.retype-hook-' . bin2hex(random_bytes(8));
        self::assertTrue(mkdir($dir . '/src/Model', 0777, true));

        self::assertNotFalse(file_put_contents($dir . '/spec.json', json_encode($this->spec())));
        self::assertNotFalse(file_put_contents($dir . '/metadata.json', json_encode($this->metadata())));

        foreach (self::VARIANTS as $variant) {
            self::assertNotFalse(
                file_put_contents($dir . '/src/Model/' . $variant . '.php', $this->modelFixture($variant))
            );
        }

        return $dir;
    }

    /**
     * Synthetic spec mirroring the upstream `allOf` refactor: the shared fields live
     * on a `…Base` parent that each variant extends via `allOf`.
     *
     * @return array<string, mixed>
     */
    private function spec(): array
    {
        $extendsBase = static fn (array $ownProps): array => [
            'allOf' => [
                ['$ref' => '#/components/schemas/ProcessInstanceCreationInstructionBase'],
                ['type' => 'object', 'properties' => $ownProps],
            ],
        ];

        return [
            'components' => [
                'schemas' => [
                    'ProcessInstanceCreationInstructionBase' => [
                        'type' => 'object',
                        'properties' => [
                            'tenantId' => ['allOf' => [['$ref' => '#/components/schemas/TenantId']]],
                            'businessId' => ['allOf' => [['$ref' => '#/components/schemas/BusinessId']]],
                        ],
                    ],
                    'ProcessInstanceCreationInstructionById' => $extendsBase([
                        'processDefinitionId' => ['allOf' => [['$ref' => '#/components/schemas/ProcessDefinitionId']]],
                    ]),
                    'ProcessInstanceCreationInstructionByKey' => $extendsBase([
                        'processDefinitionKey' => ['allOf' => [['$ref' => '#/components/schemas/ProcessDefinitionKey']]],
                    ]),
                    'ProcessInstanceCreationInstructionByVersionTag' => $extendsBase([
                        'versionTag' => ['type' => 'string'],
                    ]),
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
                ['name' => 'ProcessDefinitionKey'],
            ],
        ];
    }

    /**
     * A minimal model file shaped like openapi-generator's flattened output: the
     * inherited `tenantId` / `businessId` appear as bare `string` entries with
     * matching getters and setters.
     */
    private function modelFixture(string $class): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace Camunda\\Orchestration\\Api\\Model;

class {$class}
{
    protected static array \$openAPITypes = [
        'tenantId' => 'string',
        'businessId' => 'string',
    ];

    public function getTenantId(): ?string
    {
        return \$this->container['tenantId'];
    }

    public function setTenantId(?string \$tenantId): static
    {
        \$this->container['tenantId'] = \$tenantId;

        return \$this;
    }

    public function getBusinessId(): ?string
    {
        return \$this->container['businessId'];
    }

    public function setBusinessId(?string \$businessId): static
    {
        \$this->container['businessId'] = \$businessId;

        return \$this;
    }
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
