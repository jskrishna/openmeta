<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Meta;

use OpenMeta\Wordpress\Contracts\MetaAdapterInterface;
use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * Options adapter — stores values under a single option key per field.
 */
final class OptionsAdapter implements MetaAdapterInterface
{
    public function __construct(private readonly WordPressRuntimeInterface $runtime)
    {
    }

    public function get(int|string $objectId, string $key, bool $single = true): mixed
    {
        $optionKey = $this->optionKey($objectId, $key);
        $value = $this->runtime->getOption($optionKey, $single ? '' : []);

        return $value;
    }

    public function update(int|string $objectId, string $key, mixed $value): bool
    {
        return $this->runtime->updateOption($this->optionKey($objectId, $key), $value);
    }

    public function delete(int|string $objectId, string $key): bool
    {
        $optionKey = $this->optionKey($objectId, $key);
        $existing = $this->runtime->getOption($optionKey, null);

        if ($existing === null) {
            return false;
        }

        return $this->runtime->updateOption($optionKey, null);
    }

    private function optionKey(int|string $objectId, string $key): string
    {
        return 'openmeta_option_' . (string) $objectId . '_' . $key;
    }
}
