<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Integration;

use Camunda\Orchestration\Api\Api\ClusterApi;
use Camunda\Orchestration\Api\Model\TopologyResponse;
use Camunda\Orchestration\CamundaClient;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests run against a live Camunda 8 cluster (see docker/docker-compose.yaml).
 *
 * They are skipped unless CAMUNDA_INTEGRATION=1 is set. By default they target the local
 * unprotected cluster on http://localhost:8080 with no authentication.
 */
abstract class IntegrationTestCase extends TestCase
{
    protected CamundaClient $client;

    protected function setUp(): void
    {
        if (getenv('CAMUNDA_INTEGRATION') !== '1') {
            self::markTestSkipped('Set CAMUNDA_INTEGRATION=1 to run integration tests.');
        }

        $this->client = CamundaClient::fromEnvironment([
            'CAMUNDA_REST_ADDRESS' => getenv('CAMUNDA_REST_ADDRESS') ?: 'http://localhost:8080',
            'CAMUNDA_AUTH_STRATEGY' => 'NONE',
        ]);

        $this->awaitCluster();
    }

    private function awaitCluster(): void
    {
        $cluster = $this->client->api(ClusterApi::class);
        $deadline = microtime(true) + 60.0;

        do {
            try {
                $topology = $cluster->getTopology();
                if ($topology instanceof TopologyResponse && $topology->getClusterSize() > 0) {
                    return;
                }
            } catch (\Throwable) {
                // cluster not ready yet
            }
            usleep(500_000);
        } while (microtime(true) < $deadline);

        self::markTestSkipped('Camunda cluster did not become ready within 60s.');
    }
}
