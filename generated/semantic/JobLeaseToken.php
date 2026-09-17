<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Semantic;

use InvalidArgumentException;

/**
 * An opaque, engine-minted fencing token identifying a single activation of a job. Returned by Activate Jobs as `ActivatedJobResult.leaseToken` when the job is activated with a lease, and passed back on fenced job commands — and on agent-instance creation/updates as `jobLease` — to prove the caller holds the current lease. The token is opaque: clients may rely on its presence and equality only, and must never construct, parse, or otherwise interpret it beyond equality checks. It cannot be minted client-side; only the engine produces it, exactly once per leased activation, and clients must not depend on any particular internal format.
 *
 * Distinct, immutable, self-validating semantic identifier. Wraps a string and
 * serializes transparently to/from JSON.
 */
final class JobLeaseToken implements SemanticKey
{
    public const NAME = 'JobLeaseToken';

    public function __construct(private readonly string $value)
    {
        if (mb_strlen($value) < 1) {
            throw new InvalidArgumentException(sprintf('%s must be at least 1 character(s)', self::NAME));
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
