<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Input;

use OpenMeta\Cli\Contracts\InputInterface;

/**
 * A concrete, already-bound input: named arguments and options.
 */
final class Input implements InputInterface
{
    /**
     * @param array<string, mixed> $arguments
     * @param array<string, mixed> $options
     */
    public function __construct(
        private readonly array $arguments = [],
        private readonly array $options = [],
    ) {
    }

    public function argument(string $name): mixed
    {
        return $this->arguments[$name] ?? null;
    }

    public function hasArgument(string $name): bool
    {
        return array_key_exists($name, $this->arguments);
    }

    public function arguments(): array
    {
        return $this->arguments;
    }

    public function option(string $name): mixed
    {
        return $this->options[$name] ?? null;
    }

    public function hasOption(string $name): bool
    {
        return array_key_exists($name, $this->options);
    }

    public function options(): array
    {
        return $this->options;
    }
}
