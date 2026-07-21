<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

final class MaxRule extends Rule
{
    public function __construct()
    {
        parent::__construct('max', 'The :attribute field must not be greater than :max.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        if ($parameters === [] || ! is_numeric($parameters[0])) {
            return false;
        }

        $max = (float) $parameters[0];

        if (is_string($value)) {
            return mb_strlen($value, 'UTF-8') <= $max;
        }

        if (is_array($value)) {
            return count($value) <= $max;
        }

        if (is_numeric($value)) {
            return (float) $value <= $max;
        }

        return false;
    }
}
