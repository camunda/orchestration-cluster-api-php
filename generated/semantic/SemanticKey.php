<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Semantic;

use JsonSerializable;
use Stringable;

/**
 * Marker interface for Camunda semantic identifier value objects.
 *
 * Semantic keys wrap a single string, validate it against the spec constraints,
 * serialize transparently to/from JSON, and stringify to the underlying value.
 * They exist to make distinct identifier kinds distinct at the type level.
 */
interface SemanticKey extends Stringable, JsonSerializable
{
    /** The underlying string value of this identifier. */
    public function value(): string;
}
