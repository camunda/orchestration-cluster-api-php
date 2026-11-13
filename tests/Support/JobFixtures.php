<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Support;

use GuzzleHttp\Psr7\Response;

/**
 * Wire-format fixtures for the job endpoints.
 */
final class JobFixtures
{
    /**
     * A `200` job-activation response carrying one job per key.
     */
    public static function activation(string ...$jobKeys): Response
    {
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            (string) json_encode(['jobs' => array_map(self::job(...), array_values($jobKeys))]),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public static function job(string $jobKey): array
    {
        return [
            'type' => 'greet',
            'processDefinitionId' => 'greet-process',
            'processDefinitionVersion' => 1,
            'elementId' => 'greet-task',
            'customHeaders' => [],
            'worker' => 'php-test-worker',
            'retries' => 3,
            'deadline' => 1_789_636_204_728,
            'variables' => ['name' => "job-$jobKey"],
            'tenantId' => '<default>',
            'jobKey' => $jobKey,
            'processInstanceKey' => '2251799813686194',
            'processDefinitionKey' => '2251799813685676',
            'elementInstanceKey' => '2251799813686199',
            'kind' => 'BPMN_ELEMENT',
            'listenerEventType' => 'UNSPECIFIED',
            'userTask' => null,
            'tags' => [],
            'rootProcessInstanceKey' => '2251799813686194',
        ];
    }

    public static function problem(int $status, string $title = '', string $detail = ''): Response
    {
        $body = ['status' => $status];
        if ($title !== '') {
            $body['title'] = $title;
        }
        if ($detail !== '') {
            $body['detail'] = $detail;
        }

        return new Response($status, ['Content-Type' => 'application/problem+json'], (string) json_encode($body));
    }
}
