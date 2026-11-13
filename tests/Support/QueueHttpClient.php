<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Support;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * PSR-18 test double: replays queued responses (or throws queued exceptions) in order and
 * records every request it receives. Running out of queued items is a test failure.
 */
final class QueueHttpClient implements ClientInterface
{
    /** @var list<RequestInterface> */
    public array $requests = [];

    /** @var list<ResponseInterface|\Throwable|\Closure(RequestInterface): ResponseInterface> */
    private array $queue;

    /**
     * @param ResponseInterface|\Throwable|\Closure(RequestInterface): ResponseInterface ...$queue
     */
    public function __construct(ResponseInterface|\Throwable|\Closure ...$queue)
    {
        $this->queue = array_values($queue);
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->requests[] = $request;

        $next = array_shift($this->queue);
        if ($next === null) {
            throw new \LogicException('QueueHttpClient has no queued response left for ' . $request->getMethod() . ' ' . $request->getUri());
        }
        if ($next instanceof \Throwable) {
            throw $next;
        }
        if ($next instanceof \Closure) {
            return $next($request);
        }

        return $next;
    }
}
