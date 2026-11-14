<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Http;

use Camunda\Orchestration\Auth\AuthProvider;
use Camunda\Orchestration\Config\CamundaConfiguration;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;

/**
 * Builds the Guzzle client the SDK uses when the caller does not supply one:
 * authentication, retry/backoff and mTLS wiring derived from the configuration.
 */
final class HttpClientFactory
{
    /**
     * @param (callable(\Psr\Http\Message\RequestInterface, array<int|string, mixed>): \GuzzleHttp\Promise\PromiseInterface)|null $handler
     *   Transport handler; defaults to Guzzle's automatic choice. Mainly useful for tests.
     */
    public static function create(
        CamundaConfiguration $config,
        AuthProvider $auth,
        ?callable $handler = null,
    ): ClientInterface {
        $stack = HandlerStack::create($handler);
        // Retry wraps auth (it is pushed first, so it sits outside), which makes every
        // attempt ask the auth provider again: a token can expire during a backoff wait.
        // The auth middleware also passes the scheduled `delay` on to the provider, because
        // the transport waits only after auth has run.
        $stack->push(new RetryMiddleware(
            $config->httpRetryMaxAttempts,
            $config->httpRetryBaseDelayMs,
            $config->httpRetryMaxDelayMs,
        ), 'camunda_retry');
        $stack->push(new AuthMiddleware($auth), 'camunda_auth');

        /** @var array<string, mixed> $options */
        $options = [
            'handler' => $stack,
            'http_errors' => false,
        ];

        if ($config->mtlsCertPath !== null) {
            $options['cert'] = $config->mtlsKeyPassphrase !== null
                ? [$config->mtlsCertPath, $config->mtlsKeyPassphrase]
                : $config->mtlsCertPath;
        }
        if ($config->mtlsKeyPath !== null) {
            $options['ssl_key'] = $config->mtlsKeyPassphrase !== null
                ? [$config->mtlsKeyPath, $config->mtlsKeyPassphrase]
                : $config->mtlsKeyPath;
        }
        if ($config->mtlsCaPath !== null) {
            $options['verify'] = $config->mtlsCaPath;
        }

        return new GuzzleClient($options);
    }
}
