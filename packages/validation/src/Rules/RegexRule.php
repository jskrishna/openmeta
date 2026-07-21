<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

final class RegexRule extends Rule
{
    public function __construct()
    {
        parent::__construct('regex', 'The :attribute field format is invalid.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        if ($parameters === [] || ! is_string($value)) {
            return false;
        }

        $pattern = $parameters[0];

        if (@preg_match($pattern, '') === false) {
            return false;
        }

        return preg_match($pattern, $value) === 1;
    }
}
