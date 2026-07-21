<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

use OpenMeta\Support\Uuid\Uuid;

final class UuidRule extends Rule
{
    public function __construct()
    {
        parent::__construct('uuid', 'The :attribute field must be a valid UUID.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        return is_string($value) && Uuid::isValid($value);
    }
}
