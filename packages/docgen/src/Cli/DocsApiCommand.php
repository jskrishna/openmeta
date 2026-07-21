<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Cli;

use OpenMeta\Cli\Commands\Command;
use OpenMeta\Cli\Contracts\InputInterface;
use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Support\ExitCode;
use OpenMeta\Docgen\Manager\DocumentationManager;

/**
 * `docs:api` — generate the API reference from public PHP types.
 */
final class DocsApiCommand extends Command
{
    protected string $name = 'docs:api';

    protected string $description = 'Generate API documentation from public types.';

    public function __construct(private readonly DocumentationManager $docs)
    {
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $written = $this->docs->generateApi();

        foreach ($written as $path) {
            $output->comment('generated: ' . $path);
        }

        $output->success(sprintf('%d API page(s) generated.', count($written)));

        return ExitCode::SUCCESS;
    }
}
