<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Closure;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Fixture tests for hooks/pre_gen/0100_exact_match_scalars.php (#147).
 */
final class ExactMatchScalarsPreGenHookTest extends TestCase
{
    public function testKeyAndEnumExactMatchSchemasCollapseToAliases(): void
    {
        $spec = $this->transform(<<<'JSON'
            {"components":{"schemas":{
              "ProcessDefinitionKeyExactMatch":{"type":"string","allOf":[{"$ref":"#/components/schemas/ProcessDefinitionKey"}]},
              "ProcessInstanceStateExactMatch":{"type":"string","allOf":[{"$ref":"#/components/schemas/ProcessInstanceStateEnum"}],"description":"x"}
            }}}
            JSON);

        $expected = json_decode(<<<'JSON'
            {"components":{"schemas":{
              "ProcessDefinitionKeyExactMatch":{"$ref":"#/components/schemas/ProcessDefinitionKey"},
              "ProcessInstanceStateExactMatch":{"$ref":"#/components/schemas/ProcessInstanceStateEnum"}
            }}}
            JSON, false);
        self::assertEquals($expected, $spec);
    }

    public function testUnsupportedSchemasAreLeftUnchanged(): void
    {
        $json = <<<'JSON'
            {"components":{"schemas":{
              "NotAMatch":{"type":"string","allOf":[{"$ref":"#/components/schemas/A"}]},
              "ObjectExactMatch":{"type":"object","allOf":[{"$ref":"#/components/schemas/A"}]},
              "PropsExactMatch":{"type":"string","properties":{"a":{"type":"string"}},"allOf":[{"$ref":"#/components/schemas/A"}]},
              "MultiExactMatch":{"type":"string","allOf":[{"$ref":"#/components/schemas/A"},{"$ref":"#/components/schemas/B"}]},
              "InlineExactMatch":{"type":"string","allOf":[{"type":"string"}]},
              "NoAllOfExactMatch":{"type":"string"}
            }}}
            JSON;

        self::assertEquals(json_decode($json, false), $this->transform($json));
    }

    public function testSpecWithoutSchemasIsReturnedUnchanged(): void
    {
        $json = '{"openapi":"3.0.3","paths":{}}';
        self::assertEquals(json_decode($json, false), $this->transform($json));
    }

    public function testEmptyJsonObjectsSurviveTheTransform(): void
    {
        $spec = $this->transform(<<<'JSON'
            {"paths":{},"components":{"schemas":{
              "Empty":{"type":"object","properties":{}},
              "KeyExactMatch":{"type":"string","allOf":[{"$ref":"#/components/schemas/Key"}]}
            }}}
            JSON);

        $encoded = (string) json_encode($spec, JSON_UNESCAPED_SLASHES);
        self::assertStringContainsString('"paths":{}', $encoded);
        self::assertStringContainsString('"Empty":{"type":"object","properties":{}}', $encoded);
    }

    private function transform(string $json): stdClass
    {
        $spec = json_decode($json, false, 512, JSON_THROW_ON_ERROR);
        self::assertInstanceOf(stdClass::class, $spec);

        /** @var Closure(stdClass): stdClass $hook */
        $hook = require dirname(__DIR__, 2) . '/hooks/pre_gen/0100_exact_match_scalars.php';

        ob_start();
        try {
            return $hook($spec);
        } finally {
            ob_end_clean();
        }
    }
}
