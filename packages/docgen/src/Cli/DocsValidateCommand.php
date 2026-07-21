<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Cli;

use OpenMeta\Cli\Commands\Command;
use OpenMeta\Cli\Contracts\InputInterface;
use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Support\ExitCode;
use OpenMeta\Docgen\Manager\DocumentationManager;

/**
 * `docs:validate` — check the docs tree for broken links and markdown issues.
 */
final class DocsValidateCommand extends Command
{
    protected string $name = 'docs:validate';

    protected string $description = 'Validate documentation (links, markdown, structure).';

    public function __construct(private readonly DocumentationManager $docs)
    {
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $report = $this->docs->validate();

        if ($report->isClean()) {
            $output->success('Documentation is valid.');

            return ExitCode::SUCCESS;
        }

        foreach ($report->issues() as $issue) {
            $output->warning((string) $issue);
        }

        $output->error(sprintf('%d documentation issue(s) found.', $report->count()));

        return ExitCode::FAILURE;
    }
}
