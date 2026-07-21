<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Commands;

use OpenMeta\Cli\Contracts\CommandRegistryInterface;
use OpenMeta\Cli\Contracts\InputInterface;
use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Input\ArgumentDefinition;
use OpenMeta\Cli\Input\InputDefinition;
use OpenMeta\Cli\Support\ExitCode;

/**
 * `help <command>` — show a command's arguments and options.
 */
final class HelpCommand extends Command
{
    protected string $name = 'help';

    protected string $description = 'Show help for a command.';

    public function __construct(private readonly CommandRegistryInterface $registry)
    {
    }

    public function definition(): InputDefinition
    {
        return (new InputDefinition())
            ->addArgument(new ArgumentDefinition('command', true, 'The command name'));
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = (string) $input->argument('command');

        if (! $this->registry->has($name)) {
            $output->error(sprintf('Unknown command [%s].', $name));

            return ExitCode::INVALID;
        }

        $command = $this->registry->get($name);
        $output->writeln($command->name() . ' — ' . $command->description());

        $definition = $command->definition();

        if ($definition->arguments() !== []) {
            $output->writeln('');
            $output->comment('Arguments:');
            foreach ($definition->arguments() as $argument) {
                $output->writeln(sprintf('  %-16s %s', $argument->name, $argument->description));
            }
        }

        if ($definition->options() !== []) {
            $output->writeln('');
            $output->comment('Options:');
            foreach ($definition->options() as $option) {
                $output->writeln(sprintf('  --%-14s %s', $option->name, $option->description));
            }
        }

        return ExitCode::SUCCESS;
    }
}
