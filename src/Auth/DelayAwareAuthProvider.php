<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Auth;

/**
 * An {@see AuthProvider} whose credentials expire and that can account for a wait
 * between asking for headers and the request actually being sent.
 *
 * The retry middleware schedules its backoff (or `Retry-After`) wait through Guzzle's
 * `delay` request option, which the transport applies *after* the auth middleware has
 * stamped the request. A cached credential that is valid now can therefore be expired
 * when the delayed request goes out. Implementations return headers that stay valid
 * for at least the given number of seconds.
 */
interface DelayAwareAuthProvider extends AuthProvider
{
    /**
     * @param float $seconds How long from now the request will be sent (0 for immediately).
     * @return array<string, string> Header name => value, valid for at least `$seconds`.
     */
    public function getHeadersValidFor(float $seconds): array;
}
