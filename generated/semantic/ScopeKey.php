<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Semantic;

use InvalidArgumentException;

/**
 * Factory for the ScopeKey key set (ProcessInstanceKey, ElementInstanceKey).
 *
 * PHP has no union types for classes, so this lifts a raw string into the first
 * branch whose constraints accept it.
 */
final class ScopeKey
{
    public static function of(string $value): SemanticKey
    {
        try {
            return new ProcessInstanceKey($value);
        } catch (InvalidArgumentException) {
        }
        try {
            return new ElementInstanceKey($value);
        } catch (InvalidArgumentException) {
        }
        throw new InvalidArgumentException(
            sprintf('%s: "%s" matched no branch (ProcessInstanceKey, ElementInstanceKey)', 'ScopeKey', $value)
        );
    }
}
