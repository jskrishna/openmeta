<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

final class BetweenRule extends Rule
{
    public function __construct()
    {
        parent::__construct('between', 'The :attribute field must be between :min and :max.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        if (count($parameters) < 2 || ! is_numeric($parameters[0]) || ! is_numeric($parameters[1])) {
            return false;
        }

        $min = (float) $parameters[0];
        $max = (float) $parameters[1];

        if (is_string($value)) {
            $size = mb_strlen($value, 'UTF-8');
        } elseif (is_array($value)) {
            $size = count($value);
        } elseif (is_numeric($value)) {
            $size = (float) $value;
        } else {
            return false;
        }

        return $size >= $min && $size <= $max;
    }
}
