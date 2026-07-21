<?php

declare(strict_types=1);

namespace OpenMeta\Security\Sanitization;

use OpenMeta\Security\Contracts\SanitizerInterface;

/**
 * Injectable sanitizer delegating to {@see Sanitizer} static helpers.
 */
final class DefaultSanitizer implements SanitizerInterface
{
    public function text(mixed $value): string
    {
        return Sanitizer::text($value);
    }

    public function email(mixed $value): string
    {
        return Sanitizer::email($value);
    }

    public function url(mixed $value): string
    {
        return Sanitizer::url($value);
    }

    public function int(mixed $value, int $default = 0): int
    {
        return Sanitizer::int($value, $default);
    }

    public function float(mixed $value, float $default = 0.0): float
    {
        return Sanitizer::float($value, $default);
    }

    public function bool(mixed $value): bool
    {
        return Sanitizer::bool($value);
    }

    public function array(array $value, callable $itemSanitizer): array
    {
        return Sanitizer::array($value, $itemSanitizer);
    }
}
