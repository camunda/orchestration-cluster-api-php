<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Model\ClusterRestoreRequest;
use Camunda\Orchestration\Api\Model\ProcessInstanceCreationInstructionByKey;
use Camunda\Orchestration\Api\Model\RestoreRequest;
use Camunda\Orchestration\Api\ObjectSerializer;
use Camunda\Orchestration\Semantic\ProcessDefinitionKey;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Regression tests for https://github.com/camunda/orchestration-cluster-api-php/issues/53 —
 * unset optional request fields must be omitted from the serialized body, not serialized
 * as null or as their spec default (`awaitCompletion:false`, `requestTimeout:0`,
 * `processDefinitionVersion:-1`, …). An explicit `null` on a nullable field is still sent.
 */
final class UnsetOptionalFieldOmissionTest extends TestCase
{
    public function testRestoreAsClusterAdminOmitsUnsetFields(): void
    {
        // The exact payload from the issue: only `overrides` set.
        $request = new ClusterRestoreRequest([
            'overrides' => ['tenant-a' => new RestoreRequest(['backupIds' => [55]])],
        ]);

        $this->assertSerializesTo(
            ['overrides' => ['tenant-a' => ['backupIds' => [55]]]],
            $request,
        );
    }

    public function testCreateProcessInstanceOmitsUnsetDefaults(): void
    {
        $request = new ProcessInstanceCreationInstructionByKey([
            'processDefinitionKey' => '2251799813685249',
        ]);

        $this->assertSerializesTo(
            ['processDefinitionKey' => '2251799813685249'],
            $request,
        );
    }

    public function testExplicitlyProvidedDefaultsAreKept(): void
    {
        // Explicitly passing the default values is a caller choice and must serialize.
        $request = new ProcessInstanceCreationInstructionByKey([
            'processDefinitionKey' => '2251799813685249',
            'processDefinitionVersion' => -1,
            'awaitCompletion' => false,
            'requestTimeout' => 0,
        ]);

        $this->assertSerializesTo(
            [
                'processDefinitionKey' => '2251799813685249',
                'processDefinitionVersion' => -1,
                'awaitCompletion' => false,
                'requestTimeout' => 0,
            ],
            $request,
        );
    }

    public function testExplicitNullOnNullableFieldStillSerializes(): void
    {
        $request = new ClusterRestoreRequest([
            'from' => null,
            'overrides' => ['tenant-a' => new RestoreRequest(['backupIds' => [55]])],
        ]);

        $this->assertSerializesTo(
            ['from' => null, 'overrides' => ['tenant-a' => ['backupIds' => [55]]]],
            $request,
        );
    }

    public function testSetterProvidedFieldsSerialize(): void
    {
        $request = (new ProcessInstanceCreationInstructionByKey())
            ->setProcessDefinitionKey(new ProcessDefinitionKey('2251799813685249'))
            ->setAwaitCompletion(true)
            ->setRequestTimeout(5_000);

        $this->assertSerializesTo(
            [
                'processDefinitionKey' => '2251799813685249',
                'awaitCompletion' => true,
                'requestTimeout' => 5000,
            ],
            $request,
        );
    }

    public function testSetterNullOnNullableFieldUnsetsIt(): void
    {
        $request = new ClusterRestoreRequest([
            'overrides' => ['tenant-a' => new RestoreRequest(['backupIds' => [55]])],
        ]);
        $request->setFrom(new DateTime('2024-01-01T00:00:00Z'));
        self::assertStringContainsString('"from":"2024-01-01T00:00:00', $this->toJson($request));

        // Assigning null through a setter mirrors ArrayAccess semantics: the field
        // becomes unset again and is omitted (explicit constructor null stays explicit).
        $request->setFrom(null);
        $this->assertSerializesTo(
            ['overrides' => ['tenant-a' => ['backupIds' => [55]]]],
            $request,
        );
    }

    public function testArrayAccessTracksSetAndUnset(): void
    {
        $request = new ProcessInstanceCreationInstructionByKey();
        $request['processDefinitionKey'] = new ProcessDefinitionKey('2251799813685249');
        $request['requestTimeout'] = 5_000;

        $this->assertSerializesTo(
            [
                'processDefinitionKey' => '2251799813685249',
                'requestTimeout' => 5000,
            ],
            $request,
        );

        unset($request['requestTimeout']);
        $json = $this->toJson($request);
        self::assertStringNotContainsString('requestTimeout', $json);
        self::assertStringContainsString('"processDefinitionKey":"2251799813685249"', $json);

        $request['awaitCompletion'] = null; // null via ArrayAccess means unset
        self::assertStringNotContainsString('awaitCompletion', $this->toJson($request));
    }

    public function testNestedModelsOmitTheirOwnUnsetFields(): void
    {
        // The nested RestoreRequest must not leak its own unset optionals either.
        $request = new ClusterRestoreRequest([
            'backupIds' => [55],
            'overrides' => ['tenant-a' => new RestoreRequest(['backupIds' => [56]])],
        ]);

        $this->assertSerializesTo(
            ['backupIds' => [55], 'overrides' => ['tenant-a' => ['backupIds' => [56]]]],
            $request,
        );
    }

    private function toJson(object $model): string
    {
        $json = json_encode(ObjectSerializer::sanitizeForSerialization($model));
        self::assertIsString($json);
        return $json;
    }

    /**
     * Assert the model serializes to exactly the given body. JSON object key order is
     * not part of the API contract (it tracks the generated property order, which the
     * upstream spec may reshuffle — e.g. when shared fields move onto an allOf parent),
     * so compare the decoded structure order-independently while still enforcing that
     * no field is added or omitted.
     *
     * @param array<string, mixed> $expected
     */
    private function assertSerializesTo(array $expected, object $model): void
    {
        $decoded = json_decode($this->toJson($model), true);
        self::assertIsArray($decoded);
        self::assertEqualsCanonicalizing(array_keys($expected), array_keys($decoded));
        self::assertEquals($expected, $decoded);
    }
}
