<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Commands;

use OpenMeta\Cli\Contracts\CommandInterface;
use OpenMeta\Cli\Input\InputDefinition;
use OpenMeta\Validation\Validation;

/**
 * Base command: name/description metadata, an overridable input definition,
 * and a Validation-backed input validator (reusing @openmeta/validation).
 */
abstract class Command implements CommandInterface
{
    protected string $name = '';

    protected string $description = '';

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function definition(): InputDefinition
    {
        return new InputDefinition();
    }

    /**
     * Validate argument/option data through the Validation package.
     *
     * @param array<string, mixed>        $data
     * @param array<string, list<string>> $rules
     *
     * @return array<string, list<string>> attribute => messages (empty when valid)
     */
    protected function validateInput(array $data, array $rules): array
    {
        if ($rules === []) {
            return [];
        }

        $result = Validation::make($data, $rules)->result();

        return $result->passed() ? [] : $result->errors()->all();
    }
}
