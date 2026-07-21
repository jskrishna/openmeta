<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Settings;

use OpenMeta\Security\Sanitization\Sanitizer;

/**
 * In-memory / injectable settings store (WP options bridge later).
 */
final class SettingsStore
{
    /** @param array<string, mixed> $values */
    public function __construct(private array $values = [])
    {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->values[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->values[$key] = is_string($value) ? Sanitizer::text($value) : $value;
    }

    /**
     * @param array<string, mixed> $values
     */
    public function putMany(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->set((string) $key, $value);
        }
    }

    /** @return array<string, mixed> */
    public function all(): array
    {
        return $this->values;
    }
}
