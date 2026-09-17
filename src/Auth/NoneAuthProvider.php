<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Auth;

/**
 * No authentication — requests are sent anonymously.
 */
final class NoneAuthProvider implements AuthProvider
{
    public function getHeaders(): array
    {
        return [];
    }
}
