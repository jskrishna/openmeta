<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Settings;

use OpenMeta\Validation\Validation;
use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * Settings group registration and validated persistence (no HTML).
 */
final class SettingsAdapter
{
    /** @var array<string, array<string, mixed>> */
    private array $groups = [];

    public function __construct(private readonly WordPressRuntimeInterface $runtime)
    {
    }

    /**
     * @param array<string, mixed> $schema Validation rules keyed by field name.
     */
    public function registerGroup(string $group, array $schema): void
    {
        $this->groups[$group] = $schema;
    }

    /**
     * @param array<string, mixed> $values
     * @return array<string, mixed> Validated values
     */
    public function save(string $group, array $values): array
    {
        if (! isset($this->groups[$group])) {
            throw new \InvalidArgumentException(sprintf('Unknown settings group [%s].', $group));
        }

        $validated = Validation::make($values, $this->groups[$group])->validate();
        $this->runtime->updateOption($this->optionKey($group), $validated);

        return $validated;
    }

    /**
     * @return array<string, mixed>
     */
    public function load(string $group): array
    {
        $stored = $this->runtime->getOption($this->optionKey($group), []);

        return is_array($stored) ? $stored : [];
    }

    private function optionKey(string $group): string
    {
        return 'openmeta_settings_' . $group;
    }
}
