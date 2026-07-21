<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Tests\Fixtures;

use OpenMeta\Cli\Commands\Command;
use OpenMeta\Cli\Contracts\InputInterface;
use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Input\InputDefinition;
use OpenMeta\Cli\Support\ExitCode;
use Throwable;

/**
 * A command that records its invocation, for application/registry tests.
 */
final class RecordingCommand extends Command
{
    public bool $ran = false;

    public ?InputInterface $received = null;

    private readonly InputDefinition $inputDefinition;

    public function __construct(
        string $name,
        private readonly int $exitCode = ExitCode::SUCCESS,
        ?InputDefinition $definition = null,
        private readonly ?Throwable $throw = null,
    ) {
        $this->name = $name;
        $this->description = 'Test command ' . $name;
        $this->inputDefinition = $definition ?? new InputDefinition();
    }

    public function definition(): InputDefinition
    {
        return $this->inputDefinition;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->ran = true;
        $this->received = $input;

        if ($this->throw !== null) {
            throw $this->throw;
        }

        $output->writeln('ran ' . $this->name);

        return $this->exitCode;
    }
}
