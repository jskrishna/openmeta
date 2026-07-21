<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Assets;

use OpenMeta\Wordpress\Contracts\AssetManagerInterface;
use OpenMeta\Wordpress\Configuration\PluginConfiguration;
use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * Script/style registration and enqueue via WordPress runtime.
 */
final class AssetManager implements AssetManagerInterface
{
    public function __construct(
        private readonly WordPressRuntimeInterface $runtime,
        private readonly PluginConfiguration $configuration,
    ) {
    }

    public function registerScript(
        string $handle,
        string $src,
        array $deps = [],
        string|false $version = false,
        bool $inFooter = true,
    ): bool {
        $version = $version === false ? $this->configuration->version() : $version;

        return $this->runtime->registerScript($handle, $src, $deps, $version, $inFooter);
    }

    public function registerStyle(
        string $handle,
        string $src,
        array $deps = [],
        string|false $version = false,
    ): bool {
        $version = $version === false ? $this->configuration->version() : $version;

        return $this->runtime->registerStyle($handle, $src, $deps, $version);
    }

    public function enqueueScript(string $handle): void
    {
        $this->runtime->enqueueScript($handle);
    }

    public function enqueueStyle(string $handle): void
    {
        $this->runtime->enqueueStyle($handle);
    }
}
