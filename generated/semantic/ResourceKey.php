<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Semantic;

use InvalidArgumentException;

/**
 * Semantic identifier that accepts any of: ProcessDefinitionKey, DecisionRequirementsKey, FormKey, DecisionDefinitionKey.
 */
final class ResourceKey implements SemanticKey
{
    public const NAME = 'ResourceKey';

    public function __construct(private readonly string $value)
    {
        try {
            new ProcessDefinitionKey($value);
            return;
        } catch (InvalidArgumentException) {
        }
        try {
            new DecisionRequirementsKey($value);
            return;
        } catch (InvalidArgumentException) {
        }
        try {
            new FormKey($value);
            return;
        } catch (InvalidArgumentException) {
        }
        try {
            new DecisionDefinitionKey($value);
            return;
        } catch (InvalidArgumentException) {
        }
        throw new InvalidArgumentException(
            sprintf('%s: "%s" matched no branch (ProcessDefinitionKey, DecisionRequirementsKey, FormKey, DecisionDefinitionKey)', 'ResourceKey', $value)
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
