<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Http;

use Camunda\Orchestration\Exception\ConfigurationException;

final class OperationHost
{
    /**
     * @return array{schema: string, host: string, port: string, basePath: string}
     */
    public static function variables(string $restAddress): array
    {
        $parts = parse_url($restAddress);
        $scheme = is_array($parts) ? $parts['scheme'] ?? null : null;
        $host = is_array($parts) ? $parts['host'] ?? null : null;

        if (!is_string($scheme) || !is_string($host) || $scheme === '' || $host === '') {
            throw new ConfigurationException(
                'CAMUNDA_REST_ADDRESS must be an absolute URL with scheme and host to resolve operation-specific hosts.',
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
            'basePath' => self::basePath($parts),
        ];
    }

    /**
     * @param array<string, int|string>|false $parts
     */
    private static function basePath(array|false $parts): string
    {
        $path = is_array($parts) ? $parts['path'] ?? '' : '';
        if (!is_string($path) || $path === '' || $path === '/v2') {
            return '';
        }

        $basePath = rtrim((string) preg_replace('#/v\d+$#', '', rtrim($path, '/')), '/');

        return $basePath === '' || $basePath === '/' ? '' : $basePath;
    }
}
