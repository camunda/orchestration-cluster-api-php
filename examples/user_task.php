<?php

/**
 * Compilable usage examples for user-task operations.
 */

declare(strict_types=1);

namespace Camunda\Orchestration\Examples;

use Camunda\Orchestration\Api\Api\UserTaskApi;
use Camunda\Orchestration\Api\Model\UserTaskAssignmentRequest;
use Camunda\Orchestration\Api\Model\UserTaskCompletionRequest;
use Camunda\Orchestration\CamundaClient;
use Camunda\Orchestration\Semantic\UserTaskKey;

// region CompleteUserTask
function complete_user_task(CamundaClient $client, UserTaskKey $key): void
{
    $api = $client->api(UserTaskApi::class);

    $request = (new UserTaskCompletionRequest())
        ->setVariables(['approved' => true]);

    $api->completeUserTask((string) $key, $request);
}
// endregion CompleteUserTask

// region AssignUserTask
function assign_user_task(CamundaClient $client, UserTaskKey $key): void
{
    $api = $client->api(UserTaskApi::class);

    $request = (new UserTaskAssignmentRequest())
        ->setAssignee('demo');

    $api->assignUserTask((string) $key, $request);
}
// endregion AssignUserTask
