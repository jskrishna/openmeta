<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Configuration;

use OpenMeta\Support\Arr\Arr;

/**
 * Immutable CLI configuration bag with dot-path access.
 */
final class Configuration
{
    /**
     * @param array<string, mixed> $items
     */
    public function __construct(private readonly array $items = [])
    {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->items, $key, $default);
    }

    public function has(string $key): bool
    {
        return Arr::get($this->items, $key) !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->items;
    }
}
