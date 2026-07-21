<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Registry;

use OpenMeta\Cli\Contracts\CommandInterface;
use OpenMeta\Cli\Contracts\CommandRegistryInterface;
use OpenMeta\Cli\Exceptions\CommandNotFoundException;

/**
 * Stores commands eagerly or lazily; lazy commands resolve on first use.
 */
final class CommandRegistry implements CommandRegistryInterface
{
    /** @var array<string, CommandInterface> */
    private array $commands = [];

    /** @var array<string, callable(): CommandInterface> */
    private array $factories = [];

    /** @var array<string, string> */
    private array $descriptions = [];

    public function register(CommandInterface $command): void
    {
        $this->commands[$command->name()] = $command;
        $this->descriptions[$command->name()] = $command->description();
    }

    public function registerLazy(string $name, string $description, callable $factory): void
    {
        $this->factories[$name] = $factory;
        $this->descriptions[$name] = $description;
    }

    public function has(string $name): bool
    {
        return isset($this->commands[$name]) || isset($this->factories[$name]);
    }

    public function get(string $name): CommandInterface
    {
        if (isset($this->commands[$name])) {
            return $this->commands[$name];
        }

        if (isset($this->factories[$name])) {
            $command = ($this->factories[$name])();
            $this->commands[$name] = $command;
            unset($this->factories[$name]);

            return $command;
        }

        throw CommandNotFoundException::named($name);
    }

    public function names(): array
    {
        $names = array_values(array_unique([...array_keys($this->commands), ...array_keys($this->factories)]));
        sort($names);

        return $names;
    }

    public function descriptions(): array
    {
        ksort($this->descriptions);

        return $this->descriptions;
    }
}
