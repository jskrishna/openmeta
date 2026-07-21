<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

final class BooleanRule extends Rule
{
    public function __construct()
    {
        parent::__construct('boolean', 'The :attribute field must be a boolean.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        return is_bool($value)
            || $value === 0
            || $value === 1
            || $value === '0'
            || $value === '1'
            || $value === 'true'
            || $value === 'false';
    }
}
