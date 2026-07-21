<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

final class ObjectRule extends Rule
{
    public function __construct()
    {
        parent::__construct('object', 'The :attribute field must be an object.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        return is_object($value);
    }
}
