<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Api\Model\ConditionalEvaluationInstruction;
use Camunda\Orchestration\Api\Model\DecisionEvaluationById;
use Camunda\Orchestration\Api\Model\DecisionEvaluationByKey;
use Camunda\Orchestration\Api\Model\ExpressionEvaluationRequest;
use Camunda\Orchestration\Api\Model\JobActivationRequest;
use Camunda\Orchestration\Api\Model\MessageCorrelationRequest;
use Camunda\Orchestration\Api\Model\MessagePublicationRequest;
use Camunda\Orchestration\Api\Model\ProcessInstanceCreationInstructionById;
use Camunda\Orchestration\Api\Model\ProcessInstanceCreationInstructionByKey;
use Camunda\Orchestration\Api\Model\ProcessInstanceSearchQuery;
use Camunda\Orchestration\Api\Model\SignalBroadcastRequest;
use Camunda\Orchestration\Api\Model\TenantFilterEnum;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Config\CamundaConfiguration;
use Camunda\Orchestration\Config\ConfigResolver;
use Camunda\Orchestration\Semantic\TenantId;
use Camunda\Orchestration\Worker\JobWorkerOptions;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * Issue #138: tenant-scoped commands send the effective default tenant
 * (CAMUNDA_TENANT_ID, else `<default>`) when the caller passes none, matching
 * the Camunda Java client. Explicit tenants win; searches never get a tenant.
 */
final class DefaultTenantTest extends TestCase
{
    /** @var list<RequestInterface> */
    private array $requests = [];

    /** @var list<string> */
    private array $tempFiles = [];

