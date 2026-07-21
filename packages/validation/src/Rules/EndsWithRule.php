<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

final class EndsWithRule extends Rule
{
    public function __construct()
    {
        parent::__construct('ends_with', 'The :attribute field must end with one of the following: :values.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        if (! is_string($value) || $parameters === []) {
            return false;
        }

        foreach ($parameters as $suffix) {
            if (str_ends_with($value, $suffix)) {
                return true;
            }
        }

        return false;
    }
}
