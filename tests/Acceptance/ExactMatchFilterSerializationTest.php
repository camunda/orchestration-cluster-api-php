<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Model\AdvancedProcessDefinitionKeyFilter;
use Camunda\Orchestration\Api\Model\AuditLogFilter;
use Camunda\Orchestration\Api\Model\IntegerFilterProperty;
use Camunda\Orchestration\Api\Model\ProcessInstanceFilter;
use Camunda\Orchestration\Api\Model\ProcessInstanceStateEnum;
use Camunda\Orchestration\Api\Model\ResourceFilter;
use Camunda\Orchestration\Api\Model\StringFilterProperty;
use Camunda\Orchestration\Api\Model\VariableFilter;
use Camunda\Orchestration\Api\ObjectSerializer;
use Camunda\Orchestration\Semantic\ResourceKey;
use Camunda\Orchestration\Semantic\ScopeKey;
use PHPUnit\Framework\Attributes\DataProvider;
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

    public function testTopLevelScalarJsonIsDecodedForUnions(): void
    {
        self::assertSame(5, ObjectSerializer::deserialize('5', IntegerFilterProperty::class));
        self::assertSame('hello', ObjectSerializer::deserialize('"hello"', StringFilterProperty::class));
    }

    public function testNestedNumericKeyStringsSurviveJsonStringInput(): void
    {
        $filter = ObjectSerializer::deserialize(
            '{"processDefinitionKey":"2251799813686749"}',
            ProcessInstanceFilter::class,
        );
        self::assertInstanceOf(ProcessInstanceFilter::class, $filter);
        self::assertSame('2251799813686749', $filter->getProcessDefinitionKey());

        $list = ObjectSerializer::deserialize('["2251799813686749"]', StringFilterProperty::class . '[]');
        self::assertSame(['2251799813686749'], $list);
    }

    /**
     * Constructor data for oneOf filter wrappers whose exact-match member is a semantic key
     * union (ResourceKey, ScopeKey) must be hydrated into that key, not left as a raw string.
     *
     * @return iterable<string, array{0: object, 1: string, 2: class-string, 3: string}>
     */
    public static function semanticUnionWrapperProvider(): iterable
    {
        yield 'ResourceFilter.resourceKey' => [
            new ResourceFilter(['resourceKey' => '2251799813685249']),
            'getResourceKey', ResourceKey::class, '"resourceKey":"2251799813685249"',
        ];
        yield 'AuditLogFilter.resourceKey' => [
            new AuditLogFilter(['resourceKey' => '2251799813685249']),
            'getResourceKey', ResourceKey::class, '"resourceKey":"2251799813685249"',
        ];
        yield 'VariableFilter.scopeKey' => [
            new VariableFilter(['scopeKey' => '2251799813685249']),
            'getScopeKey', ScopeKey::class, '"scopeKey":"2251799813685249"',
        ];
    }

    /**
     * @param class-string $keyClass
     */
    #[DataProvider('semanticUnionWrapperProvider')]
    public function testConstructorHydratesSemanticUnionWrapperMembers(
        object $filter,
        string $getter,
        string $keyClass,
        string $expectedJson,
    ): void {
        $callable = [$filter, $getter];
        self::assertIsCallable($callable);
        self::assertInstanceOf($keyClass, $callable());
        self::assertStringContainsString($expectedJson, $this->toJson($filter));
    }

    private function toJson(object $model): string
    {
        return (string) json_encode(ObjectSerializer::sanitizeForSerialization($model), JSON_UNESCAPED_SLASHES);
    }
}
