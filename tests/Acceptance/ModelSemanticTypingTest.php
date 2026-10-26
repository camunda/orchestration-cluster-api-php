<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Model\ElementInstanceResult;
use Camunda\Orchestration\Api\Model\ProcessInstanceCreationInstructionById;
use Camunda\Orchestration\Api\Model\ProcessInstanceSequenceFlowResult;
use Camunda\Orchestration\Api\Model\VariableResult;
use Camunda\Orchestration\Api\ObjectSerializer;
use Camunda\Orchestration\Semantic\ProcessDefinitionId;
use Camunda\Orchestration\Semantic\ProcessInstanceKey;
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

    /**
     * A retyped setter must preserve the generator's nullable-state tracking so that an
     * explicit null survives serialization. ObjectSerializer only emits a null value when
     * `isNullableSetToNull()` is true; a rewrite that dropped the tracking would silently
     * omit `set<Prop>(null)` from the wire. Covers an inherited nullable identifier.
     */
    public function testExplicitNullOnRetypedSetterIsSerialized(): void
    {
        $result = new VariableResult([
            'name' => 'orderId',
            'tenantId' => '<default>',
            'variableKey' => '1',
            'scopeKey' => '2',
            'processInstanceKey' => '3',
            'value' => 'ORD-42',
        ]);
        $result->setRootProcessInstanceKey(null);

        self::assertTrue($result->isNullableSetToNull('rootProcessInstanceKey'));

        /** @var object $sanitized */
        $sanitized = ObjectSerializer::sanitizeForSerialization($result);
        $json = json_encode($sanitized);

        self::assertIsString($json);
        self::assertStringContainsString('"rootProcessInstanceKey":null', $json);
    }

    /**
     * The same class of bug affects every nullable retyped identifier, including the
     * newly-nullable definition fields on the legacy-result models. Clearing a value
     * back to null after it was set must re-flag it as an explicit null, and setting a
     * concrete value must clear the flag.
     */
    public function testRetypedNullableSetterTogglesExplicitNullState(): void
    {
        $result = new ElementInstanceResult();

        $result->setProcessDefinitionKey(null);
        self::assertTrue($result->isNullableSetToNull('processDefinitionKey'));

        $result->setRootProcessInstanceKey(ProcessInstanceKey::of('42'));
        self::assertFalse($result->isNullableSetToNull('rootProcessInstanceKey'));

        $result->setRootProcessInstanceKey(null);
        self::assertTrue($result->isNullableSetToNull('rootProcessInstanceKey'));
    }

    /**
     * Regression: hook 0300's blunt `(string)` cast on validation operands also rewrote the
     * generator's `is_null($this->container['prop'])` guard to `is_null((string) ...)`. Because
     * `(string) null === ''`, that guard is always false, so the length/pattern check ran on
     * omitted optional identifiers and wrongly rejected them (e.g. "character length must be
     * bigger than or equal to 1"). An instruction carrying only its required identifier, with
     * every optional retyped identifier omitted, must validate cleanly.
     */
    public function testOmittedOptionalRetypedIdentifiersPassValidation(): void
    {
        $instruction = new ProcessInstanceCreationInstructionById([
            'processDefinitionId' => 'order-process',
        ]);

        self::assertSame([], $instruction->listInvalidProperties());
        self::assertTrue($instruction->valid());
    }

    /**
     * The same class of bug affected the newly-nullable retyped definition fields on the
     * legacy-result models. Explicitly nulling such a field must not resurrect the spurious
     * minimum-length validation error that the broken `is_null((string) ...)` guard let through.
     */
    public function testExplicitNullOnNewlyNullableDefinitionFieldIsNotRejected(): void
    {
        $result = new ProcessInstanceSequenceFlowResult();
        $result->setProcessDefinitionId(null);

        self::assertTrue($result->isNullableSetToNull('processDefinitionId'));
        self::assertNotContains(
            "invalid value for 'processDefinitionId', the character length must be bigger than or equal to 1.",
            $result->listInvalidProperties(),
        );
    }
}
