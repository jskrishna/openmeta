<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Contracts;

/**
 * Parsed command input: named arguments and options.
 */
interface InputInterface
{
    public function argument(string $name): mixed;

    public function hasArgument(string $name): bool;

    /**
     * @return array<string, mixed>
     */
    public function arguments(): array;

    public function option(string $name): mixed;

    public function hasOption(string $name): bool;

    /**
     * @return array<string, mixed>
     */
    public function options(): array;
}
