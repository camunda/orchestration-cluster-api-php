<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Configuration;
use Camunda\Orchestration\Api\ObjectSerializer;
use Closure;
use FilesystemIterator;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use SplFileInfo;

/**
 * Boolean query parameters must serialize as `true`/`false` (JSON-style), never
 * PHP-style `1`/`0` — the Camunda 8 Orchestration Cluster API rejects the latter.
 * See https://github.com/camunda/orchestration-cluster-api-php/issues/51.
 */
final class BooleanQueryFormatTest extends TestCase
{
    public function testGeneratedConfigurationDefaultsToStringBooleanFormat(): void
    {
        self::assertSame(
            Configuration::BOOLEAN_FORMAT_STRING,
            (new Configuration())->getBooleanFormatForQueryString(),
        );
        self::assertSame(
            Configuration::BOOLEAN_FORMAT_STRING,
            Configuration::getDefaultConfiguration()->getBooleanFormatForQueryString(),
        );
    }

    public function testToQueryValueSerializesBooleansAsTrueFalse(): void
    {
        self::assertSame(
            ['verbose' => 'true'],
            ObjectSerializer::toQueryValue(true, 'verbose', 'boolean'),
        );
        self::assertSame(
            ['verbose' => 'false'],
            ObjectSerializer::toQueryValue(false, 'verbose', 'boolean'),
        );
    }

    public function testToQueryValueConvertsBooleansRegardlessOfOpenApiType(): void
    {
        // A boolean must never leak into a query string as 1/0, even when the
        // declared OpenAPI type is not `boolean`.
        self::assertSame(
            ['flag' => 'true'],
            ObjectSerializer::toQueryValue(true, 'flag', 'string'),
        );
    }

    public function testBuildQuerySerializesBooleansAsTrueFalse(): void
    {
        self::assertSame(
            'soft=true&dryRun=false',
            ObjectSerializer::buildQuery(['soft' => true, 'dryRun' => false]),
        );
    }

    public function testHookPatchesConfigurationAndSerializer(): void
    {
        $dir = $this->createGeneratedFixture();

        try {
            $hook = self::booleanQueryFormatHook();
            $hook(['out_dir' => $dir]);

            $configuration = file_get_contents($dir . '/src/Configuration.php');
            self::assertNotFalse($configuration);
            self::assertStringContainsString(
                'protected string $booleanFormatForQueryString = self::BOOLEAN_FORMAT_STRING;',
                $configuration,
            );
            self::assertStringNotContainsString(
                'protected string $booleanFormatForQueryString = self::BOOLEAN_FORMAT_INT;',
                $configuration,
            );

            $serializer = file_get_contents($dir . '/src/ObjectSerializer.php');
            self::assertNotFalse($serializer);
            self::assertStringContainsString(
                "if (is_bool(\$value)) {\n            \$value = self::convertBoolToQueryStringFormat(\$value);",
                $serializer,
            );
            self::assertStringNotContainsString(
                "if ('boolean' === \$openApiType && is_bool(\$value)) {",
                $serializer,
            );
        } finally {
            $this->removeDirectory($dir);
        }
    }

    public function testHookIsIdempotent(): void
    {
        $dir = $this->createGeneratedFixture();

        try {
            $hook = self::booleanQueryFormatHook();
            $hook(['out_dir' => $dir]);
            $hook(['out_dir' => $dir]);

            $configuration = file_get_contents($dir . '/src/Configuration.php');
            self::assertNotFalse($configuration);
            self::assertSame(
                1,
                substr_count($configuration, 'protected string $booleanFormatForQueryString = self::BOOLEAN_FORMAT_STRING;'),
            );

            $serializer = file_get_contents($dir . '/src/ObjectSerializer.php');
            self::assertNotFalse($serializer);
            self::assertSame(1, substr_count($serializer, 'convertBoolToQueryStringFormat($value)'));
        } finally {
            $this->removeDirectory($dir);
        }
    }

    public function testHookFailsWhenConfigurationShapeChanges(): void
    {
        $dir = $this->createGeneratedFixture(configurationSource: <<<'PHP'
<?php

final class Configuration
{
    protected string $booleanFormatForQueryString = self::BOOLEAN_FORMAT_SOMETHING_ELSE;
}
PHP);

        try {
            $hook = self::booleanQueryFormatHook();

            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage('hook 0275: anchor for booleanFormatForQueryString default not found');

            $hook(['out_dir' => $dir]);
        } finally {
            $this->removeDirectory($dir);
        }
    }

    public function testHookCanBeRequiredMoreThanOnce(): void
    {
        $hookA = require dirname(__DIR__, 2) . '/hooks/post_gen/0275_boolean_query_format.php';
        $hookB = require dirname(__DIR__, 2) . '/hooks/post_gen/0275_boolean_query_format.php';

        self::assertInstanceOf(Closure::class, $hookA);
        self::assertInstanceOf(Closure::class, $hookB);
    }

    /**
     * @return Closure(array{out_dir: string}): void
     */
    private static function booleanQueryFormatHook(): Closure
    {
        /** @var Closure(array{out_dir: string}): void $hook */
        $hook = require dirname(__DIR__, 2) . '/hooks/post_gen/0275_boolean_query_format.php';

        return $hook;
    }

    private function createGeneratedFixture(?string $configurationSource = null): string
    {
        $dir = dirname(__DIR__, 2) . '/.boolean-query-hook-' . bin2hex(random_bytes(8));
        self::assertTrue(mkdir($dir . '/src', 0777, true));
        self::assertNotFalse(file_put_contents(
            $dir . '/src/Configuration.php',
            $configurationSource ?? $this->configurationSource(),
        ));
        self::assertNotFalse(file_put_contents($dir . '/src/ObjectSerializer.php', $this->serializerSource()));

        return $dir;
    }

    private function configurationSource(): string
    {
        return <<<'PHP'
<?php

final class Configuration
{
    public const BOOLEAN_FORMAT_INT = 'int';
    public const BOOLEAN_FORMAT_STRING = 'string';

    protected string $booleanFormatForQueryString = self::BOOLEAN_FORMAT_INT;
}
PHP;
    }

    private function serializerSource(): string
    {
        return <<<'PHP'
<?php

final class ObjectSerializer
{
    public static function toQueryValue(): array
    {
        if ('boolean' === $openApiType && is_bool($value)) {
            $value = self::convertBoolToQueryStringFormat($value);
        }

        return [];
    }
}
PHP;
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST,
        );
        foreach ($iterator as $file) {
            if (!$file instanceof SplFileInfo) {
                continue;
            }
            $path = $file->getPathname();
            $file->isDir() ? rmdir($path) : unlink($path);
        }
        rmdir($dir);
    }
}
