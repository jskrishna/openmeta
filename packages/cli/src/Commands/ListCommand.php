<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Commands;

use OpenMeta\Cli\Contracts\CommandRegistryInterface;
use OpenMeta\Cli\Contracts\InputInterface;
use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Support\ExitCode;

/**
 * `list` — list all registered commands.
 */
final class ListCommand extends Command
{
    protected string $name = 'list';

    protected string $description = 'List available commands.';

    public function __construct(private readonly CommandRegistryInterface $registry)
    {
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $rows = [];

        foreach ($this->registry->descriptions() as $name => $description) {
            $rows[] = [$name, $description];
        }

        $output->table(['Command', 'Description'], $rows);

        return ExitCode::SUCCESS;
    }
}
