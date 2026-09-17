<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\GeneratedAsyncOperations;
use Camunda\Orchestration\GeneratedOperations;
use JsonException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use RuntimeException;

final class FacadeCoverageTest extends TestCase
{
    public function testSyncAndAsyncFacadesCoverEveryGeneratedOperation(): void
    {
        $expected = $this->specOperations();

        self::assertSame($expected, $this->traitOperations(GeneratedOperations::class));
        self::assertSame($expected, $this->traitOperations(GeneratedAsyncOperations::class));
    }

    public function testSpecOperationsFailsWhenMetadataCannotBeRead(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot read spec metadata.');

        $this->specOperationsFromMetadata(dirname(__DIR__, 2) . '/external-spec/bundled/does-not-exist.json');
    }

    /**
     * @return list<string>
     */
    private function specOperations(): array
    {
        return $this->specOperationsFromMetadata(dirname(__DIR__, 2) . '/external-spec/bundled/spec-metadata.json');
    }

    /**
     * @return list<string>
     */
    private function specOperationsFromMetadata(string $path): array
    {
        $metadataJson = file_get_contents($path);
        if ($metadataJson === false) {
            throw new RuntimeException('Cannot read spec metadata.');
        }

        try {
            $metadata = json_decode(
                $metadataJson,
                true,
                flags: JSON_THROW_ON_ERROR,
            );
        } catch (JsonException $error) {
            throw new RuntimeException("Cannot parse spec metadata: {$error->getMessage()}", 0, $error);
        }

        if (!is_array($metadata) || !is_array($metadata['operations'] ?? null)) {
            throw new RuntimeException('Spec metadata has no operations list.');
        }

        $operations = [];
        foreach ($metadata['operations'] as $operation) {
            $name = is_array($operation) ? $operation['operationId'] ?? null : null;
            if (!is_string($name) || $name === '') {
                throw new RuntimeException('Spec metadata contains an operation without an operationId.');
            }
            if (isset($operations[$name])) {
                throw new RuntimeException("Spec metadata contains duplicate operationId '$name'.");
            }

            $operations[$name] = true;
        }

        $operations = array_keys($operations);
        sort($operations, SORT_STRING);

        return $operations;
    }

    /**
     * @param class-string $trait
     * @return list<string>
     */
    private function traitOperations(string $trait): array
    {
        $operations = [];
        foreach ((new ReflectionClass($trait))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $name = $method->getName();
            if ($this->isOperation($name)) {
                $operations[] = $name;
            }
        }
        sort($operations, SORT_STRING);

        return $operations;
    }

    private function isOperation(string $name): bool
    {
        return !preg_match('/(WithHttpInfo|Async|Request)$/', $name)
            && !in_array($name, ['__construct', 'getConfig', 'getHostIndex', 'setHostIndex'], true);
    }
}
