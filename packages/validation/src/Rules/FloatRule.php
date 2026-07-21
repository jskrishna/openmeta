<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

final class FloatRule extends Rule
{
    public function __construct()
    {
        parent::__construct('float', 'The :attribute field must be a float.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        if (is_float($value)) {
            return true;
        }

        if (is_int($value)) {
            return true;
        }

        return is_string($value) && is_numeric($value);
    }
}
