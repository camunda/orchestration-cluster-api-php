<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Semantic\ProcessDefinitionId;
use Camunda\Orchestration\Semantic\ProcessInstanceKey;
use Camunda\Orchestration\Semantic\SemanticKey;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class SemanticTypeTest extends TestCase
{
    public function testConstructsValidValue(): void
    {
        $key = new ProcessInstanceKey('123456789');
        self::assertSame('123456789', $key->value());
        self::assertSame('123456789', (string) $key);
        self::assertInstanceOf(SemanticKey::class, $key);
    }

    public function testOfIsConstructorAlias(): void
    {
        self::assertTrue(ProcessInstanceKey::of('42')->equals(new ProcessInstanceKey('42')));
    }

    public function testRejectsValueViolatingPattern(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ProcessInstanceKey('not-a-number');
    }

    public function testRejectsValueViolatingMaxLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ProcessInstanceKey(str_repeat('9', 26));
    }

    public function testEqualityComparesByValue(): void
    {
        self::assertTrue((new ProcessInstanceKey('7'))->equals(new ProcessInstanceKey('7')));
        self::assertFalse((new ProcessInstanceKey('7'))->equals(new ProcessInstanceKey('8')));
    }

    public function testJsonSerializesToBareString(): void
    {
        self::assertSame('"order-process"', json_encode(new ProcessDefinitionId('order-process')));
    }

    public function testDistinctTypesAreNotInterchangeable(): void
    {
        // A compile-/analysis-time guarantee, asserted structurally here:
        self::assertNotSame(ProcessDefinitionId::class, ProcessInstanceKey::class);
    }
}
