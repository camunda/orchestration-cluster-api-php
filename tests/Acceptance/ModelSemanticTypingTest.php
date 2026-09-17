<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Model\ProcessInstanceCreationInstructionById;
use Camunda\Orchestration\Api\ObjectSerializer;
use Camunda\Orchestration\Semantic\ProcessDefinitionId;
use PHPUnit\Framework\TestCase;

final class ModelSemanticTypingTest extends TestCase
{
    public function testArrayConstructionLiftsStringIntoValueObject(): void
    {
        $instruction = new ProcessInstanceCreationInstructionById([
            'processDefinitionId' => 'order-process',
            'variables' => ['orderId' => 'ORD-42'],
        ]);

        self::assertInstanceOf(ProcessDefinitionId::class, $instruction->getProcessDefinitionId());
        self::assertSame('order-process', (string) $instruction->getProcessDefinitionId());
    }

    public function testSetterAcceptsValueObject(): void
    {
        $instruction = (new ProcessInstanceCreationInstructionById())
            ->setProcessDefinitionId(ProcessDefinitionId::of('order-process'));

        self::assertSame('order-process', (string) $instruction->getProcessDefinitionId());
    }

    public function testSerializationEmitsBareString(): void
    {
        $instruction = new ProcessInstanceCreationInstructionById([
            'processDefinitionId' => 'order-process',
        ]);

        /** @var object $sanitized */
        $sanitized = ObjectSerializer::sanitizeForSerialization($instruction);
        $json = json_encode($sanitized);

        self::assertIsString($json);
        self::assertStringContainsString('"processDefinitionId":"order-process"', $json);
    }

    public function testDeserializationProducesValueObject(): void
    {
        $payload = (object) ['processDefinitionId' => 'order-process'];

        /** @var ProcessInstanceCreationInstructionById $model */
        $model = ObjectSerializer::deserialize($payload, ProcessInstanceCreationInstructionById::class);

        self::assertInstanceOf(ProcessDefinitionId::class, $model->getProcessDefinitionId());
        self::assertSame('order-process', (string) $model->getProcessDefinitionId());
    }
}
