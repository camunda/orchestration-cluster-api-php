<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Auth;

/**
 * HTTP Basic authentication.
 */
final class BasicAuthProvider implements AuthProvider
{
    private readonly string $header;

    public function __construct(string $username, string $password)
    {
        $this->header = 'Basic ' . base64_encode($username . ':' . $password);
    }

    public function getHeaders(): array
    {
        return ['Authorization' => $this->header];
    }
}
