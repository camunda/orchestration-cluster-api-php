<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Semantic;

use InvalidArgumentException;

/**
 * Semantic identifier that accepts any of: ProcessInstanceKey, ElementInstanceKey.
 */
final class ScopeKey implements SemanticKey
{
    public const NAME = 'ScopeKey';

    public function __construct(private readonly string $value)
    {
        try {
            new ProcessInstanceKey($value);
            return;
        } catch (InvalidArgumentException) {
        }
        try {
            new ElementInstanceKey($value);
            return;
        } catch (InvalidArgumentException) {
        }
        throw new InvalidArgumentException(
            sprintf('%s: "%s" matched no branch (ProcessInstanceKey, ElementInstanceKey)', 'ScopeKey', $value)
        );
    }

    public static function of(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
