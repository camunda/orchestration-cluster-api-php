<?php

/**
 * Compilable usage examples for global task-listener operations.
 */

declare(strict_types=1);

namespace Camunda\Orchestration\Examples;

use Camunda\Orchestration\CamundaClient;

// region CreateGlobalTaskListener
/**
 * Create global user task listener.
 */
function create_global_task_listener(CamundaClient $client, \Camunda\Orchestration\Api\Model\CreateGlobalTaskListenerRequest $createGlobalTaskListenerRequest): void
{
    $client->createGlobalTaskListener($createGlobalTaskListenerRequest);
}
// endregion CreateGlobalTaskListener

// region GetGlobalTaskListener
/**
 * Get global user task listener.
 */
function get_global_task_listener(CamundaClient $client, string $id): void
{
    $client->getGlobalTaskListener($id);
}
// endregion GetGlobalTaskListener

// region UpdateGlobalTaskListener
/**
 * Update global user task listener.
 */
function update_global_task_listener(CamundaClient $client, string $id, \Camunda\Orchestration\Api\Model\UpdateGlobalTaskListenerRequest $updateGlobalTaskListenerRequest): void
{
    $client->updateGlobalTaskListener($id, $updateGlobalTaskListenerRequest);
}
// endregion UpdateGlobalTaskListener

// region DeleteGlobalTaskListener
/**
 * Delete global user task listener.
 */
function delete_global_task_listener(CamundaClient $client, string $id): void
{
    $client->deleteGlobalTaskListener($id);
}
// endregion DeleteGlobalTaskListener

// region SearchGlobalTaskListeners
/**
 * Search global user task listeners.
 */
function search_global_task_listeners(CamundaClient $client, ?\Camunda\Orchestration\Api\Model\GlobalTaskListenerSearchQueryRequest $globalTaskListenerSearchQueryRequest = null): void
{
    $client->searchGlobalTaskListeners($globalTaskListenerSearchQueryRequest);
}
// endregion SearchGlobalTaskListeners
