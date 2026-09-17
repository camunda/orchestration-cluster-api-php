<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Examples\Advanced\MessageCorrelation;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/internal/ExampleSupport.php';

use Camunda\Orchestration\Api\ApiException;
use Camunda\Orchestration\Api\Model\MessagePublicationRequest;
use Camunda\Orchestration\Api\Model\ProblemDetail;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Examples\Advanced\Internal\ExampleSupport;
use Camunda\Orchestration\Semantic\ProcessInstanceKey;

function run(): void
{
    $client = ExampleSupport::client();
    $runId = ExampleSupport::runId();
    $processId = 'php-sdk-payment-' . $runId;
    $messageName = 'payment-received-' . $runId;
    $resource = ExampleSupport::renderBpmn(__DIR__ . '/payment.bpmn', [
        '__PROCESS_ID__' => $processId,
        '__MESSAGE_NAME__' => $messageName,
    ]);
    /** @var list<ProcessInstanceKey> $instances */
    $instances = [];
    $completed = false;

    try {
        ExampleSupport::deploy($client, $resource);
        for ($index = 1; $index <= 3; ++$index) {
            $orderId = "payment-$index-$runId";
            $instances[] = ExampleSupport::startProcess($client, $processId, [
                'orderId' => $orderId,
                'amount' => 49.90,
            ]);

            $messageId = 'payment-provider-event-' . $orderId;
            publishPayment($client, $messageName, $orderId, $messageId);
            publishPayment($client, $messageName, $orderId, $messageId);
        }

        foreach ($instances as $instance) {
            ExampleSupport::waitForCompletion($client, $instance);
        }
        $completed = true;
        printf("Correlated and completed %d payment processes.\n", count($instances));
    } finally {
        if (!$completed) {
            foreach ($instances as $instance) {
                ExampleSupport::cancelIfActive($client, $instance);
            }
        }
        if (is_file($resource)) {
            unlink($resource);
        }
    }
}

function publishPayment(
    CamundaClient $client,
    string $messageName,
    string $orderId,
    string $messageId,
): void {
    $request = (new MessagePublicationRequest())
        ->setName($messageName)
        ->setCorrelationKey($orderId)
        ->setMessageId($messageId)
        ->setTimeToLive(60_000)
        ->setVariables([
            'paymentStatus' => 'captured',
            'paymentId' => 'payment-' . $orderId,
        ]);
    try {
        $result = $client->publishMessage($request);
    } catch (ApiException $error) {
        if ($error->getCode() === 409) {
            return;
        }

        throw new \RuntimeException("Message publication failed for $orderId: {$error->getMessage()}", 0, $error);
    }

    if ($result instanceof ProblemDetail && $result->getStatus() !== 409) {
        throw new \RuntimeException(
            "Message publication failed for $orderId: {$result->getDetail()}",
        );
    }
}

try {
    run();
} catch (\Throwable $error) {
    fwrite(STDERR, "Message-correlation example failed: {$error->getMessage()}\n");
    exit(1);
}
