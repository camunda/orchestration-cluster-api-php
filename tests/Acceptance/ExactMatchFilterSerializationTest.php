<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Model\AdvancedProcessDefinitionKeyFilter;
use Camunda\Orchestration\Api\Model\ProcessInstanceFilter;
use Camunda\Orchestration\Api\Model\ProcessInstanceSearchQuery;
use Camunda\Orchestration\Api\ObjectSerializer;
use PHPUnit\Framework\TestCase;

/**
 * Regression tests for https://github.com/camunda/orchestration-cluster-api-php/issues/145 —
 * key and enum filter properties (whose exact-match variant is a scalar `*ExactMatch`
 * string schema) must survive serialization in both the exact-match and advanced forms.
 */
final class ExactMatchFilterSerializationTest extends TestCase
{
    public function testExactMatchKeyFilterIsSerialized(): void
    {
        $query = new ProcessInstanceSearchQuery([
            'filter' => new ProcessInstanceFilter([
                'processDefinitionId' => 'test',
                'processDefinitionKey' => '2251799813686749',
            ]),
        ]);

        self::assertSame(
            ['filter' => ['processDefinitionId' => 'test', 'processDefinitionKey' => '2251799813686749']],
            $this->roundTrip($query),
        );
    }

    public function testExactMatchEnumFilterIsSerialized(): void
    {
        $filter = new ProcessInstanceFilter(['state' => 'ACTIVE']);

        self::assertSame(['state' => 'ACTIVE'], $this->roundTrip($filter));
    }

    public function testAdvancedKeyFilterIsSerialized(): void
    {
        $filter = new ProcessInstanceFilter([
            'processDefinitionKey' => new AdvancedProcessDefinitionKeyFilter([
                'eq' => '2251799813686749',
                'in' => ['2251799813686749', '2251799813686750'],
            ]),
        ]);

        self::assertSame(
            ['processDefinitionKey' => ['$eq' => '2251799813686749', '$in' => ['2251799813686749', '2251799813686750']]],
            $this->roundTrip($filter),
        );
    }

    public function testFilterDeserializesBothForms(): void
    {
        $exact = ObjectSerializer::deserialize(
            ['processDefinitionKey' => '2251799813686749', 'state' => 'ACTIVE'],
            ProcessInstanceFilter::class,
        );
        self::assertInstanceOf(ProcessInstanceFilter::class, $exact);
        self::assertSame('2251799813686749', $exact->getProcessDefinitionKey());
        self::assertSame('ACTIVE', $exact->getState());

        $advanced = ObjectSerializer::deserialize(
            ['processDefinitionKey' => ['$eq' => '2251799813686749']],
            ProcessInstanceFilter::class,
        );
        self::assertInstanceOf(ProcessInstanceFilter::class, $advanced);
        $key = $advanced->getProcessDefinitionKey();
        self::assertInstanceOf(AdvancedProcessDefinitionKeyFilter::class, $key);
        self::assertSame('2251799813686749', $key->getEq());
    }

    /**
     * @return mixed
     */
    private function roundTrip(object $model): mixed
    {
        $json = json_encode(ObjectSerializer::sanitizeForSerialization($model));
        self::assertIsString($json);

        return json_decode($json, true);
    }
}
