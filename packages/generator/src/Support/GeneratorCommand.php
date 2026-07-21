<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Support;

use OpenMeta\Cli\Commands\Command;
use OpenMeta\Cli\Contracts\InputInterface;
use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Input\ArgumentDefinition;
use OpenMeta\Cli\Input\InputDefinition;
use OpenMeta\Cli\Input\OptionDefinition;
use OpenMeta\Cli\Support\ExitCode;
use OpenMeta\Generator\Contracts\GeneratorManagerInterface;
use OpenMeta\Generator\Files\FileStatus;
use OpenMeta\Generator\Manager\FileOutcome;
use OpenMeta\Generator\Manager\GenerationOptions;
use OpenMeta\Generator\Manager\GenerationRequest;

/**
 * Bridges a generator into the console as `make:<key>` — one instance per
 * registered generator, so scaffolding and the CLI compose rather than
 * duplicate.
 */
final class GeneratorCommand extends Command
{
    public function __construct(
        private readonly string $key,
        string $description,
        private readonly GeneratorManagerInterface $manager,
    ) {
        $this->name = 'make:' . $key;
        $this->description = $description;
    }

    public function definition(): InputDefinition
    {
        return (new InputDefinition())
            ->addArgument(new ArgumentDefinition('name', true, 'The artefact name'))
            ->addOption(new OptionDefinition('force', 'f', false, 'Overwrite existing files'))
            ->addOption(new OptionDefinition('dry-run', null, false, 'Preview without writing'));
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = (string) $input->argument('name');

        $options = new GenerationOptions(
            dryRun: $input->option('dry-run') === true,
            force: $input->option('force') === true,
        );

        $result = $this->manager->run(new GenerationRequest($this->key, $name, [], $options));

        foreach ($result->files as $file) {
            $this->report($output, $file);
        }

        return $result->written() === [] && $result->hasSkips() ? ExitCode::FAILURE : ExitCode::SUCCESS;
    }

    private function report(OutputInterface $output, FileOutcome $file): void
    {
        match ($file->status) {
            FileStatus::Created, FileStatus::Merged => $output->success('created: ' . $file->path),
            FileStatus::Previewed => $output->comment('would create: ' . $file->path),
            FileStatus::Skipped => $output->warning(sprintf(
                'skipped: %s (%s)',
                $file->path,
                $file->conflict !== null ? $file->conflict->message : 'conflict',
            )),
        };
    }
}
