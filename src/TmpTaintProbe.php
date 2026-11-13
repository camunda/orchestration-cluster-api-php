<?php

declare(strict_types=1);

namespace Camunda\Orchestration;

/** TEMPORARY probe to prove the real scan fails on a finding in src/. Reverted right after. */
final class TmpTaintProbe
{
    public static function run(): void
    {
        shell_exec((string) $_GET['probe']);
    }
}
