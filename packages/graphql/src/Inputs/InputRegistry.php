<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Inputs;

use OpenMeta\GraphQL\Errors\DuplicateTypeException;
use OpenMeta\GraphQL\Errors\TypeNotFoundException;

/**
 * Registry of input object types.
 */
final class InputRegistry
{
    /** @var array<string, InputType> */
    private array $inputs = [];

    public function register(InputType $input): void
    {
        if (isset($this->inputs[$input->name])) {
            throw DuplicateTypeException::named('Input type', $input->name);
        }

        $this->inputs[$input->name] = $input;
    }

    public function has(string $name): bool
    {
        return isset($this->inputs[$name]);
    }

    public function get(string $name): InputType
    {
        return $this->inputs[$name] ?? throw TypeNotFoundException::named($name);
    }

    /**
     * @return list<InputType>
     */
    public function all(): array
    {
        return array_values($this->inputs);
    }
}
