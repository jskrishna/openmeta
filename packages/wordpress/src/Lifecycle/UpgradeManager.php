<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Lifecycle;

use OpenMeta\Wordpress\Configuration\PluginConfiguration;
use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * Compares stored plugin version and updates the option non-destructively.
 */
final class UpgradeManager
{
    public function __construct(
        private readonly WordPressRuntimeInterface $runtime,
        private readonly PluginConfiguration $configuration,
    ) {
    }

    public function maybeUpgrade(): void
    {
        $stored = $this->runtime->getOption($this->configuration->versionOptionKey(), '');
        $current = $this->configuration->version();

        if (! is_string($stored) || version_compare($stored, $current, '<')) {
            $this->runtime->updateOption($this->configuration->versionOptionKey(), $current);
            $this->runtime->doAction('openmeta_upgrade', $stored, $current);
        }
    }

    public function storedVersion(): string
    {
        $stored = $this->runtime->getOption($this->configuration->versionOptionKey(), '');

        return is_string($stored) ? $stored : '';
    }
}
