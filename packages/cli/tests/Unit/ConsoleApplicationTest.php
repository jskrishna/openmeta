<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Tests\Unit;

use OpenMeta\Cli\Events\CommandFailed;
use OpenMeta\Cli\Events\CommandFinished;
use OpenMeta\Cli\Events\CommandStarted;
use OpenMeta\Cli\Input\ArgumentDefinition;
use OpenMeta\Cli\Input\InputDefinition;
use OpenMeta\Cli\Support\ExitCode;
use OpenMeta\Cli\Tests\CliTestCase;
use OpenMeta\Cli\Tests\Fixtures\RecordingCommand;
use RuntimeException;

final class ConsoleApplicationTest extends CliTestCase
{
    public function test_runs_command_and_dispatches_lifecycle_events(): void
    {
        $command = new RecordingCommand('greet');
        $this->app->register($command);

        /** @var list<object> $started */
        $started = [];
        /** @var list<object> $finished */
        $finished = [];
        $this->events->listen(CommandStarted::class, function (object $e) use (&$started): void {
            $started[] = $e;
        });
        $this->events->listen(CommandFinished::class, function (object $e) use (&$finished): void {
            $finished[] = $e;
        });

        $exit = $this->app->run(['greet']);

        self::assertSame(ExitCode::SUCCESS, $exit);
        self::assertTrue($command->ran);
        self::assertCount(1, $started);
        self::assertCount(1, $finished);
        self::assertStringContainsString('ran greet', $this->output->content());
    }

    public function test_unknown_command_returns_invalid(): void
    {
        self::assertSame(ExitCode::INVALID, $this->app->run(['nope']));
        self::assertStringContainsString('not registered', $this->output->content());
    }

    public function test_defaults_to_list_when_no_command_given(): void
    {
        $this->app->register(new RecordingCommand('list'));

        self::assertSame(ExitCode::SUCCESS, $this->app->run([]));
    }

    public function test_invalid_input_returns_invalid(): void
    {
        $definition = (new InputDefinition())->addArgument(new ArgumentDefinition('name', true));
        $this->app->register(new RecordingCommand('need-arg', ExitCode::SUCCESS, $definition));

        self::assertSame(ExitCode::INVALID, $this->app->run(['need-arg']));
    }

    public function test_thrown_command_fails_and_emits_failed_event(): void
    {
        $this->app->register(new RecordingCommand('boom', ExitCode::SUCCESS, null, new RuntimeException('kaboom')));

        /** @var list<object> $failed */
        $failed = [];
        $this->events->listen(CommandFailed::class, function (object $e) use (&$failed): void {
            $failed[] = $e;
        });

        $exit = $this->app->run(['boom']);

        self::assertSame(ExitCode::FAILURE, $exit);
        self::assertCount(1, $failed);
        self::assertStringContainsString('kaboom', $this->output->content());
    }
}
