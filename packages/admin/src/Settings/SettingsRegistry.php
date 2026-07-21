<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Settings;

/**
 * Settings section + field registry (capability-gated at screen level).
 */
final class SettingsRegistry
{
    /** @var array<string, array{title: string, fields: list<array{name: string, label: string, rules?: string}>}> */
    private array $sections = [];

    /**
     * @param list<array{name: string, label: string, rules?: string}> $fields
     */
    public function section(string $id, string $title, array $fields): void
    {
        $this->sections[$id] = [
            'title' => $title,
            'fields' => $fields,
        ];
    }

    public function has(string $id): bool
    {
        return isset($this->sections[$id]);
    }

    /**
     * @return array{title: string, fields: list<array{name: string, label: string, rules?: string}>}
     */
    public function get(string $id): array
    {
        return $this->sections[$id];
    }

    /**
     * @return array<string, array{title: string, fields: list<array{name: string, label: string, rules?: string}>}>
     */
    public function all(): array
    {
        return $this->sections;
    }
}
