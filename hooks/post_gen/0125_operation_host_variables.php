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
    $outDir = $ctx['out_dir'] ?? null;
    if (!is_string($outDir) || $outDir === '') {
        throw new RuntimeException('hook 0125: output directory not provided');
    }

    $configuration = $outDir . '/src/Configuration.php';
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

    $unconfiguredBuilder = '$operationHost = Configuration::getHostString($hostSettings, $hostIndex, $variables);';
    $configuredBuilder = <<<'PHP'
$operationHost = Configuration::getHostString(
                $hostSettings,
                $hostIndex,
                array_replace($this->config->getOperationHostVariables(), $variables),
            );
PHP;
    $hostSettingsAssignment = '$hostSettings = $this->getHostSettingsFor';
    $operationHostBuilders = 0;
    foreach (glob($outDir . '/src/Api/*.php') ?: [] as $file) {
        $source = (string) file_get_contents($file);
        $expected = substr_count($source, $hostSettingsAssignment);
        if ($expected === 0) {
            continue;
        }

        $unconfigured = substr_count($source, $unconfiguredBuilder);
        $configured = substr_count($source, $configuredBuilder);
        if ($expected !== $unconfigured + $configured) {
            throw new RuntimeException(
                "hook 0125: expected $expected operation-specific host builder(s) in $file, found $unconfigured unconfigured and $configured configured",
            );
        }

        if ($unconfigured > 0) {
            $source = str_replace($unconfiguredBuilder, $configuredBuilder, $source);
            if (file_put_contents($file, $source) === false) {
                throw new RuntimeException("hook 0125: cannot write $file");
            }
        }

        $operationHostBuilders += $expected;
    }

    if ($operationHostBuilders === 0) {
        throw new RuntimeException('hook 0125: no operation-specific host builders found');
    }

    fwrite(STDOUT, "  [operation-hosts] configured $operationHostBuilders operation-specific server calls\n");
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
