<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Auth;

/**
 * Supplies the HTTP authorization headers for outgoing requests.
 *
 * Implementations must be safe to call once per request; providers that acquire
 * short-lived credentials (e.g. OAuth) cache and refresh internally.
 */
interface AuthProvider
{
    /**
     * @return array<string, string> Header name => value (empty for anonymous access).
     */
    public function getHeaders(): array;
}
