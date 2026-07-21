<?php

declare(strict_types=1);

namespace OpenMeta\Security\Contracts;

/**
 * Inbound sanitization API.
 */
interface SanitizerInterface
{
    public function text(mixed $value): string;

    public function email(mixed $value): string;

    public function url(mixed $value): string;

    public function int(mixed $value, int $default = 0): int;

    public function float(mixed $value, float $default = 0.0): float;

    public function bool(mixed $value): bool;

    /**
     * @param array<array-key, mixed> $value
     * @return array<array-key, mixed>
     */
    public function array(array $value, callable $itemSanitizer): array;
}
