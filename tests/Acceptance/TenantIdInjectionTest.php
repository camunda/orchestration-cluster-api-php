<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Model\ConditionalEvaluationInstruction;
use Camunda\Orchestration\Api\Model\DecisionEvaluationById;
use Camunda\Orchestration\Api\Model\DecisionEvaluationByKey;
use Camunda\Orchestration\Api\Model\ExpressionEvaluationRequest;
use Camunda\Orchestration\Api\Model\MessageCorrelationRequest;
use Camunda\Orchestration\Api\Model\MessagePublicationRequest;
use Camunda\Orchestration\Api\Model\ProcessInstanceCreationInstructionById;
use Camunda\Orchestration\Api\Model\ProcessInstanceCreationInstructionByKey;
use Camunda\Orchestration\Api\Model\SignalBroadcastRequest;
use Camunda\Orchestration\CamundaAsyncClient;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Config\ConfigResolver;
use Camunda\Orchestration\Semantic\TenantId;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * CAMUNDA_TENANT_ID must be injected into tenant-aware operations when the
 * caller omits tenantId, matching the JS, Python and C# SDK behaviour.
 */
final class TenantIdInjectionTest extends TestCase
{
    /** @var list<RequestInterface> */
    private array $requests = [];

    public function testCreateProcessInstanceInjectsDefaultTenantId(): void
    {
        $client = $this->clientWith(new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode([
                'processInstanceKey' => '2251799813685249',
                'tenantId' => 'acme',
            ]),
        ));

        $instruction = new ProcessInstanceCreationInstructionByKey([
            'processDefinitionKey' => '2251799813685249',
        ]);

        $client->createProcessInstance($instruction);

