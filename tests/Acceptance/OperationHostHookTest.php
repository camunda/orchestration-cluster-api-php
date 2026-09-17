<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use PHPUnit\Framework\TestCase;
use RuntimeException;

final class OperationHostHookTest extends TestCase
{
    public function testHookPatchesOperationHostCallsites(): void
    {
        $dir = $this->createGeneratedFixture($this->multilineUnpatchedApiSource());

        try {
            $hook = require dirname(__DIR__, 2) . '/hooks/post_gen/0125_operation_host_variables.php';
            $hook(['out_dir' => $dir]);

            $configuration = file_get_contents($dir . '/src/Configuration.php');
            self::assertNotFalse($configuration);
            $api = file_get_contents($dir . '/src/Api/ClusterApi.php');
            self::assertNotFalse($api);

            self::assertStringContainsString('protected array $operationHostVariables = [];', $configuration);
            self::assertStringContainsString('public function getOperationHostVariables(): array', $configuration);
            self::assertStringContainsString(
                'array_replace($this->config->getOperationHostVariables(), $variables)',
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
            $hook = require dirname(__DIR__, 2) . '/hooks/post_gen/0125_operation_host_variables.php';

            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage('hook 0125: expected to classify 1 operation-specific host call(s)');

            $hook(['out_dir' => $dir]);
        } finally {
            $this->removeDirectory($dir);
        }
    }

    private function createGeneratedFixture(string $apiSource): string
    {
        $dir = sys_get_temp_dir() . '/operation-host-hook-' . bin2hex(random_bytes(8));
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

    private function multilineUnpatchedApiSource(): string
    {
        return <<<'PHP'
<?php

final class ClusterApi
{
    public function request(): void
    {
        $serverSettings = $this->getHostSettingsForstatus();
        $resolvedHost = Configuration::getHostString(
            $serverSettings,
            $hostSelection,
            $variables,
        );
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
        $serverSettings = $this->getHostSettingsForstatus();
        $resolvedHost = Configuration::getHostString(
            $serverSettings,
            $hostSelection,
            array_merge([], $variables),
        );
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
