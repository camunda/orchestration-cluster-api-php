<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Model\DeploymentResult;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Config\ConfigResolver;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

final class DeploymentHelperTest extends TestCase
{
    /** @var list<RequestInterface> */
    private array $requests = [];

    /** @var list<string> */
    private array $resourcePaths = [];

    protected function setUp(): void
    {
        $this->createResourceFile('<definitions id="test-1" />');
        $this->createResourceFile('<definitions id="test-2" />');
    }

    protected function tearDown(): void
    {
        foreach ($this->resourcePaths as $resourcePath) {
            if (is_file($resourcePath)) {
                unlink($resourcePath);
            }
        }
    }

    public function testDeploymentHelperUsesRepeatedResourcesMultipartField(): void
    {
        $client = CamundaClient::fromConfiguration(
            ConfigResolver::resolve(
                overrides: [
                    'CAMUNDA_AUTH_STRATEGY' => 'NONE',
                    'CAMUNDA_TENANT_ID' => 'acme',
                ],
                environment: [],
            ),
            $this->httpClient(new Response(
                200,
                ['Content-Type' => 'application/json'],
                (string) json_encode([
                    'deploymentKey' => '2251799813685249',
                    'tenantId' => 'acme',
                    'deployments' => [],
                ]),
            )),
        );

        $deployment = $client->deployResourcesFromFiles(...$this->resourcePaths);

        self::assertInstanceOf(DeploymentResult::class, $deployment);
        self::assertCount(1, $this->requests);
        self::assertStringContainsString('multipart/form-data', $this->requests[0]->getHeaderLine('Content-Type'));

        $body = (string) $this->requests[0]->getBody();
        self::assertSame(2, substr_count($body, 'name="resources"'));
        self::assertStringNotContainsString('name="resources[0]"', $body);
        self::assertStringNotContainsString('name="resources[1]"', $body);
        self::assertStringContainsString('test-1', $body);
        self::assertStringContainsString('test-2', $body);
        self::assertStringContainsString('name="tenantId"', $body);
        self::assertStringContainsString("\r\nacme\r\n", $body);
    }

    private function createResourceFile(string $contents): void
    {
        $path = tempnam(sys_get_temp_dir(), 'orchestration-resource-');
        self::assertNotFalse($path);
        $resourcePath = $path . '.bpmn';
        rename($path, $resourcePath);
        file_put_contents($resourcePath, $contents);
        $this->resourcePaths[] = $resourcePath;
    }

    private function httpClient(Response $response): GuzzleClient
    {
        $stack = HandlerStack::create(new MockHandler([$response]));
        $stack->push(function (callable $handler): callable {
            return function (RequestInterface $request, array $options) use ($handler) {
                $this->requests[] = $request;
                return $handler($request, $options);
            };
        });

        return new GuzzleClient(['handler' => $stack, 'http_errors' => false]);
    }
}
