<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Cli;

use OpenMeta\Cli\Commands\Command;
use OpenMeta\Cli\Contracts\InputInterface;
use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Support\ExitCode;
use OpenMeta\Docgen\Manager\DocumentationManager;

/**
 * `docs:build` — run the full pipeline: API, packages, search index, sitemap,
 * and changelog.
 */
final class DocsBuildCommand extends Command
{
    protected string $name = 'docs:build';

    protected string $description = 'Generate all documentation artefacts.';

    public function __construct(private readonly DocumentationManager $docs)
    {
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $written = $this->docs->build();

        $output->success(sprintf('%d documentation artefact(s) generated.', count($written)));

        return ExitCode::SUCCESS;
    }
}
