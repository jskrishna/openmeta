<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Contracts;

/**
 * A reusable developer task (build, test, lint, docs, release, …).
 */
interface TaskInterface
{
    public function name(): string;

    public function description(): string;

    public function run(OutputInterface $output): int;
}
