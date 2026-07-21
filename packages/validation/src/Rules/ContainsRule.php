<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

final class ContainsRule extends Rule
{
    public function __construct()
    {
        parent::__construct('contains', 'The :attribute field must contain :contains.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        if ($parameters === []) {
            return false;
        }

        $needle = $parameters[0];

        if (is_string($value)) {
            return str_contains($value, $needle);
        }

        if (is_array($value)) {
            return in_array($needle, $value, false);
        }

        return false;
    }
}
