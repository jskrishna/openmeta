<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Localization;

use OpenMeta\Wordpress\Configuration\PluginConfiguration;
use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * Loads the OpenMeta text domain via WordPress runtime.
 */
final class LocalizationAdapter
{
    public function __construct(
        private readonly WordPressRuntimeInterface $runtime,
        private readonly PluginConfiguration $configuration,
    ) {
    }

    public function register(): void
    {
        $this->runtime->addAction('plugins_loaded', [$this, 'loadTextDomain'], 15);
    }

    public function loadTextDomain(): void
    {
        $pluginFile = $this->configuration->pluginFile();
        $relativePath = function_exists('plugin_basename')
            ? dirname((string) plugin_basename($pluginFile)) . '/languages'
            : 'languages';

        $this->runtime->loadTextDomain(
            $this->configuration->textDomain(),
            $relativePath
        );
    }
}
