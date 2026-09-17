<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Semantic;

use InvalidArgumentException;

/**
 * System-generated identifier for a decision evaluation instance. It is composed of the parent decision evaluation key and the 1-based index of the evaluated decision within that evaluation, joined by a hyphen (format: `<decisionEvaluationKey>-<index>`).
 *
 * Distinct, immutable, self-validating semantic identifier. Wraps a string and
 * serializes transparently to/from JSON.
 */
final class DecisionEvaluationInstanceKey implements SemanticKey
{
    public const NAME = 'DecisionEvaluationInstanceKey';

    public const PATTERN = '/^[0-9]+-[0-9]+$/u';

    public function __construct(private readonly string $value)
    {
        if (preg_match(self::PATTERN, $value) !== 1) {
            throw new InvalidArgumentException(sprintf('%s must match %s, got "%s"', self::NAME, self::PATTERN, $value));
        }
        if (mb_strlen($value) < 3) {
            throw new InvalidArgumentException(sprintf('%s must be at least 3 character(s)', self::NAME));
        }
        if (mb_strlen($value) > 30) {
            throw new InvalidArgumentException(sprintf('%s must be at most 30 character(s)', self::NAME));
        }
    }

    /** Construct from a raw string (alias for the constructor). */
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
