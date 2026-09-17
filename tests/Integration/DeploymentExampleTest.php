<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Integration;

use Camunda\Orchestration\Api\Model\DeploymentResult;

final class DeploymentExampleTest extends IntegrationTestCase
{
    public function testDeploymentExamplesUseCheckedInResources(): void
    {
        $resources = dirname(__DIR__, 2) . '/examples/resources';
        self::assertFileIsReadable($resources . '/order-process.bpmn');
        self::assertFileIsReadable($resources . '/pricing.dmn');

        require_once dirname(__DIR__, 2) . '/examples/deployment.php';

        ob_start();
        try {
            $multipleDeployment = \Camunda\Orchestration\Examples\deploy_resources($this->client);
            $singleDeployment = \Camunda\Orchestration\Examples\deploy_single_resource($this->client);
        } finally {
            ob_end_clean();
        }

        self::assertInstanceOf(DeploymentResult::class, $multipleDeployment);
        self::assertInstanceOf(DeploymentResult::class, $singleDeployment);
    }
}
