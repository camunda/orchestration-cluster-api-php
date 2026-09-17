<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Semantic;

use InvalidArgumentException;

/**
 * Factory for the ResourceKey key set (ProcessDefinitionKey, DecisionRequirementsKey, FormKey, DecisionDefinitionKey).
 *
 * PHP has no union types for classes, so this lifts a raw string into the first
 * branch whose constraints accept it.
 */
final class ResourceKey
{
    public static function of(string $value): SemanticKey
    {
        try {
            return new ProcessDefinitionKey($value);
        } catch (InvalidArgumentException) {
        }
        try {
            return new DecisionRequirementsKey($value);
        } catch (InvalidArgumentException) {
        }
        try {
            return new FormKey($value);
        } catch (InvalidArgumentException) {
        }
        try {
            return new DecisionDefinitionKey($value);
        } catch (InvalidArgumentException) {
        }
        throw new InvalidArgumentException(
            sprintf('%s: "%s" matched no branch (ProcessDefinitionKey, DecisionRequirementsKey, FormKey, DecisionDefinitionKey)', 'ResourceKey', $value)
        );
    }
}
