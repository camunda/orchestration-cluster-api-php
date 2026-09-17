<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Closure;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class OperationHostHookTest extends TestCase
{
    public function testHookPatchesOperationHostCallsites(): void
    {
        $dir = $this->createGeneratedFixture($this->unpatchedApiSource());

        try {
            $hook = self::operationHostHook();
            $hook(['out_dir' => $dir]);

            $configuration = file_get_contents($dir . '/src/Configuration.php');
            self::assertNotFalse($configuration);
            $api = file_get_contents($dir . '/src/Api/ClusterApi.php');
            self::assertNotFalse($api);

            self::assertStringContainsString('protected array $operationHostVariables = [];', $configuration);
            self::assertStringContainsString('public function getOperationHostVariables(): array', $configuration);
            self::assertStringContainsString(
                '\Camunda\Orchestration\Http\OperationHost::resolveHost(',
                $api,
            );
            self::assertStringContainsString(
                '$this->config->getOperationHostVariables()',
                $api,
            );
            self::assertStringContainsString(
                '$variables',
                $api,
            );
            self::assertStringContainsString(
                '"url" => "{schema}://{host}:{port}{basePath}"',
                $api,
            );
            self::assertStringContainsString(
                '"basePath" => [',
                $api,
            );
            self::assertStringContainsString(
                'Configuration::getHostString($primaryHosts, $hostSelection, $variablesMap)',
                $api,
            );
        } finally {
            $this->removeDirectory($dir);
        }
    }

    public function testHookFailsWhenOperationHostCallsiteShapeChanges(): void
    {
        $dir = $this->createGeneratedFixture($this->divergentApiSource());

        try {
            $hook = self::operationHostHook();

            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage('hook 0125: expected 1 operation-specific host builder(s)');

            $hook(['out_dir' => $dir]);
        } finally {
            $this->removeDirectory($dir);
        }
    }

    public function testHookCanBeRequiredMoreThanOnce(): void
    {
        self::assertInstanceOf(Closure::class, self::operationHostHook());
        self::assertInstanceOf(Closure::class, self::operationHostHook());
    }

    /**
     * @return Closure(array{out_dir: string}): void
     */
    private static function operationHostHook(): Closure
    {
        /** @var Closure(array{out_dir: string}): void $hook */
        $hook = require dirname(__DIR__, 2) . '/hooks/post_gen/0125_operation_host_variables.php';

        return $hook;
    }

    private function createGeneratedFixture(string $apiSource): string
    {
        $dir = dirname(__DIR__, 2) . '/.operation-host-hook-' . bin2hex(random_bytes(8));
        self::assertTrue(mkdir($dir . '/src/Api', 0777, true));
        self::assertNotFalse(file_put_contents($dir . '/src/Configuration.php', $this->configurationSource()));
        self::assertNotFalse(file_put_contents($dir . '/src/Api/ClusterApi.php', $apiSource));

        return $dir;
    }

    private function configurationSource(): string
    {
        return <<<'PHP'
<?php

final class Configuration
{
    protected bool $ignoreOperationHosts = false;

    public function getIgnoreOperationHosts(): bool
    {
        return $this->ignoreOperationHosts;
    }
}
PHP;
    }

    private function unpatchedApiSource(): string
    {
        return <<<'PHP'
<?php

final class ClusterApi
{
    public function request(): void
    {
        $primaryHosts = $this->getPrimaryHosts();
        $ignoredHost = Configuration::getHostString($primaryHosts, $hostSelection, $variablesMap);
        $hostSettings = $this->getHostSettingsForstatus();
        $operationHost = Configuration::getHostString($hostSettings, $hostIndex, $variables);
    }

    protected function getHostSettingsForstatus(): array
    {
        return [
            [
                "url" => "{schema}://{host}:{port}",
                "description" => "No description provided",
                "variables" => [
                    "host" => [
                    "description" => "The hostname of the Orchestration Cluster REST Gateway.",
                    "default_value" => "localhost",
                    ],
                    "port" => [
                    "description" => "The port of the Orchestration Cluster REST API server.",
                    "default_value" => "8080",
                    ],
                    "schema" => [
                    "description" => "The schema of the Orchestration Cluster REST API server.",
                    "default_value" => "http",
                    ]
                ]
            ]
        ];
    }
}
PHP;
    }

    private function divergentApiSource(): string
    {
        return <<<'PHP'
<?php

final class ClusterApi
{
    public function request(): void
    {
        $hostSettings = $this->getHostSettingsForstatus();
        $operationHost = Configuration::getHostString(
            $hostSettings,
            $hostIndex,
            array_merge([], $variables),
        );
    }

    protected function getHostSettingsForstatus(): array
    {
        return [
            [
                "url" => "{schema}://{host}:{port}",
                "description" => "No description provided",
                "variables" => [
                    "host" => [
                    "description" => "The hostname of the Orchestration Cluster REST Gateway.",
                    "default_value" => "localhost",
                    ],
                    "port" => [
                    "description" => "The port of the Orchestration Cluster REST API server.",
                    "default_value" => "8080",
                    ],
                    "schema" => [
                    "description" => "The schema of the Orchestration Cluster REST API server.",
                    "default_value" => "http",
                    ]
                ]
            ]
        ];
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
