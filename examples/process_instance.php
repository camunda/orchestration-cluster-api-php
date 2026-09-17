<?php

/**
 * Compilable usage examples for process instance operations.
 */

declare(strict_types=1);

namespace Camunda\Orchestration\Examples;

use Camunda\Orchestration\Api\Api\ProcessInstanceApi;
use Camunda\Orchestration\Api\Model\CreateProcessInstanceResult;
use Camunda\Orchestration\Api\Model\ProcessInstanceCreationInstructionById;
use Camunda\Orchestration\Api\Model\ProcessInstanceSearchQuery;
use Camunda\Orchestration\Api\Model\ProcessInstanceSearchQueryResult;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Semantic\ProcessDefinitionId;
use Camunda\Orchestration\Semantic\ProcessInstanceKey;

// region CreateProcessInstance
function create_process_instance(CamundaClient $client): void
{
    $api = $client->api(ProcessInstanceApi::class);

    $instruction = (new ProcessInstanceCreationInstructionById())
        ->setProcessDefinitionId(ProcessDefinitionId::of('order-process'))
        ->setVariables(['orderId' => 'ORD-42', 'total' => 99.5]);

    $result = $api->createProcessInstance($instruction);

    if ($result instanceof CreateProcessInstanceResult) {
        echo 'Started instance: ', (string) $result->getProcessInstanceKey(), "\n";
    }
}
// endregion CreateProcessInstance

// region CreateProcessInstanceFromArray
function create_process_instance_from_array(CamundaClient $client): void
{
    $api = $client->api(ProcessInstanceApi::class);

    // Array construction lifts raw strings into their semantic value objects.
    $instruction = new ProcessInstanceCreationInstructionById([
        'processDefinitionId' => 'order-process',
        'variables' => ['orderId' => 'ORD-42'],
    ]);

    $api->createProcessInstance($instruction);
}
// endregion CreateProcessInstanceFromArray

// region CancelProcessInstance
function cancel_process_instance(CamundaClient $client, ProcessInstanceKey $key): void
{
    $api = $client->api(ProcessInstanceApi::class);
    $api->cancelProcessInstance((string) $key);
}
// endregion CancelProcessInstance

// region SearchProcessInstances
function search_process_instances(CamundaClient $client): void
{
    $api = $client->api(ProcessInstanceApi::class);

    $result = $api->searchProcessInstances(new ProcessInstanceSearchQuery());

    if ($result instanceof ProcessInstanceSearchQueryResult) {
        foreach ($result->getItems() as $item) {
            echo (string) $item->getProcessInstanceKey(), "\n";
        }
    }
}
// endregion SearchProcessInstances
