<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

final class ArrayRule extends Rule
{
    public function __construct()
    {
        parent::__construct('array', 'The :attribute field must be an array.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        return is_array($value);
    }
}
