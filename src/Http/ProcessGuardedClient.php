<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Http;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Decorates the HTTP client handed to generated API groups so every request runs a check first
 * (issue #190: detect a client used in a forked child, even through an API group cached before
 * the fork).
 *
 * @internal
 */
final class ProcessGuardedClient implements ClientInterface
{
    /** @param \Closure(): void $check */
    public function __construct(
        private readonly ClientInterface $inner,
        private readonly \Closure $check,
    ) {
    }

    /** @param array<string, mixed> $options */
    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        ($this->check)();
        return $this->inner->send($request, $options);
    }

    /** @param array<string, mixed> $options */
    public function sendAsync(RequestInterface $request, array $options = []): PromiseInterface
    {
        ($this->check)();
        return $this->inner->sendAsync($request, $options);
    }

    /** @param array<string, mixed> $options */
    public function request(string $method, $uri, array $options = []): ResponseInterface
    {
        ($this->check)();
        return $this->inner->request($method, $uri, $options);
    }

    /** @param array<string, mixed> $options */
    public function requestAsync(string $method, $uri, array $options = []): PromiseInterface
    {
        ($this->check)();
        return $this->inner->requestAsync($method, $uri, $options);
    }

    /** @deprecated Mirrors {@see ClientInterface::getConfig()}. */
    public function getConfig(?string $option = null)
    {
        return $this->inner->getConfig($option);
    }
}
