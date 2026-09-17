<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Semantic;

use InvalidArgumentException;

/**
 * The start cursor in a search query result set.
 *
 * Distinct, immutable, self-validating semantic identifier. Wraps a string and
 * serializes transparently to/from JSON.
 */
final class StartCursor implements SemanticKey
{
    public const NAME = 'StartCursor';

    public const PATTERN = '/^(?:[A-Za-z0-9+\/]{4})*(?:[A-Za-z0-9+\/]{2}(?:==)?|[A-Za-z0-9+\/]{3}=)?$/u';

    public function __construct(private readonly string $value)
    {
        if (preg_match(self::PATTERN, $value) !== 1) {
            throw new InvalidArgumentException(sprintf('%s must match %s, got "%s"', self::NAME, self::PATTERN, $value));
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
