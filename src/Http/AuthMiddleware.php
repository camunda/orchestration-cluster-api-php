<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Http;

use Camunda\Orchestration\Auth\AuthProvider;
use Psr\Http\Message\RequestInterface;

/**
 * Guzzle middleware that stamps the current {@see AuthProvider} headers onto every
 * outgoing request. Kept as middleware (rather than static config) so that OAuth token
 * refresh happens transparently on each call.
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
            foreach ($this->authProvider->getHeaders() as $name => $value) {
                $request = $request->withHeader($name, $value);
            }
            /** @var array<string, mixed> $options */
            return $handler($request, $options);
        };
    }
}
