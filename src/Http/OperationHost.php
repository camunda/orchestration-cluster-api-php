<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Http;

use Camunda\Orchestration\Exception\ConfigurationException;

final class OperationHost
{
    /**
     * @return array{schema: string, host: string, port: string}
     */
    public static function variables(string $restAddress): array
    {
        $parts = parse_url($restAddress);
        $scheme = is_array($parts) ? $parts['scheme'] ?? null : null;
        $host = is_array($parts) ? $parts['host'] ?? null : null;

        if (!is_string($scheme) || !is_string($host) || $scheme === '' || $host === '') {
            throw new ConfigurationException(
                "CAMUNDA_REST_ADDRESS must be an absolute URL to resolve operation-specific hosts: $restAddress",
            );
        }

        $port = is_array($parts) ? $parts['port'] ?? null : null;
        if (!is_int($port)) {
            $port = strtolower($scheme) === 'https' ? 443 : 80;
        }

        if (str_contains($host, ':') && !str_starts_with($host, '[')) {
            $host = "[$host]";
        }

        return [
            'schema' => $scheme,
            'host' => $host,
            'port' => (string) $port,
        ];
    }
}
