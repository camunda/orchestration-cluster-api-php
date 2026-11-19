<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Support;

/**
 * Deterministic clock for expiry tests: injected as `\Closure` via {@see self::closure()}.
 */
final class FakeClock
{
    public function __construct(private float $now = 1_000_000.0)
    {
    }

    public function now(): float
    {
        return $this->now;
    }

    public function set(float $now): void
    {
        $this->now = $now;
    }

    public function advance(float $seconds): void
    {
        $this->now += $seconds;
    }

    /** @return \Closure(): float */
    public function closure(): \Closure
    {
        return fn (): float => $this->now;
    }
}
