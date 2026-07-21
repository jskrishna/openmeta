<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

final class IntegerRule extends Rule
{
    public function __construct()
    {
        parent::__construct('integer', 'The :attribute field must be an integer.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        return is_int($value) || (is_string($value) && ctype_digit(ltrim($value, '-')));
    }
}
