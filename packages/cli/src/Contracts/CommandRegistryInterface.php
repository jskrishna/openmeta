<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Contracts;

use OpenMeta\Cli\Exceptions\CommandNotFoundException;

/**
 * Stores commands, supporting lazy registration for fast boot.
 */
interface CommandRegistryInterface
{
    public function register(CommandInterface $command): void;

    /**
     * Register a factory resolved only when the command is first needed.
     *
     * @param callable(): CommandInterface $factory
     */
    public function registerLazy(string $name, string $description, callable $factory): void;

    public function has(string $name): bool;

    /**
     * @throws CommandNotFoundException
     */
    public function get(string $name): CommandInterface;

    /**
     * @return list<string>
     */
    public function names(): array;

    /**
     * Name => description, without forcing lazy commands to resolve.
     *
     * @return array<string, string>
     */
    public function descriptions(): array;
}
