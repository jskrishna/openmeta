<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

final class NullableRule extends Rule
{
    public function __construct()
    {
        parent::__construct('nullable', 'The :attribute field may be null.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        return true;
    }
}
