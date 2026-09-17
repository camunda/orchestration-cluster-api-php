<?php

/**
 * Post-gen hook 0125 — resolve operation-specific hosts from SDK configuration.
 *
 * The OpenAPI document declares cluster-admin operation servers with localhost
 * defaults. Replace those defaults with the scheme, host, and port configured on
 * CamundaClient so remote clusters and containerized applications keep working.
 */

declare(strict_types=1);

return static function (array $ctx): void {
    $configuration = $ctx['out_dir'] . '/src/Configuration.php';
    if (!is_file($configuration)) {
        throw new RuntimeException('hook 0125: Configuration.php not found');
    }

    $configurationSource = (string) file_get_contents($configuration);
    if (!str_contains($configurationSource, 'getOperationHostVariables')) {
        $configurationSource = replace_once_operation_hosts(
            $configurationSource,
            "    protected bool \$ignoreOperationHosts = false;\n",
            <<<'PHP'
    protected bool $ignoreOperationHosts = false;

    /**
     * Variables that replace operation-specific server defaults.
     *
     * @var array<string, string>
     */
    protected array $operationHostVariables = [];
PHP,
            'operation-host variables property',
        );
        $configurationSource = replace_once_operation_hosts(
            $configurationSource,
            <<<'PHP'
    public function getIgnoreOperationHosts(): bool
    {
        return $this->ignoreOperationHosts;
    }
PHP,
            <<<'PHP'
    public function getIgnoreOperationHosts(): bool
    {
        return $this->ignoreOperationHosts;
    }

    /**
     * @param array<string, string> $operationHostVariables
     */
    public function setOperationHostVariables(array $operationHostVariables): static
    {
        $this->operationHostVariables = $operationHostVariables;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getOperationHostVariables(): array
    {
        return $this->operationHostVariables;
    }
PHP,
            'operation-host variables accessors',
        );

        if (file_put_contents($configuration, $configurationSource) === false) {
            throw new RuntimeException('hook 0125: cannot write Configuration.php');
        }
    }

    $replacement = 'array_replace($this->config->getOperationHostVariables(), $variables)';
    $callsiteNeedle = 'Configuration::getHostString(';
    $patched = 0;
    foreach (glob($ctx['out_dir'] . '/src/Api/*.php') ?: [] as $file) {
        $source = (string) file_get_contents($file);
        if (!str_contains($source, $callsiteNeedle)) {
            continue;
        }

        ['source' => $source, 'count' => $count] = patch_operation_host_calls($source, $replacement, $file);
        if ($count === 0) {
            continue;
        }

        if (file_put_contents($file, $source) === false) {
            throw new RuntimeException("hook 0125: cannot write $file");
        }
        $patched += $count;
    }

    fwrite(STDOUT, "  [operation-hosts] configured $patched operation-specific server calls\n");
};

function replace_once_operation_hosts(
    string $source,
    string $needle,
    string $replacement,
    string $description,
): string {
    $position = strpos($source, $needle);
    if ($position === false) {
        throw new RuntimeException("hook 0125: anchor for $description not found");
    }

    return substr($source, 0, $position) . $replacement . substr($source, $position + strlen($needle));
}

/**
 * @return array{source: string, count: int}
 */
function patch_operation_host_calls(string $source, string $replacement, string $file): array
{
    $needle = 'Configuration::getHostString(';
    $offset = 0;
    $patched = 0;
    $calls = [];

    while (($position = strpos($source, $needle, $offset)) !== false) {
        $openParen = $position + strlen($needle) - 1;
        $closeParen = find_matching_paren_operation_hosts($source, $openParen, $file);
        $arguments = parse_operation_host_arguments($source, $openParen + 1, $closeParen, $file);

        if (count($arguments) === 3 && str_contains($arguments[2]['value'], '$variables')) {
            $thirdArgument = trim($arguments[2]['value']);
            if ($thirdArgument === '$variables') {
                $calls[] = $arguments[2];
            } elseif ($thirdArgument !== $replacement) {
                throw new RuntimeException(
                    "hook 0125: unsupported operation-host variables argument '$thirdArgument' in $file",
                );
            }

            $patched++;
        }

        $offset = $closeParen + 1;
    }

    if ($calls === []) {
        return ['source' => $source, 'count' => $patched];
    }

    foreach (array_reverse($calls) as $call) {
        $value = $call['value'];
        $leading = substr($value, 0, strlen($value) - strlen(ltrim($value)));
        $trailing = substr($value, strlen(rtrim($value)));
        $source = substr($source, 0, $call['start'])
            . $leading
            . $replacement
            . $trailing
            . substr($source, $call['end']);
    }

    return ['source' => $source, 'count' => $patched];
}

function find_matching_paren_operation_hosts(string $source, int $openParen, string $file): int
{
    $depth = 0;
    $length = strlen($source);

    for ($index = $openParen; $index < $length; $index++) {
        $char = $source[$index];
        if ($char === '(') {
            $depth++;

            continue;
        }

        if ($char !== ')') {
            continue;
        }

        $depth--;
        if ($depth === 0) {
            return $index;
        }
    }

    throw new RuntimeException("hook 0125: unterminated operation-host call in $file");
}

/**
 * @return list<array{start: int, end: int, value: string}>
 */
function parse_operation_host_arguments(string $source, int $start, int $end, string $file): array
{
    $arguments = [];
    $depth = 0;
    $argumentStart = $start;

    for ($index = $start; $index < $end; $index++) {
        $char = $source[$index];
        if ($char === '(' || $char === '[' || $char === '{') {
            $depth++;

            continue;
        }

        if ($char === ')' || $char === ']' || $char === '}') {
            $depth--;

            continue;
        }

        if ($char !== ',' || $depth !== 0) {
            continue;
        }

        $arguments[] = [
            'start' => $argumentStart,
            'end' => $index,
            'value' => substr($source, $argumentStart, $index - $argumentStart),
        ];
        $argumentStart = $index + 1;
    }

    if ($depth !== 0) {
        throw new RuntimeException("hook 0125: unbalanced operation-host arguments in $file");
    }

    $arguments[] = [
        'start' => $argumentStart,
        'end' => $end,
        'value' => substr($source, $argumentStart, $end - $argumentStart),
    ];

    $last = $arguments[array_key_last($arguments)];
    if (count($arguments) > 1 && trim($last['value']) === '') {
        array_pop($arguments);
    }

    return $arguments;
}
