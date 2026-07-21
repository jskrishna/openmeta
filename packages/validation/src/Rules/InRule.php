<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

final class InRule extends Rule
{
    public function __construct()
    {
        parent::__construct('in', 'The selected :attribute is invalid.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        if ($parameters === []) {
            return false;
        }

        return in_array((string) $value, $parameters, true)
            || in_array($value, $parameters, true);
    }
}
