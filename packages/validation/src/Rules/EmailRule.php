<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

final class EmailRule extends Rule
{
    public function __construct()
    {
        parent::__construct('email', 'The :attribute field must be a valid email address.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        return is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}
