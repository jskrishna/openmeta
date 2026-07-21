<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

use DateTimeImmutable;

final class DateRule extends Rule
{
    public function __construct()
    {
        parent::__construct('date', 'The :attribute field must be a valid date.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        if (! is_string($value) || $value === '') {
            return false;
        }

        $format = $parameters[0] ?? 'Y-m-d';
        $date = DateTimeImmutable::createFromFormat('!' . $format, $value);

        return $date !== false && $date->format($format) === $value;
    }
}
