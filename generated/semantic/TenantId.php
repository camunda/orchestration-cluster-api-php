<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Semantic;

use InvalidArgumentException;

/**
 * The unique identifier of the tenant.
 *
 * Distinct, immutable, self-validating semantic identifier. Wraps a string and
 * serializes transparently to/from JSON.
 */
final class TenantId implements SemanticKey
{
    public const NAME = 'TenantId';

    public const PATTERN = '/^(<default>|[\w\.\-]{1,31})$/u';

    public function __construct(private readonly string $value)
    {
        if (preg_match(self::PATTERN, $value) !== 1) {
            throw new InvalidArgumentException(sprintf('%s must match %s, got "%s"', self::NAME, self::PATTERN, $value));
        }
        if (mb_strlen($value) < 1) {
            throw new InvalidArgumentException(sprintf('%s must be at least 1 character(s)', self::NAME));
        }
        if (mb_strlen($value) > 31) {
            throw new InvalidArgumentException(sprintf('%s must be at most 31 character(s)', self::NAME));
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
