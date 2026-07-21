<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

final class RequiredRule extends Rule
{
    public function __construct()
    {
        parent::__construct('required', 'The :attribute field is required.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        if ($value === null) {
            return false;
        }

        if (is_string($value) && trim($value) === '') {
            return false;
        }

        if (is_array($value) && $value === []) {
            return false;
        }

        return true;
    }
}
