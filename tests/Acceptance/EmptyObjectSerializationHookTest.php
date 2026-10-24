<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Closure;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Regression tests for the post-generation hook 0225 review finding: the idempotency
 * guard must recognise the hook's own output, so rerunning the hooks against the
 * already-patched, checked-in serializer (`php generate.php --skip-openapi-generator`)
 * skips cleanly instead of failing on the rewritten `is_array` anchor.
 */
final class EmptyObjectSerializationHookTest extends TestCase
{
    private static ?Closure $hook = null;

    public function testHookPatchesUnpatchedSerializer(): void
    {
        $dir = $this->createGeneratedFixture($this->unpatchedSerializerSource());

        try {
            $hook = self::emptyObjectSerializationHook();
            $hook(['out_dir' => $dir]);

            $patched = file_get_contents($dir . '/src/ObjectSerializer.php');
            self::assertNotFalse($patched);
            self::assertStringContainsString('self::isObjectShapedType($type)', $patched);
            self::assertStringContainsString('private static function isObjectShapedType(?string $type): bool', $patched);
            self::assertStringContainsString('return new \stdClass();', $patched);
        } finally {
            $this->removeDirectory($dir);
        }
    }

    public function testHookRerunOnPatchedSerializerIsIdempotent(): void
    {
        $dir = $this->createGeneratedFixture($this->unpatchedSerializerSource());

        try {
            $hook = self::emptyObjectSerializationHook();
            $hook(['out_dir' => $dir]);

            $once = file_get_contents($dir . '/src/ObjectSerializer.php');
            self::assertNotFalse($once);

            // The rerun path (`generate.php --skip-openapi-generator`) must not throw
            // on the already-patched output, and must not patch it a second time.
            $hook(['out_dir' => $dir]);

            $twice = file_get_contents($dir . '/src/ObjectSerializer.php');
            self::assertSame($once, $twice);
        } finally {
            $this->removeDirectory($dir);
        }
    }

    public function testHookFailsWhenSerializerShapeChanges(): void
    {
        $dir = $this->createGeneratedFixture($this->divergentSerializerSource());

        try {
            $hook = self::emptyObjectSerializationHook();

            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage("hook 0225: anchor for 'is_array branch' not found");

            $hook(['out_dir' => $dir]);
        } finally {
            $this->removeDirectory($dir);
        }
    }

    public function testHookCanBeRequiredMoreThanOnce(): void
    {
        // Requiring the file again returns the same closure (the file declares a global
        // helper function, so it can only be loaded once per PHP process).
        self::assertSame(self::emptyObjectSerializationHook(), self::emptyObjectSerializationHook());
    }

    /**
     * @return Closure(array{out_dir: string}): void
     */
    private static function emptyObjectSerializationHook(): Closure
    {
        // The hook file declares a global helper function, so it can only be required
        // once per PHP process — cache the closure across tests.
        if (self::$hook !== null) {
            return self::$hook;
        }

        /** @var Closure(array{out_dir: string}): void $hook */
        $hook = require dirname(__DIR__, 2) . '/hooks/post_gen/0225_empty_object_serialization.php';

        return self::$hook = $hook;
    }

    private function createGeneratedFixture(string $serializerSource): string
    {
        $dir = dirname(__DIR__, 2) . '/.empty-object-hook-' . bin2hex(random_bytes(8));
        self::assertTrue(mkdir($dir . '/src', 0777, true));
        self::assertNotFalse(file_put_contents($dir . '/src/ObjectSerializer.php', $serializerSource));

        return $dir;
    }

    private function unpatchedSerializerSource(): string
    {
        return <<<'PHP'
<?php

final class ObjectSerializer
{
    public static function sanitizeForSerialization(mixed $data, ?string $type = null): mixed
    {
        if (is_array($data)) {
            foreach ($data as $property => $value) {
                $data[$property] = self::sanitizeForSerialization($value);
            }
            return $data;
        }

        return $data;
    }

    /**
     * Sanitize filename by removing path.
     */
    public static function sanitizeFilename(string $filename): string
    {
        return $filename;
    }
}
PHP;
    }

    private function divergentSerializerSource(): string
    {
        return <<<'PHP'
<?php

final class ObjectSerializer
{
    public static function sanitizeForSerialization(mixed $data, ?string $type = null): mixed
    {
        if (is_iterable($data)) {
            return $data;
        }

        return $data;
    }

    /**
     * Sanitize filename by removing path.
     */
    public static function sanitizeFilename(string $filename): string
    {
        return $filename;
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
