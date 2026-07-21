<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Input;

/**
 * The set of arguments and options a command accepts.
 */
final class InputDefinition
{
    /** @var list<ArgumentDefinition> */
    private array $arguments = [];

    /** @var array<string, OptionDefinition> */
    private array $options = [];

    public function addArgument(ArgumentDefinition $argument): self
    {
        $this->arguments[] = $argument;

        return $this;
    }

    public function addOption(OptionDefinition $option): self
    {
        $this->options[$option->name] = $option;

        return $this;
    }

    /**
     * @return list<ArgumentDefinition>
     */
    public function arguments(): array
    {
        return $this->arguments;
    }

    /**
     * @return list<OptionDefinition>
     */
    public function options(): array
    {
        return array_values($this->options);
    }

    public function option(string $name): ?OptionDefinition
    {
        return $this->options[$name] ?? null;
    }

    public function hasOption(string $name): bool
    {
        return isset($this->options[$name]);
    }

    public function optionByShortcut(string $shortcut): ?OptionDefinition
    {
        foreach ($this->options as $option) {
            if ($option->shortcut === $shortcut) {
                return $option;
            }
        }

        return null;
    }
}
