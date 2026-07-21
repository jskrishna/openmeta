<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Application;

use OpenMeta\Cli\Contracts\CommandInterface;
use OpenMeta\Cli\Contracts\CommandRegistryInterface;
use OpenMeta\Cli\Contracts\ConsoleApplicationInterface;
use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Events\CommandFailed;
use OpenMeta\Cli\Events\CommandFinished;
use OpenMeta\Cli\Events\CommandStarted;
use OpenMeta\Cli\Exceptions\InvalidInputException;
use OpenMeta\Cli\Input\InputParser;
use OpenMeta\Cli\Support\ConsoleErrorHandler;
use OpenMeta\Cli\Support\ExitCode;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use Throwable;

/**
 * The console kernel: resolves a command by name, binds input, dispatches
 * lifecycle events, and maps outcomes to exit codes.
 *
 * Boot flow: argv → command lookup → input parse → started → execute →
 * finished / failed.
 */
final class ConsoleApplication implements ConsoleApplicationInterface
{
    private const DEFAULT_COMMAND = 'list';

    private readonly InputParser $parser;

    private readonly ConsoleErrorHandler $errors;

    public function __construct(
        private readonly CommandRegistryInterface $registry,
        private readonly OutputInterface $output,
        private readonly EventDispatcherInterface $events,
        ?InputParser $parser = null,
        ?ConsoleErrorHandler $errors = null,
    ) {
        $this->parser = $parser ?? new InputParser();
        $this->errors = $errors ?? new ConsoleErrorHandler();
    }

    public function register(CommandInterface $command): void
    {
        $this->registry->register($command);
    }

    public function registry(): CommandRegistryInterface
    {
        return $this->registry;
    }

    public function run(array $argv): int
    {
        $name = $argv[0] ?? self::DEFAULT_COMMAND;
        $rest = array_values(array_slice($argv, 1));

        if (! $this->registry->has($name)) {
            $this->output->error(sprintf('Command [%s] is not registered. Run "list".', $name));

            return ExitCode::INVALID;
        }

        $command = $this->registry->get($name);

        try {
            $input = $this->parser->parse($rest, $command->definition());
        } catch (InvalidInputException $exception) {
            $this->output->error($exception->getMessage());

            return ExitCode::INVALID;
        }

        $this->events->dispatch(new CommandStarted($name, $input->arguments(), $input->options()));

        try {
            $exitCode = $command->execute($input, $this->output);
            $this->events->dispatch(new CommandFinished($name, $exitCode));

            return $exitCode;
        } catch (Throwable $exception) {
            $this->events->dispatch(new CommandFailed($name, $exception));
            $this->errors->render($exception, $this->output);

            return ExitCode::FAILURE;
        }
    }
}
