<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

final class StartsWithRule extends Rule
{
    public function __construct()
    {
        parent::__construct('starts_with', 'The :attribute field must start with one of the following: :values.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        if (! is_string($value) || $parameters === []) {
            return false;
        }

        foreach ($parameters as $prefix) {
            if (str_starts_with($value, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
