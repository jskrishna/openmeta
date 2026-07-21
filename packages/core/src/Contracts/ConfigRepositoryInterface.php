<?php

declare(strict_types=1);

namespace OpenMeta\Core\Contracts;

/**
 * Nested configuration store (dot-notation get / set / has).
 */
interface ConfigRepositoryInterface
{
    public function has(string $key): bool;

    public function get(string $key, mixed $default = null): mixed;

    public function set(string $key, mixed $value): void;

    /**
     * @return array<string, mixed>
     */
    public function all(): array;
}
