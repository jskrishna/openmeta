<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Commands;

use OpenMeta\Cli\Contracts\InputInterface;
use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Support\ExitCode;
use OpenMeta\Core\Application\Application;

/**
 * `version` — print the framework version.
 */
final class VersionCommand extends Command
{
    protected string $name = 'version';

    protected string $description = 'Show the OpenMeta framework version.';

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->success('OpenMeta ' . Application::VERSION);

        return ExitCode::SUCCESS;
    }
}
