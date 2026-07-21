<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Contracts;

use OpenMeta\Cli\Exceptions\TaskNotFoundException;

/**
 * Registers and runs developer tasks.
 */
interface TaskRunnerInterface
{
    public function register(TaskInterface $task): void;

    public function has(string $name): bool;

    /**
     * @throws TaskNotFoundException
     */
    public function run(string $name, OutputInterface $output): int;

    /**
     * @return list<string>
     */
    public function names(): array;
}