    protected function tearDown(): void
    {
        foreach ($this->tempFiles as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        $this->tempFiles = [];
    }

    /**
     * @return iterable<string, array{0: \Closure(CamundaClient, ?string): mixed}>
     */
    public static function tenantScopedCommands(): iterable
    {
        $t = static fn (?string $tenant): array => $tenant === null ? [] : ['tenantId' => new TenantId($tenant)];
        yield 'createProcessInstance by id' => [static fn (CamundaClient $c, ?string $tenant) => $c->createProcessInstance(new ProcessInstanceCreationInstructionById(['processDefinitionId' => 'p'] + $t($tenant)))];
        yield 'createProcessInstance by key' => [static fn (CamundaClient $c, ?string $tenant) => $c->createProcessInstance(new ProcessInstanceCreationInstructionByKey(['processDefinitionKey' => '2251799813685249'] + $t($tenant)))];
        yield 'createProcessInstance by id with version' => [static fn (CamundaClient $c, ?string $tenant) => $c->createProcessInstance(new ProcessInstanceCreationInstructionById(['processDefinitionId' => 'p', 'processDefinitionVersion' => 2] + $t($tenant)))];
        yield 'publishMessage' => [static fn (CamundaClient $c, ?string $tenant) => $c->publishMessage(new MessagePublicationRequest(['name' => 'm'] + $t($tenant)))];
        yield 'correlateMessage' => [static fn (CamundaClient $c, ?string $tenant) => $c->correlateMessage(new MessageCorrelationRequest(['name' => 'm', 'correlationKey' => 'k'] + $t($tenant)))];
        yield 'broadcastSignal' => [static fn (CamundaClient $c, ?string $tenant) => $c->broadcastSignal(new SignalBroadcastRequest(['signalName' => 's'] + $t($tenant)))];
        yield 'evaluateDecision by id' => [static fn (CamundaClient $c, ?string $tenant) => $c->evaluateDecision(new DecisionEvaluationById(['decisionDefinitionId' => 'd'] + $t($tenant)))];
        yield 'evaluateDecision by key' => [static fn (CamundaClient $c, ?string $tenant) => $c->evaluateDecision(new DecisionEvaluationByKey(['decisionDefinitionKey' => '2251799813685249'] + $t($tenant)))];
        yield 'evaluateExpression' => [static fn (CamundaClient $c, ?string $tenant) => $c->evaluateExpression(new ExpressionEvaluationRequest(['expression' => '1'] + ($tenant === null ? [] : ['tenantId' => $tenant])))];
        yield 'evaluateConditionals' => [static fn (CamundaClient $c, ?string $tenant) => $c->evaluateConditionals(new ConditionalEvaluationInstruction(['processDefinitionKey' => '2251799813685249', 'variables' => []] + $t($tenant)))];
    }

    /** @param \Closure(CamundaClient, ?string): mixed $call */
    #[DataProvider('tenantScopedCommands')]
    public function testSendsBuiltInDefaultWhenNothingConfigured(\Closure $call): void
    {
        $call($this->client([]), null);
        self::assertSame('<default>', $this->body()['tenantId'] ?? null);
    }

    /** @param \Closure(CamundaClient, ?string): mixed $call */
    #[DataProvider('tenantScopedCommands')]
    public function testSendsConfiguredDefault(\Closure $call): void
    {
        $call($this->client(['CAMUNDA_TENANT_ID' => 'acme']), null);
        self::assertSame('acme', $this->body()['tenantId'] ?? null);
    }

    /** @param \Closure(CamundaClient, ?string): mixed $call */
    #[DataProvider('tenantScopedCommands')]
    public function testExplicitTenantWins(\Closure $call): void
    {
        $call($this->client(['CAMUNDA_TENANT_ID' => 'acme']), 'explicit');
        self::assertSame('explicit', $this->body()['tenantId'] ?? null);
    }

    public function testCreateDeploymentSendsDefaultTenantPart(): void
    {
        $this->client([])->createDeployment([$this->bpmnFile()]);
        self::assertMatchesRegularExpression('/name="tenantId"\r\n(?:[^\r\n]+\r\n)*\r\n<default>\r\n/', (string) $this->requests[0]->getBody());
    }

    public function testCreateDeploymentConfiguredAndExplicit(): void
    {
        $client = $this->client(['CAMUNDA_TENANT_ID' => 'acme']);
        $client->createDeployment([$this->bpmnFile()]);
        $client->createDeployment([$this->bpmnFile()], 'explicit');
        self::assertMatchesRegularExpression('/\r\n\r\nacme\r\n/', (string) $this->requests[0]->getBody());
        self::assertMatchesRegularExpression('/\r\n\r\nexplicit\r\n/', (string) $this->requests[1]->getBody());
    }

    /**
     * @return iterable<string, array{0: ?TenantFilterEnum, 1: ?list<string>}>
     */
    public static function activationWithoutTenants(): iterable
    {
        yield 'unset filter, absent ids' => [null, null];
        yield 'unset filter, empty ids' => [null, []];
        yield 'PROVIDED, absent ids' => [TenantFilterEnum::PROVIDED, null];
        yield 'PROVIDED, empty ids' => [TenantFilterEnum::PROVIDED, []];
    }

    /** @param ?list<string> $ids */
    #[DataProvider('activationWithoutTenants')]
    public function testActivateJobsSendsDefaultTenant(?TenantFilterEnum $filter, ?array $ids): void
    {
        $this->client([])->activateJobs($this->activation($filter, $ids));
        self::assertSame(['<default>'], $this->body()['tenantIds'] ?? null);
    }

    public function testActivateJobsSendsConfiguredTenants(): void
    {
        $this->client(['CAMUNDA_TENANT_IDS' => 'a,b'])->activateJobs($this->activation(null, null));
        self::assertSame(['a', 'b'], $this->body()['tenantIds'] ?? null);
    }

    public function testActivateJobsExplicitTenantsWin(): void
    {
        $this->client([])->activateJobs($this->activation(TenantFilterEnum::PROVIDED, ['x']));
        self::assertSame(['x'], $this->body()['tenantIds'] ?? null);
    }

    public function testActivateJobsAssignedFilterLeavesTenantIdsAlone(): void
    {
        $this->client([])->activateJobs($this->activation(TenantFilterEnum::ASSIGNED, null));
        self::assertArrayNotHasKey('tenantIds', $this->body());
    }

    public function testJobWorkerActivatesForDefaultTenant(): void
    {
        $this->client([], '{"jobs":[]}')->createJobWorker(new JobWorkerOptions(type: 't'))->pollOnce(static fn () => []);
        $body = $this->body();
        self::assertSame(['<default>'], $body['tenantIds'] ?? null);
        self::assertSame('PROVIDED', $body['tenantFilter'] ?? null);
    }

    public function testSearchSendsNoTenant(): void
    {
        $this->client(['CAMUNDA_TENANT_ID' => 'acme'])->searchProcessInstances(new ProcessInstanceSearchQuery());
        self::assertStringNotContainsString('tenant', (string) $this->requests[0]->getBody());
    }

    public function testConfigurationDefaults(): void
    {
        $config = new CamundaConfiguration(restAddress: 'http://localhost:8080', authStrategy: 'NONE');
        self::assertSame('<default>', $config->defaultTenantId());
        self::assertSame(['<default>'], $config->defaultJobTenantIds());
    }

    /** @param ?list<string> $ids */
    private function activation(?TenantFilterEnum $filter, ?array $ids): JobActivationRequest
    {
        $request = new JobActivationRequest(['type' => 't', 'timeout' => 1000, 'maxJobsToActivate' => 1]);
        if ($filter !== null) {
            $request->setTenantFilter($filter);
        }
        if ($ids !== null) {
            $request->setTenantIds($ids);
        }
        return $request;
    }

    private function bpmnFile(): \SplFileObject
    {
        $tmp = tempnam(sys_get_temp_dir(), 'bpmn');
        self::assertNotFalse($tmp);
        $path = $tmp . '.bpmn';
        rename($tmp, $path);
        $this->tempFiles[] = $path;
        file_put_contents($path, '<definitions/>');
        return new \SplFileObject($path, 'r');
    }

    /** @param array<string, string> $overrides */
    private function client(array $overrides, string $responseBody = '{}'): CamundaClient
    {
        $responses = [];
        for ($i = 0; $i < 4; ++$i) {
            $responses[] = new Response(200, ['Content-Type' => 'application/json'], $responseBody);
        }
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(function (callable $handler): callable {
            return function (RequestInterface $request, array $options) use ($handler) {
                $this->requests[] = $request;
                return $handler($request, $options);
            };
        });

        return CamundaClient::fromConfiguration(
            ConfigResolver::resolve(overrides: ['CAMUNDA_AUTH_STRATEGY' => 'NONE'] + $overrides, environment: []),
            new GuzzleClient(['handler' => $stack, 'http_errors' => false]),
        );
    }

    /** @return array<string, mixed> */
    private function body(): array
    {
        self::assertNotEmpty($this->requests);
        /** @var array<string, mixed> */
        return json_decode((string) $this->requests[0]->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }
}