        self::assertCount(1, $this->requests);
        $payload = $this->decodeBody($this->requests[0]);
        self::assertSame('acme', $payload['tenantId']);
    }

    public function testCreateProcessInstanceByIdInjectsDefaultTenantId(): void
    {
        $client = $this->clientWith(new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode([
                'processInstanceKey' => '2251799813685249',
                'tenantId' => 'acme',
            ]),
        ));

        $instruction = new ProcessInstanceCreationInstructionById([
            'processDefinitionId' => 'my-process',
        ]);

        $client->createProcessInstance($instruction);

        self::assertCount(1, $this->requests);
        $payload = $this->decodeBody($this->requests[0]);
        self::assertSame('acme', $payload['tenantId']);
    }

    public function testCreateProcessInstancePreservesExplicitTenantId(): void
    {
        $client = $this->clientWith(new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode([
                'processInstanceKey' => '2251799813685249',
                'tenantId' => 'globex',
            ]),
        ));

        $instruction = new ProcessInstanceCreationInstructionByKey([
            'processDefinitionKey' => '2251799813685249',
            'tenantId' => 'globex',
        ]);

        $client->createProcessInstance($instruction);

        self::assertCount(1, $this->requests);
        $payload = $this->decodeBody($this->requests[0]);
        self::assertSame('globex', $payload['tenantId']);
    }

    public function testEvaluateDecisionByKeyInjectsDefaultTenantId(): void
    {
        $client = $this->clientWith(new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode([
                'decisionEvaluationKey' => '2251799813685249',
                'tenantId' => 'acme',
            ]),
        ));

        $instruction = new DecisionEvaluationByKey([
            'decisionDefinitionKey' => '2251799813685249',
        ]);

        $client->evaluateDecision($instruction);

        self::assertCount(1, $this->requests);
        $payload = $this->decodeBody($this->requests[0]);
        self::assertSame('acme', $payload['tenantId']);
    }

    public function testEvaluateDecisionByIdInjectsDefaultTenantId(): void
    {
        $client = $this->clientWith(new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode([
                'decisionEvaluationKey' => '2251799813685249',
                'tenantId' => 'acme',
            ]),
        ));

        $instruction = new DecisionEvaluationById([
            'decisionDefinitionId' => 'my-decision',
        ]);

        $client->evaluateDecision($instruction);

        self::assertCount(1, $this->requests);
        $payload = $this->decodeBody($this->requests[0]);
        self::assertSame('acme', $payload['tenantId']);
    }

    public function testPublishMessageInjectsDefaultTenantId(): void
    {
        $client = $this->clientWith(new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode([
                'messageKey' => '2251799813685249',
                'tenantId' => 'acme',
            ]),
        ));

        $request = new MessagePublicationRequest([
            'name' => 'my-message',
        ]);

        $client->publishMessage($request);

        self::assertCount(1, $this->requests);
        $payload = $this->decodeBody($this->requests[0]);
        self::assertSame('acme', $payload['tenantId']);
    }

    public function testCorrelateMessageInjectsDefaultTenantId(): void
    {
        $client = $this->clientWith(new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode([
                'messageKey' => '2251799813685249',
                'tenantId' => 'acme',
            ]),
        ));

        $request = new MessageCorrelationRequest([
            'name' => 'my-message',
            'correlationKey' => 'order-123',
        ]);

        $client->correlateMessage($request);

        self::assertCount(1, $this->requests);
        $payload = $this->decodeBody($this->requests[0]);
        self::assertSame('acme', $payload['tenantId']);
    }

    public function testBroadcastSignalInjectsDefaultTenantId(): void
    {
        $client = $this->clientWith(new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode([
                'signalKey' => '2251799813685249',
                'tenantId' => 'acme',
            ]),
        ));

        $request = new SignalBroadcastRequest([
            'signalName' => 'my-signal',
        ]);

        $client->broadcastSignal($request);

        self::assertCount(1, $this->requests);
        $payload = $this->decodeBody($this->requests[0]);
        self::assertSame('acme', $payload['tenantId']);
    }

    public function testEvaluateConditionalsInjectsDefaultTenantId(): void
    {
        $client = $this->clientWith(new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode([
                'tenantId' => 'acme',
            ]),
        ));

        $instruction = new ConditionalEvaluationInstruction([
            'processDefinitionKey' => '2251799813685249',
            'variables' => [],
        ]);

        $client->evaluateConditionals($instruction);

        self::assertCount(1, $this->requests);
        $payload = $this->decodeBody($this->requests[0]);
        self::assertSame('acme', $payload['tenantId']);
    }

    public function testEvaluateExpressionInjectsDefaultTenantId(): void
    {
        $client = $this->clientWith(new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode([
                'result' => 42,
            ]),
        ));

        $request = new ExpressionEvaluationRequest([
            'expression' => '1 + 41',
        ]);

        $client->evaluateExpression($request);

        self::assertCount(1, $this->requests);
        $payload = $this->decodeBody($this->requests[0]);
        self::assertSame('acme', $payload['tenantId']);
    }

    public function testCreateDeploymentInjectsDefaultTenantId(): void
    {
        $client = $this->clientWith(new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode([
                'deploymentKey' => '2251799813685249',
                'tenantId' => 'acme',
                'deployments' => [],
            ]),
        ));

        $client->createDeployment([new \SplFileObject(__FILE__, 'r')]);

        self::assertCount(1, $this->requests);
        $body = (string) $this->requests[0]->getBody();
        self::assertStringContainsString('name="tenantId"', $body);
        self::assertStringContainsString("\r\nacme\r\n", $body);
    }

    public function testCreateDeploymentPreservesExplicitTenantId(): void
    {
        $client = $this->clientWith(new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode([
                'deploymentKey' => '2251799813685249',
                'tenantId' => 'globex',
                'deployments' => [],
            ]),
        ));

        $client->createDeployment([new \SplFileObject(__FILE__, 'r')], 'globex');

        self::assertCount(1, $this->requests);
        $body = (string) $this->requests[0]->getBody();
        self::assertStringContainsString('name="tenantId"', $body);
        self::assertStringContainsString("\r\nglobex\r\n", $body);
    }

    public function testNoInjectionWhenNoDefaultTenantConfigured(): void
    {
        $client = CamundaClient::fromConfiguration(
            ConfigResolver::resolve(
                overrides: ['CAMUNDA_AUTH_STRATEGY' => 'NONE'],
                environment: [],
            ),
            $this->mockHttpClient(new Response(
                200,
                ['Content-Type' => 'application/json'],
                (string) json_encode([
                    'processInstanceKey' => '2251799813685249',
                    'tenantId' => '<default>',
                ]),
            )),
        );

        $instruction = new ProcessInstanceCreationInstructionByKey([
            'processDefinitionKey' => '2251799813685249',
        ]);

        $client->createProcessInstance($instruction);

        self::assertCount(1, $this->requests);
        $payload = $this->decodeBody($this->requests[0]);
        self::assertArrayNotHasKey('tenantId', $payload);
    }

    public function testAsyncCreateProcessInstanceInjectsDefaultTenantId(): void
    {
        $client = $this->asyncClientWith(new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode([
                'processInstanceKey' => '2251799813685249',
                'tenantId' => 'acme',
            ]),
        ));

        $instruction = new ProcessInstanceCreationInstructionByKey([
            'processDefinitionKey' => '2251799813685249',
        ]);

        $client->createProcessInstance($instruction)->wait();

        self::assertCount(1, $this->requests);
        $payload = $this->decodeBody($this->requests[0]);
        self::assertSame('acme', $payload['tenantId']);
    }

    private function clientWith(Response $response): CamundaClient
    {
        return CamundaClient::fromConfiguration(
            ConfigResolver::resolve(
                overrides: [
                    'CAMUNDA_AUTH_STRATEGY' => 'NONE',
                    'CAMUNDA_TENANT_ID' => 'acme',
                ],
                environment: [],
            ),
            $this->mockHttpClient($response),
        );
    }

    private function asyncClientWith(Response $response): CamundaAsyncClient
    {
        return CamundaAsyncClient::fromConfiguration(
            ConfigResolver::resolve(
                overrides: [
                    'CAMUNDA_AUTH_STRATEGY' => 'NONE',
                    'CAMUNDA_TENANT_ID' => 'acme',
                ],
                environment: [],
            ),
            $this->mockHttpClient($response),
        );
    }

    private function mockHttpClient(Response $response): GuzzleClient
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

    /**
     * @return array<string, mixed>
     */
    private function decodeBody(RequestInterface $request): array
    {
        $body = (string) $request->getBody();
        /** @var array<string, mixed> $payload */
        $payload = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        return $payload;
    }
}
