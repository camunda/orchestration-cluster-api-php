<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Model\CreateClusterVariableRequest;
use Camunda\Orchestration\Api\Model\MessagePublicationRequest;
use Camunda\Orchestration\Api\Model\ProcessInstanceCreationInstructionByKey;
use Camunda\Orchestration\Api\Model\SignalBroadcastRequest;
use Camunda\Orchestration\Api\ObjectSerializer;
use PHPUnit\Framework\TestCase;

/**
 * Regression tests for https://github.com/camunda/orchestration-cluster-api-php/issues/52 —
 * object-typed request fields (`variables`, `metadata`, …) must serialize an empty map as
 * `{}`, never as the JSON array `[]`.
 */
final class EmptyObjectSerializationTest extends TestCase
{
    public function testEmptyVariablesSerializeAsObject(): void
    {
        $request = new MessagePublicationRequest([
            'name' => 'order-received',
            'variables' => [],
        ]);

        $json = $this->toJson($request);

        self::assertStringContainsString('"variables":{}', $json);
        self::assertStringNotContainsString('"variables":[]', $json);
    }

    public function testEmptyVariablesSerializeAsObjectAcrossOperations(): void
    {
        $signal = new SignalBroadcastRequest([
            'signalName' => 'order-cancelled',
            'variables' => [],
        ]);
        self::assertStringContainsString('"variables":{}', $this->toJson($signal));

        $process = new ProcessInstanceCreationInstructionByKey([
            'processDefinitionKey' => '2251799813685249',
            'variables' => [],
        ]);
        self::assertStringContainsString('"variables":{}', $this->toJson($process));
    }

    public function testEmptyMetadataSerializesAsObject(): void
    {
        $request = new CreateClusterVariableRequest([
            'name' => 'cluster-var',
            'value' => (object) ['nested' => true],
            'metadata' => [],
        ]);

        $json = $this->toJson($request);

        self::assertStringContainsString('"metadata":{}', $json);
        self::assertStringNotContainsString('"metadata":[]', $json);
    }

    public function testNonEmptyMapStillSerializesAsObject(): void
    {
        $request = new MessagePublicationRequest([
            'name' => 'order-received',
            'variables' => ['orderId' => 'ORD-42'],
        ]);

        self::assertStringContainsString('"variables":{"orderId":"ORD-42"}', $this->toJson($request));
    }

    public function testEmptyListTypesStillSerializeAsArray(): void
    {
        // List types are JSON arrays — `[]` is the correct empty form for them.
        self::assertSame('[]', json_encode(ObjectSerializer::sanitizeForSerialization([], 'string[]')));
        self::assertSame('[]', json_encode(ObjectSerializer::sanitizeForSerialization([], 'array')));
        self::assertSame('[]', json_encode(ObjectSerializer::sanitizeForSerialization([], null)));
        self::assertSame(
            '[]',
            json_encode(ObjectSerializer::sanitizeForSerialization([], '\Camunda\Orchestration\Api\Model\SomeModel[]'))
        );
    }

    public function testEmptyMapWithObjectTypeSerializesAsObject(): void
    {
        self::assertSame('{}', json_encode(ObjectSerializer::sanitizeForSerialization([], 'object')));
        self::assertSame('{}', json_encode(ObjectSerializer::sanitizeForSerialization([], 'array<string,mixed>')));
    }

    private function toJson(object $model): string
    {
        $json = json_encode(ObjectSerializer::sanitizeForSerialization($model));
        self::assertIsString($json);
        return $json;
    }
}
