<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Commands;

use OpenMeta\Cli\Contracts\InputInterface;
use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Environment\EnvironmentCheck;
use OpenMeta\Cli\Environment\EnvironmentInspector;
use OpenMeta\Cli\Support\ExitCode;

/**
 * `doctor` — run environment diagnostics; non-zero exit if any check fails.
 */
final class DoctorCommand extends Command
{
    protected string $name = 'doctor';

    protected string $description = 'Diagnose the development environment.';

    public function __construct(private readonly EnvironmentInspector $inspector)
    {
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $checks = $this->inspector->checks();

        $rows = array_map(
            static fn (EnvironmentCheck $check): array => [
                $check->name,
                $check->passed ? 'OK' : 'FAIL',
                $check->message,
            ],
            $checks,
        );

        $output->table(['Check', 'Status', 'Detail'], $rows);

        $failed = count(array_filter($checks, static fn (EnvironmentCheck $check): bool => ! $check->passed));

        if ($failed > 0) {
            $output->error(sprintf('%d check(s) failed.', $failed));

            return ExitCode::FAILURE;
        }

        $output->success('All checks passed.');

        return ExitCode::SUCCESS;
    }
}
