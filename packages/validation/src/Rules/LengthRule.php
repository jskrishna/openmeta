<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

final class LengthRule extends Rule
{
    public function __construct()
    {
        parent::__construct('length', 'The :attribute field must be :length characters.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        if ($parameters === [] || ! is_numeric($parameters[0])) {
            return false;
        }

        $length = (int) $parameters[0];

        if (is_string($value)) {
            return mb_strlen($value, 'UTF-8') === $length;
        }

        if (is_array($value)) {
            return count($value) === $length;
        }

        return false;
    }
}
