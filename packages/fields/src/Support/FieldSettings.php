<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Support;

/**
 * Small helpers for field settings bags.
 */
final class FieldSettings
{
    /**
     * @param array<string, mixed> $settings
     * @return array<string, mixed>
     */
    public static function merge(array $settings, array $overrides): array
    {
        return array_replace($settings, $overrides);
    }

    /**
     * @param array<string, mixed> $settings
     * @return list<string>
     */
    public static function optionValues(array $settings): array
    {
        $options = $settings['options'] ?? [];

        if (! is_array($options)) {
            return [];
        }

        $values = [];

        foreach ($options as $key => $label) {
            if (is_int($key) && is_scalar($label)) {
                $values[] = (string) $label;
                continue;
            }

            $values[] = (string) $key;
        }

        return $values;
    }
}
