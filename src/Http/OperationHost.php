<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Http;

use Camunda\Orchestration\Api\Configuration as ApiConfiguration;
use Camunda\Orchestration\Exception\ConfigurationException;

final class OperationHost
{
    /**
     * @param array<string, string> $configuredVariables
     * @param array<string, string> $variables
     * @return array<string, string>
     */
    public static function resolveVariables(
        string $restAddress,
        array $configuredVariables = [],
        array $variables = [],
    ): array {
        try {
            $derived = self::variables($restAddress);
        } catch (ConfigurationException) {
            $derived = [];
        }

        return array_replace($derived, $configuredVariables, $variables);
    }

    /**
     * @param array<int, array<string, mixed>> $hostSettings
     * @param array<string, string> $configuredVariables
     * @param array<string, string> $variables
     */
    public static function resolveHost(
        string $restAddress,
        array $hostSettings,
        int $hostIndex,
        array $configuredVariables = [],
        array $variables = [],
    ): string {
        $merged = array_replace($configuredVariables, $variables);
        $parts = parse_url($restAddress);

        if (self::isAbsolute($parts)) {
            return ApiConfiguration::getHostString(
                $hostSettings,
                $hostIndex,
                array_replace(self::variables($restAddress), $configuredVariables, $variables),
            );
        }

        if (self::isRelativePath($parts, $restAddress)) {
            if (self::hasAbsoluteOverride($merged)) {
                $basePath = self::basePath($parts);
                if ($basePath !== '' && !str_starts_with($basePath, '/')) {
                    $basePath = '/' . $basePath;
                }

                return ApiConfiguration::getHostString(
                    $hostSettings,
                    $hostIndex,
                    array_replace(['basePath' => $basePath], $merged),
                );
            }

            if (self::hasPartialAbsoluteOverride($merged)) {
                throw new ConfigurationException(
                    'Operation-host overrides for a relative CAMUNDA_REST_ADDRESS must include schema, host, and port.',
                );
            }

            return $merged['basePath'] ?? self::basePath($parts);
        }

        throw new ConfigurationException(
            'CAMUNDA_REST_ADDRESS must be an absolute URL with scheme and host to resolve operation-specific hosts.',
        );
    }

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
        if (!is_string($path) || $path === '') {
            return '';
        }

        $trimmedPath = rtrim($path, '/');
        if ($trimmedPath === '' || preg_match('#^/?v\d+$#', $trimmedPath) === 1) {
            return '';
        }

        $basePath = rtrim((string) preg_replace('#(?:^|/)v\d+$#', '', $trimmedPath), '/');

        return $basePath === '' || $basePath === '/' ? '' : $basePath;
    }

    /**
     * @param array<string, int|string>|false $parts
     */
    private static function isAbsolute(array|false $parts): bool
    {
        $scheme = is_array($parts) ? $parts['scheme'] ?? null : null;
        $host = is_array($parts) ? $parts['host'] ?? null : null;

        return is_string($scheme) && is_string($host) && $scheme !== '' && $host !== '';
    }

    /**
     * @param array<string, int|string>|false $parts
     */
    private static function isRelativePath(array|false $parts, string $restAddress): bool
    {
        if (!is_array($parts) || array_key_exists('scheme', $parts) || array_key_exists('host', $parts)) {
            return false;
        }

        $path = $parts['path'] ?? null;

        return is_string($path) && $path !== '' && !str_starts_with($restAddress, '//');
    }

    /**
     * @param array<string, string> $variables
     */
    private static function hasAbsoluteOverride(array $variables): bool
    {
        return isset($variables['schema'], $variables['host'], $variables['port']);
    }

    /**
     * @param array<string, string> $variables
     */
    private static function hasPartialAbsoluteOverride(array $variables): bool
    {
        return isset($variables['schema']) || isset($variables['host']) || isset($variables['port']);
    }
}
