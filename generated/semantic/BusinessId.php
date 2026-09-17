<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Semantic;

use InvalidArgumentException;

/**
 * An optional, user-defined string identifier that identifies the process instance within the scope of a process definition (scoped by tenant). If provided and uniqueness enforcement is enabled, the engine will reject creation if another root process instance with the same business id is already active for the same process definition. Note that any active child process instances with the same business id are not taken into account.
 *
 * Distinct, immutable, self-validating semantic identifier. Wraps a string and
 * serializes transparently to/from JSON.
 */
final class BusinessId implements SemanticKey
{
    public const NAME = 'BusinessId';

    public function __construct(private readonly string $value)
    {
        if (mb_strlen($value) < 1) {
            throw new InvalidArgumentException(sprintf('%s must be at least 1 character(s)', self::NAME));
        }
        if (mb_strlen($value) > 256) {
            throw new InvalidArgumentException(sprintf('%s must be at most 256 character(s)', self::NAME));
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
