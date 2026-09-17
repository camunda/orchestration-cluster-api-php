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
    $replaceOnce = static function (
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
    };

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
        $configurationSource = $replaceOnce(
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
        $configurationSource = $replaceOnce(
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
                array_replace(
                    \Camunda\Orchestration\Http\OperationHost::variables($this->config->getHost()),
                    $this->config->getOperationHostVariables(),
                    $variables,
                ),
            );
PHP;
    $legacyConfiguredBuilder = <<<'PHP'
$operationHost = Configuration::getHostString(
                $hostSettings,
                $hostIndex,
                array_replace($this->config->getOperationHostVariables(), $variables),
            );
PHP;
    $hostSettingsAssignment = '$hostSettings = $this->getHostSettingsFor';
    $unconfiguredUrl = <<<'PHP'
                "url" => "{schema}://{host}:{port}",
                "description" => "No description provided",
                "variables" => [
                    "host" => [
                    "description" => "The hostname of the Orchestration Cluster REST Gateway.",
                    "default_value" => "localhost",
                    ],
                    "port" => [
                    "description" => "The port of the Orchestration Cluster REST API server.",
                    "default_value" => "8080",
                    ],
                    "schema" => [
                    "description" => "The schema of the Orchestration Cluster REST API server.",
                    "default_value" => "http",
                    ]
                ]
PHP;
    $configuredUrl = <<<'PHP'
                "url" => "{schema}://{host}:{port}{basePath}",
                "description" => "No description provided",
                "variables" => [
                    "host" => [
                    "description" => "The hostname of the Orchestration Cluster REST Gateway.",
                    "default_value" => "localhost",
                    ],
                    "port" => [
                    "description" => "The port of the Orchestration Cluster REST API server.",
                    "default_value" => "8080",
                    ],
                    "schema" => [
                    "description" => "The schema of the Orchestration Cluster REST API server.",
                    "default_value" => "http",
                    ],
                    "basePath" => [
                    "description" => "The path prefix of the Orchestration Cluster REST Gateway.",
                    "default_value" => "",
                    ]
                ]
PHP;
    $operationHostBuilders = 0;
    foreach (glob($outDir . '/src/Api/*.php') ?: [] as $file) {
        $source = (string) file_get_contents($file);
        $expected = substr_count($source, $hostSettingsAssignment);
        if ($expected === 0) {
            continue;
        }

        $unconfigured = substr_count($source, $unconfiguredBuilder);
        $configured = substr_count($source, $configuredBuilder);
        $legacyConfigured = substr_count($source, $legacyConfiguredBuilder);
        if ($expected !== $unconfigured + $legacyConfigured + $configured) {
            throw new RuntimeException(
                "hook 0125: expected $expected operation-specific host builder(s) in $file, found $unconfigured unconfigured, $legacyConfigured legacy configured, and $configured configured",
            );
        }

        if ($unconfigured > 0) {
            $source = str_replace($unconfiguredBuilder, $configuredBuilder, $source);
        }

        if ($legacyConfigured > 0) {
            $source = str_replace($legacyConfiguredBuilder, $configuredBuilder, $source);
        }

        $unconfiguredUrls = substr_count($source, $unconfiguredUrl);
        $configuredUrls = substr_count($source, $configuredUrl);
        if ($expected !== $unconfiguredUrls + $configuredUrls) {
            throw new RuntimeException(
                "hook 0125: expected $expected operation-specific host URL template(s) in $file, found $unconfiguredUrls unconfigured and $configuredUrls configured",
            );
        }

        if ($unconfiguredUrls > 0) {
            $source = str_replace($unconfiguredUrl, $configuredUrl, $source);
        }

        if (($unconfigured > 0 || $legacyConfigured > 0 || $unconfiguredUrls > 0) && file_put_contents($file, $source) === false) {
            throw new RuntimeException("hook 0125: cannot write $file");
        }

        $operationHostBuilders += $expected;
    }

    if ($operationHostBuilders === 0) {
        throw new RuntimeException('hook 0125: no operation-specific host builders found');
    }

    fwrite(STDOUT, "  [operation-hosts] configured $operationHostBuilders operation-specific server calls\n");
};
