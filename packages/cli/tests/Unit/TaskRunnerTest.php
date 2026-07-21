<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Tests\Unit;

use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Exceptions\TaskNotFoundException;
use OpenMeta\Cli\Output\BufferedOutput;
use OpenMeta\Cli\Output\OutputFormatter;
use OpenMeta\Cli\Support\ExitCode;
use OpenMeta\Cli\Tasks\CallableTask;
use OpenMeta\Cli\Tasks\TaskRunner;
use PHPUnit\Framework\TestCase;

final class TaskRunnerTest extends TestCase
{
    public function test_registers_and_runs_a_task(): void
    {
        $runner = new TaskRunner();
        $runner->register(new CallableTask('build', 'Build assets', static function (OutputInterface $output): int {
            $output->writeln('built');

            return ExitCode::SUCCESS;
        }));

        $output = new BufferedOutput(new OutputFormatter(false));

        self::assertTrue($runner->has('build'));
        self::assertSame(ExitCode::SUCCESS, $runner->run('build', $output));
        self::assertStringContainsString('built', $output->content());
        self::assertSame(['build'], $runner->names());
    }

    public function test_unknown_task_throws(): void
    {
        $this->expectException(TaskNotFoundException::class);
        (new TaskRunner())->run('nope', new BufferedOutput(new OutputFormatter(false)));
    }
}
