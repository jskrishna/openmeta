<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Commands;

use OpenMeta\Cli\Contracts\InputInterface;
use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Environment\EnvironmentInspector;
use OpenMeta\Cli\Input\InputDefinition;
use OpenMeta\Cli\Input\OptionDefinition;
use OpenMeta\Cli\Support\ExitCode;

/**
 * `info` — print environment information (add `--json` for machine output).
 */
final class InfoCommand extends Command
{
    protected string $name = 'info';

    protected string $description = 'Show environment information.';

    public function __construct(private readonly EnvironmentInspector $inspector)
    {
    }

    public function definition(): InputDefinition
    {
        return (new InputDefinition())
            ->addOption(new OptionDefinition('json', null, false, 'Output as JSON'));
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $report = $this->inspector->report();

        if ($input->option('json') === true) {
            $output->json($report);

            return ExitCode::SUCCESS;
        }

        $output->table(
            ['Key', 'Value'],
            [
                ['PHP', (string) $report['php']],
                ['OS', (string) $report['os']],
                ['Extensions', (string) $report['extensions']],
            ],
        );

        return ExitCode::SUCCESS;
    }
}
