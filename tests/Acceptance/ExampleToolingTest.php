<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use PHPUnit\Framework\TestCase;

final class ExampleToolingTest extends TestCase
{
    private string $fixtureRoot;

    protected function setUp(): void
    {
        $this->fixtureRoot = sys_get_temp_dir() . '/orchestration-example-tooling-' . bin2hex(random_bytes(8));
        mkdir($this->fixtureRoot . '/examples', 0o755, true);
        mkdir($this->fixtureRoot . '/external-spec/bundled', 0o755, true);
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->fixtureRoot);
    }

    public function testSnippetCheckerSynchronizesSourcesAndRejectsUninjectedPhp(): void
    {
        $this->write('examples/readme.php', <<<'PHP'
            <?php
            // region Example
            function example(): void
            {
            }
            // endregion Example
            PHP);
        $this->write('README.md', <<<'MARKDOWN'
            <!-- snippet-source: examples/readme.php | regions: Example -->
            ```php
            stale
            ```
            MARKDOWN);

        [$status] = $this->runScript('sync-readme-snippets.php', '--check');
        self::assertSame(1, $status);

        [$status] = $this->runScript('sync-readme-snippets.php');
        self::assertSame(0, $status);
        self::assertStringContainsString("function example(): void\n{\n}", $this->read('README.md'));

        $this->write('README.md', $this->read('README.md') . "\n```php\n\$value = 1;\n```\n");
        [$status, , $stderr] = $this->runScript('sync-readme-snippets.php', '--check');

        self::assertSame(1, $status);
        self::assertStringContainsString('without a snippet source', $stderr);
    }

    public function testSnippetCheckerRejectsMissingRegion(): void
    {
        $this->write('examples/readme.php', "<?php\n");
        $this->write('README.md', <<<'MARKDOWN'
            <!-- snippet-source: examples/readme.php | regions: Missing -->
            ```php
            stale
            ```
            MARKDOWN);

        [$status, , $stderr] = $this->runScript('sync-readme-snippets.php', '--check');

        self::assertSame(1, $status);
        self::assertStringContainsString("Region 'Missing' does not exist", $stderr);
    }

    public function testSnippetCheckerRejectsMarkerWithoutPhpFence(): void
    {
        $this->write('examples/readme.php', <<<'PHP'
            <?php
            // region Example
            function example(): void
            {
            }
            // endregion Example
            PHP);
        $this->write('README.md', <<<'MARKDOWN'
            <!-- snippet-source: examples/readme.php | regions: Example -->
            ```json
            stale
            ```
            MARKDOWN);

        [$status, , $stderr] = $this->runScript('sync-readme-snippets.php', '--check');

        self::assertSame(1, $status);
        self::assertStringContainsString('must be followed by a PHP code fence', $stderr);
    }

    public function testCoverageCheckerUsesExactOperationIdsAndStrictMode(): void
    {
        $this->write('examples/workflow.php', <<<'PHP'
            <?php
            // region GetWorkflow
            function get_workflow(): void
            {
            }
            // endregion GetWorkflow
            PHP);
        $this->write('external-spec/bundled/spec-metadata.json', json_encode([
            'operations' => [
                ['operationId' => 'getWorkflow'],
                ['operationId' => 'deleteWorkflow'],
            ],
        ], JSON_THROW_ON_ERROR));
        $this->write('examples/operation-map.json', json_encode([
            'getWorkflow' => [
                ['file' => 'workflow.php', 'region' => 'GetWorkflow'],
            ],
        ], JSON_THROW_ON_ERROR));

        [$status, $stdout] = $this->runScript('check-example-coverage.php');
        self::assertSame(0, $status);
        self::assertStringContainsString('Covered:         1', $stdout);

        [$status, , $stderr] = $this->runScript('check-example-coverage.php', '--strict');
        self::assertSame(1, $status);
        self::assertStringContainsString('deleteWorkflow', $stderr);
    }

    public function testCoverageCheckerRejectsDuplicateRegions(): void
    {
        $this->write('examples/first.php', "// region Duplicate\n// endregion Duplicate\n");
        $this->write('examples/second.php', "// region Duplicate\n// endregion Duplicate\n");
        $this->write('external-spec/bundled/spec-metadata.json', json_encode([
            'operations' => [['operationId' => 'getWorkflow']],
        ], JSON_THROW_ON_ERROR));
        $this->write('examples/operation-map.json', json_encode([
            'getWorkflow' => [
                ['file' => 'first.php', 'region' => 'Duplicate'],
            ],
        ], JSON_THROW_ON_ERROR));

        [$status, , $stderr] = $this->runScript('check-example-coverage.php');

        self::assertSame(1, $status);
        self::assertStringContainsString("Duplicate region 'Duplicate'", $stderr);
    }

    /**
     * @return array{0: int, 1: string, 2: string}
     */
    private function runScript(string $script, string ...$arguments): array
    {
        /** @var list<string> $command */
        $command = [PHP_BINARY, dirname(__DIR__, 2) . '/scripts/' . $script, '--root', $this->fixtureRoot];
        foreach ($arguments as $argument) {
            $command[] = $argument;
        }

        $pipes = [];
        $process = proc_open(
            $command,
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes,
        );
        self::assertIsResource($process);
        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        return [proc_close($process), (string) $stdout, (string) $stderr];
    }

    private function write(string $path, string $content): void
    {
        $fullPath = $this->fixtureRoot . '/' . $path;
        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0o755, true);
        }
        file_put_contents($fullPath, $content . "\n");
    }

    private function read(string $path): string
    {
        return (string) file_get_contents($this->fixtureRoot . '/' . $path);
    }

    private function removeDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }

        foreach (scandir($directory) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $path = $directory . '/' . $entry;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($directory);
    }
}
