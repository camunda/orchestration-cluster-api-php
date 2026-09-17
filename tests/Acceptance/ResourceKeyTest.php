<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Api\ResourceApi;
use Camunda\Orchestration\Api\Model\AdvancedResourceKeyFilter;
use Camunda\Orchestration\Api\Model\AdvancedScopeKeyFilter;
use Camunda\Orchestration\Api\Model\DeleteResourceResponse;
use Camunda\Orchestration\Api\Model\ExpressionEvaluationRequest;
use Camunda\Orchestration\Api\Model\ResourceResult;
use Camunda\Orchestration\Semantic\ResourceKey;
use Camunda\Orchestration\Semantic\ScopeKey;
use Camunda\Orchestration\Semantic\SemanticKey;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ResourceKeyTest extends TestCase
{
    public function testResourceApiPathUsesSemanticResourceKeyValue(): void
    {
        $request = (new ResourceApi())->getResourceRequest(new ResourceKey('2251799813685676'));

        self::assertSame('/v2/resources/2251799813685676', $request->getUri()->getPath());
    }

    public function testResourceModelsLiftRawStringsIntoSemanticUnionKeys(): void
    {
        $result = new ResourceResult([
            'resourceKey' => '2251799813685676',
            'resourceName' => 'order-process.bpmn',
            'resourceChecksum' => 'sha-256',
            'resourceType' => \Camunda\Orchestration\Api\Model\ResourceTypeEnum::PROCESS_DEFINITION,
        ]);

        self::assertInstanceOf(ResourceKey::class, $result->getResourceKey());
        self::assertInstanceOf(SemanticKey::class, $result->getResourceKey());
        self::assertSame('2251799813685676', $result->getResourceKey()->value());
    }

    public function testSemanticUnionModelsLiftRawScalarStringsIntoSemanticKeys(): void
    {
        $expressionRequest = new ExpressionEvaluationRequest([
            'expression' => '= x + y',
            'scopeKey' => '2251799813685249',
        ]);
        $scopeFilter = new AdvancedScopeKeyFilter([
            'eq' => '2251799813685249',
        ]);
        $deleteResponse = new DeleteResourceResponse([
            'resourceKey' => '2251799813685676',
        ]);

        self::assertInstanceOf(ScopeKey::class, $expressionRequest->getScopeKey());
        self::assertSame('2251799813685249', $expressionRequest->getScopeKey()->value());
        self::assertInstanceOf(ScopeKey::class, $scopeFilter->getEq());
        self::assertSame('2251799813685249', $scopeFilter->getEq()->value());
        self::assertInstanceOf(ResourceKey::class, $deleteResponse->getResourceKey());
        self::assertSame('2251799813685676', $deleteResponse->getResourceKey()->value());
    }

    public function testSemanticUnionModelsLiftRawStringArraysIntoSemanticKeys(): void
    {
        $scopeFilter = new AdvancedScopeKeyFilter([
            'in' => ['2251799813685249', '2251799813685250'],
        ]);
        $resourceFilter = new AdvancedResourceKeyFilter([
            'in' => ['2251799813685676', '2251799813685677'],
        ]);

        $scopeKeys = $scopeFilter->getIn();
        self::assertNotNull($scopeKeys);
        self::assertContainsOnlyInstancesOf(ScopeKey::class, $scopeKeys);
        self::assertSame(
            ['2251799813685249', '2251799813685250'],
            array_map(static fn (ScopeKey $key): string => $key->value(), $scopeKeys)
        );
        $resourceKeys = $resourceFilter->getIn();
        self::assertNotNull($resourceKeys);
        self::assertContainsOnlyInstancesOf(ResourceKey::class, $resourceKeys);
        self::assertSame(
            ['2251799813685676', '2251799813685677'],
            array_map(static fn (ResourceKey $key): string => $key->value(), $resourceKeys)
        );
    }

    public function testResourceKeyRejectsMalformedValue(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ResourceKey('not-a-long-key');
    }

    public function testScopeKeyRejectsMalformedValue(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ScopeKey('not-a-long-key');
    }
}
