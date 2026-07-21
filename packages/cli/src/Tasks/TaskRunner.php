<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Tasks;

use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Contracts\TaskInterface;
use OpenMeta\Cli\Contracts\TaskRunnerInterface;
use OpenMeta\Cli\Exceptions\TaskNotFoundException;

/**
 * Registers and runs reusable developer tasks.
 */
final class TaskRunner implements TaskRunnerInterface
{
    /** @var array<string, TaskInterface> */
    private array $tasks = [];

    public function register(TaskInterface $task): void
    {
        $this->tasks[$task->name()] = $task;
    }

    public function has(string $name): bool
    {
        return isset($this->tasks[$name]);
    }

    public function run(string $name, OutputInterface $output): int
    {
        $task = $this->tasks[$name] ?? throw TaskNotFoundException::named($name);

        return $task->run($output);
    }

    public function names(): array
    {
        return array_keys($this->tasks);
    }

    /**
     * @return list<TaskInterface>
     */
    public function all(): array
    {
        return array_values($this->tasks);
    }
}
