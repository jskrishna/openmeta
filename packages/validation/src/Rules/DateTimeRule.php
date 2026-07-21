<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

use DateTimeImmutable;
use Exception;

final class DateTimeRule extends Rule
{
    public function __construct()
    {
        parent::__construct('datetime', 'The :attribute field must be a valid datetime.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        if ($value instanceof DateTimeImmutable) {
            return true;
        }

        if (! is_string($value) || $value === '') {
            return false;
        }

        if ($parameters !== []) {
            $format = $parameters[0];
            $date = DateTimeImmutable::createFromFormat('!' . $format, $value);

            return $date !== false && $date->format($format) === $value;
        }

        try {
            new DateTimeImmutable($value);

            return true;
        } catch (Exception) {
            return false;
        }
    }
}
