<?php

declare(strict_types=1);

namespace OpenMeta\Core\Config;

use OpenMeta\Core\Contracts\ConfigRepositoryInterface;

/**
 * Configuration repository for nested runtime settings.
 *
 * Dot-notation: `app.name`, `database.default`, `api.enabled`.
 */
class ConfigRepository implements ConfigRepositoryInterface
{
    /** @param array<string, mixed> $items */
    public function __construct(private array $items = [])
    {
    }

    /**
     * Load defaults from a config directory, then merge runtime overrides.
     *
     * @param array<string, mixed> $overrides
     */
    public static function load(string $directory, array $overrides = []): self
    {
        $items = ConfigLoader::load($directory);

        if ($overrides !== []) {
            $items = array_replace_recursive($items, $overrides);
        }

        return new self($items);
    }

    public function has(string $key): bool
    {
        return $this->find($key)['exists'];
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $result = $this->find($key);

        return $result['exists'] ? $result['value'] : $default;
    }

    public function set(string $key, mixed $value): void
    {
        $segments = explode('.', $key);
        $cursor = &$this->items;

        foreach ($segments as $index => $segment) {
            if ($index === count($segments) - 1) {
                $cursor[$segment] = $value;

                return;
            }

            if (! isset($cursor[$segment]) || ! is_array($cursor[$segment])) {
                $cursor[$segment] = [];
            }

            $cursor = &$cursor[$segment];
        }
    }

    /**
     * @param array<string, mixed> $items
     */
    public function merge(array $items): void
    {
        $this->items = array_replace_recursive($this->items, $items);
    }

    /** @return array<string, mixed> */
    public function all(): array
    {
        return $this->items;
    }

    /** @return array{exists: bool, value: mixed} */
    private function find(string $key): array
    {
        if ($key === '') {
            return ['exists' => true, 'value' => $this->items];
        }

        $segments = explode('.', $key);
        $cursor = $this->items;

        foreach ($segments as $segment) {
            if (! is_array($cursor) || ! array_key_exists($segment, $cursor)) {
                return ['exists' => false, 'value' => null];
            }

            $cursor = $cursor[$segment];
        }

        return ['exists' => true, 'value' => $cursor];
    }
}
