<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Model\AdvancedProcessDefinitionKeyFilter;
use Camunda\Orchestration\Api\Model\ProcessInstanceFilter;
use Camunda\Orchestration\Api\Model\ProcessInstanceStateEnum;
use Camunda\Orchestration\Api\ObjectSerializer;
use PHPUnit\Framework\TestCase;

/**
 * Regression tests for #145 / #147 — key and enum filter properties must not be silently
 * dropped from search / batch-operation filters. Their `*ExactMatch` schemas used to be
 * generated as empty model classes, so the value had nowhere to live.
 */
final class ExactMatchFilterSerializationTest extends TestCase
{
    public function testExactMatchKeyFilterIsSerialized(): void
    {
        $filter = new ProcessInstanceFilter();
        $filter->setProcessDefinitionKey('2251799813686749');

        self::assertStringContainsString('"processDefinitionKey":"2251799813686749"', $this->toJson($filter));
    }

    public function testExactMatchEnumFilterIsSerialized(): void
    {
        $filter = new ProcessInstanceFilter();
        $filter->setState(ProcessInstanceStateEnum::ACTIVE);

        self::assertStringContainsString('"state":"ACTIVE"', $this->toJson($filter));
    }

    public function testAdvancedKeyFilterIsSerialized(): void
    {
        $filter = new ProcessInstanceFilter();
        $filter->setProcessDefinitionKey(
            (new AdvancedProcessDefinitionKeyFilter())
                ->setEq('2251799813686749')
                ->setIn(['1', '2']),
        );

        self::assertStringContainsString(
            '"processDefinitionKey":{"$eq":"2251799813686749","$in":["1","2"]}',
            $this->toJson($filter),
        );
    }

    public function testFilterPropertiesRoundTripFromJson(): void
    {
        $exact = ObjectSerializer::deserialize(
            json_decode('{"processDefinitionKey":"2251799813686749","state":"ACTIVE"}'),
            ProcessInstanceFilter::class,
        );
        self::assertInstanceOf(ProcessInstanceFilter::class, $exact);
        self::assertSame('2251799813686749', $exact->getProcessDefinitionKey());
        self::assertSame(ProcessInstanceStateEnum::ACTIVE, $exact->getState());

        $advanced = ObjectSerializer::deserialize(
            json_decode('{"processDefinitionKey":{"$eq":"2251799813686749"}}'),
            ProcessInstanceFilter::class,
        );
        self::assertInstanceOf(ProcessInstanceFilter::class, $advanced);
        $key = $advanced->getProcessDefinitionKey();
        self::assertInstanceOf(AdvancedProcessDefinitionKeyFilter::class, $key);
        self::assertSame('2251799813686749', $key->getEq());
    }

    private function toJson(object $model): string
    {
        return (string) json_encode(ObjectSerializer::sanitizeForSerialization($model), JSON_UNESCAPED_SLASHES);
    }
}
