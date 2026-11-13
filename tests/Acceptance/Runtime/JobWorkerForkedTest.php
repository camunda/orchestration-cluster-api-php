<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance\Runtime;

use Camunda\Orchestration\Api\Model\ActivatedJobResult;
use Camunda\Orchestration\Tests\Support\JobFixtures;
use Camunda\Orchestration\Tests\Support\RecordingGuzzle;
use Camunda\Orchestration\Worker\ForkedWorkerSupport;
use Camunda\Orchestration\Worker\JobActionClient;
use Camunda\Orchestration\Worker\JobWorkerOptions;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\RequiresFunction;
use PHPUnit\Framework\TestCase;

/**
 * The forked execution mode of the job worker: every job runs in its own child process, bounded to
 * `maxJobs` concurrent children, and the parent reaps them all before `pollOnce()` returns.
 *
 * Children are real processes, so they report through a marker file rather than shared memory.
 */
#[RequiresFunction('pcntl_fork')]
final class JobWorkerForkedTest extends TestCase
{
    private string $markerFile = '';

    protected function setUp(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'orchestration-forked-');
        self::assertIsString($path);
        $this->markerFile = $path;
    }

    protected function tearDown(): void
    {
        if (is_file($this->markerFile)) {
            unlink($this->markerFile);
        }
    }

    public function testEachJobRunsInItsOwnChildProcessAndTheParentWaitsForAllOfThem(): void
    {
        $http = new RecordingGuzzle();
        $worker = $http->client(
            [JobFixtures::activation('1', '2', '3'), new Response(204), new Response(204), new Response(204)],
        )->createJobWorker(new JobWorkerOptions(type: 'greet', maxJobs: 8, forked: true));
        $parentPid = (int) getmypid();
        $markerFile = $this->markerFile;

        $processed = $worker->pollOnce(static function (ActivatedJobResult $job, JobActionClient $action) use ($markerFile, $parentPid): null {
            file_put_contents(
                $markerFile,
                ForkedWorkerSupport::encodePidMarker((string) getmypid(), $parentPid, (string) $job->getJobKey()) . "\n",
                FILE_APPEND | LOCK_EX,
            );

            return null;
        });

        self::assertSame(3, $processed);
        $markers = $this->readMarkers();
        self::assertCount(3, $markers, 'pollOnce() must not return before every child has finished');
        $childPids = [];
        foreach ($markers as $marker) {
            $pid = ForkedWorkerSupport::validateHandledByPidMarker($marker, $marker['runId'], $parentPid);
            self::assertNotSame((string) $parentPid, $pid);
            $childPids[] = $pid;
        }
        self::assertCount(3, array_unique($childPids), 'one process per job');
        $handledJobs = array_map(static fn (array $m): string => $m['runId'], $markers);
        sort($handledJobs);
        self::assertSame(['1', '2', '3'], $handledJobs);
    }

    public function testMoreJobsThanMaxJobsAreProcessedInWavesAndAllAreReaped(): void
    {
        $http = new RecordingGuzzle();
        $worker = $http->client(
            [JobFixtures::activation('1', '2', '3', '4', '5'), ...array_fill(0, 5, new Response(204))],
        )->createJobWorker(new JobWorkerOptions(type: 'greet', maxJobs: 2, forked: true));
        $parentPid = (int) getmypid();
        $markerFile = $this->markerFile;

        $processed = $worker->pollOnce(static function (ActivatedJobResult $job, JobActionClient $action) use ($markerFile, $parentPid): null {
            file_put_contents(
                $markerFile,
                ForkedWorkerSupport::encodePidMarker((string) getmypid(), $parentPid, (string) $job->getJobKey()) . "\n",
                FILE_APPEND | LOCK_EX,
            );

            return null;
        });

        self::assertSame(5, $processed);
        self::assertCount(5, $this->readMarkers());
    }

    public function testNoMoreThanMaxJobsChildrenRunConcurrently(): void
    {
        $http = new RecordingGuzzle();
        $worker = $http->client(
            [JobFixtures::activation('1', '2', '3', '4', '5'), ...array_fill(0, 5, new Response(204))],
        )->createJobWorker(new JobWorkerOptions(type: 'greet', maxJobs: 2, forked: true));
        $markerFile = $this->markerFile;

        $worker->pollOnce(static function (ActivatedJobResult $job, JobActionClient $action) use ($markerFile): null {
            // Each child records the interval during which it was busy.
            $started = microtime(true);
            usleep(150_000);
            file_put_contents($markerFile, json_encode([$started, microtime(true)]) . "\n", FILE_APPEND | LOCK_EX);

            return null;
        });

        $lines = file($this->markerFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        self::assertIsArray($lines);
        self::assertCount(5, $lines);
        $events = [];
        foreach ($lines as $line) {
            /** @var array{0: float, 1: float} $interval */
            $interval = json_decode($line, true, flags: JSON_THROW_ON_ERROR);
            $events[] = [$interval[0], 1];
            $events[] = [$interval[1], -1];
        }
        // Sort by time; at equal timestamps process the end before the start.
        usort($events, static fn (array $a, array $b): int => [$a[0], $a[1]] <=> [$b[0], $b[1]]);
        $running = 0;
        $peak = 0;
        foreach ($events as [, $delta]) {
            $running += $delta;
            $peak = max($peak, $running);
        }

        self::assertLessThanOrEqual(2, $peak, 'the parent must never have more than maxJobs children alive at once');
    }

    #[RequiresFunction('posix_kill')]
    public function testAChildFailureDoesNotTakeTheParentWorkerDown(): void
    {
        $http = new RecordingGuzzle();
        $worker = $http->client(
            [JobFixtures::activation('1', '2'), new Response(204), new Response(204)],
        )->createJobWorker(new JobWorkerOptions(type: 'greet', maxJobs: 8, forked: true));
        $parentPid = (int) getmypid();
        $markerFile = $this->markerFile;

        $processed = $worker->pollOnce(static function (ActivatedJobResult $job, JobActionClient $action) use ($markerFile, $parentPid): null {
            file_put_contents(
                $markerFile,
                ForkedWorkerSupport::encodePidMarker((string) getmypid(), $parentPid, (string) $job->getJobKey()) . "\n",
                FILE_APPEND | LOCK_EX,
            );
            if ((string) $job->getJobKey() === '1' && getmypid() !== $parentPid) {
                // Kill this child abruptly (no shutdown handlers, no exit status 0).
                posix_kill((int) getmypid(), SIGKILL);
            }

            return null;
        });

        self::assertSame(2, $processed, 'the parent keeps going and reaps the killed child');
        self::assertCount(2, $this->readMarkers());
    }

    /**
     * @return list<array{handledByPid: string, handledByParentPid: string, runId: string}>
     */
    private function readMarkers(): array
    {
        $lines = file($this->markerFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        self::assertIsArray($lines);
        $markers = [];
        foreach ($lines as $line) {
            /** @var array{handledByPid: string, handledByParentPid: string, runId: string} $decoded */
            $decoded = json_decode($line, true, flags: JSON_THROW_ON_ERROR);
            $markers[] = $decoded;
        }

        return $markers;
    }
}
