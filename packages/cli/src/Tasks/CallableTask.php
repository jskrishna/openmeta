<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Tasks;

use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Contracts\TaskInterface;

/**
 * Adapts a callable into a {@see TaskInterface} (build, test, lint, …).
 */
final class CallableTask implements TaskInterface
{
    /** @var callable(OutputInterface): int */
    private $callback;

    /**
     * @param callable(OutputInterface): int $callback
     */
    public function __construct(
        private readonly string $name,
        private readonly string $description,
        callable $callback,
    ) {
        $this->callback = $callback;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function run(OutputInterface $output): int
    {
        return ($this->callback)($output);
    }
}
