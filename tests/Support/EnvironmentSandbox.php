<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Support;

use Camunda\Orchestration\Config\ConfigResolver;

/**
 * Isolates process-environment mutations (`putenv`) made by a test: every variable the SDK
 * understands is captured and cleared on {@see self::enter()}, and restored on {@see self::leave()}.
 * Use it from setUp()/tearDown().
 */
final class EnvironmentSandbox
{
    /** @var array<string, string|false> */
    private array $saved = [];

    public function enter(): void
    {
        foreach (ConfigResolver::CONFIG_KEYS as $key) {
            $this->saved[$key] = getenv($key);
            putenv($key);
        }
    }

    public function set(string $key, string $value): void
    {
        putenv("$key=$value");
    }

    public function leave(): void
    {
        foreach ($this->saved as $key => $value) {
            if ($value === false) {
                putenv($key);
            } else {
                putenv("$key=$value");
            }
        }
        $this->saved = [];
    }
}
