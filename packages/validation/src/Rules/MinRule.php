<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

final class MinRule extends Rule
{
    public function __construct()
    {
        parent::__construct('min', 'The :attribute field must be at least :min.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        if ($parameters === [] || ! is_numeric($parameters[0])) {
            return false;
        }

        $min = (float) $parameters[0];

        if (is_string($value)) {
            return mb_strlen($value, 'UTF-8') >= $min;
        }

        if (is_array($value)) {
            return count($value) >= $min;
        }

        if (is_numeric($value)) {
            return (float) $value >= $min;
        }

        return false;
    }
}
