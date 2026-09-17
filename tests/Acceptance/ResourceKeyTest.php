<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Api\ResourceApi;
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
