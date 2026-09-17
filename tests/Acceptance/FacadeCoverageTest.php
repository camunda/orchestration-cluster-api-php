<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\GeneratedAsyncOperations;
use Camunda\Orchestration\GeneratedOperations;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

final class FacadeCoverageTest extends TestCase
{
    public function testSyncAndAsyncFacadesCoverEveryGeneratedOperation(): void
    {
        $expected = $this->generatedApiOperations();

        self::assertSame($expected, $this->traitOperations(GeneratedOperations::class));
        self::assertSame($expected, $this->traitOperations(GeneratedAsyncOperations::class));
    }

    /**
     * @return list<string>
     */
    private function generatedApiOperations(): array
    {
        $operations = [];
        $apiDir = dirname(__DIR__, 2) . '/generated/src/Api';

        foreach (glob($apiDir . '/*Api.php') ?: [] as $file) {
            /** @var class-string $class */
            $class = 'Camunda\\Orchestration\\Api\\Api\\' . basename($file, '.php');
            foreach ((new ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $name = $method->getName();
                if ($this->isOperation($name)) {
                    $operations[$name] = true;
                }
            }
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
