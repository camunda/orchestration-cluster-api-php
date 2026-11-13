<?php

declare(strict_types=1);

// Deliberately vulnerable. NOT part of the SDK: it is in no Composer autoload path and is
// never executed. It only exists so that `make security-selftest` can prove the taint
// analysis is able to find a real source-to-sink flow.

function runUserCommand(): void
{
    shell_exec($_GET['cmd']);
}
