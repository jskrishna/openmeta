<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

use OpenMeta\Support\Arr\Arr;

/**
 * Conditionally required when another attribute equals a given value.
 *
 * Syntax: required_if:other_field,expected_value
 */
final class RequiredIfRule extends Rule
{
    public function __construct()
    {
        parent::__construct('required_if', 'The :attribute field is required when :other is :value.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        if (count($parameters) < 2) {
            return false;
        }

        $otherField = $parameters[0];
        $expected = implode(',', array_slice($parameters, 1));
        $otherValue = Arr::get($data, $otherField);

        if ((string) $otherValue !== $expected) {
            return true;
        }

        return (new RequiredRule())->passes($attribute, $value, [], $data);
    }
}
