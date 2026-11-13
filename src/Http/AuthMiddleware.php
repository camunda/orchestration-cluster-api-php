<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Http;

use Camunda\Orchestration\Auth\AuthProvider;
use Camunda\Orchestration\Auth\DelayAwareAuthProvider;
use Psr\Http\Message\RequestInterface;

/**
 * Guzzle middleware that stamps the current {@see AuthProvider} headers onto every
 * outgoing request. Kept as middleware (rather than static config) so that OAuth token
 * refresh happens transparently on each call.
 *
 * Guzzle's `delay` request option (milliseconds, set by the retry middleware for its
 * backoff) is applied by the transport after this middleware has run. A
 * {@see DelayAwareAuthProvider} is told about it, so the credentials it returns are
 * still valid when the delayed request is actually sent.
 */
final class AuthMiddleware
{
    public function __construct(private readonly AuthProvider $authProvider)
    {
    }

    /**
     * @param callable(RequestInterface, array<string, mixed>): mixed $handler
     * @return callable(RequestInterface, array<string, mixed>): mixed
     */
    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $headers = $this->authProvider instanceof DelayAwareAuthProvider
                ? $this->authProvider->getHeadersValidFor(self::delaySeconds($options))
                : $this->authProvider->getHeaders();
            foreach ($headers as $name => $value) {
                $request = $request->withHeader($name, $value);
            }
            /** @var array<string, mixed> $options */
            return $handler($request, $options);
        };
    }

    /**
     * @param array<int|string, mixed> $options
     */
    private static function delaySeconds(array $options): float
    {
        $delay = $options['delay'] ?? null;

        return is_numeric($delay) && (float) $delay > 0 ? (float) $delay / 1000 : 0.0;
    }
}
