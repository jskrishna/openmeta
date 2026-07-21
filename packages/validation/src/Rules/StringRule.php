<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

final class StringRule extends Rule
{
    public function __construct()
    {
        parent::__construct('string', 'The :attribute field must be a string.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        return is_string($value);
    }
}
