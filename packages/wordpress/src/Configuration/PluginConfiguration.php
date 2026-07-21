<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Configuration;

use OpenMeta\Wordpress\Plugin\Plugin;

/**
 * Immutable plugin configuration loaded from the entry file.
 */
final class PluginConfiguration
{
    public const TEXT_DOMAIN = 'openmeta';

    public function __construct(
        private readonly string $pluginFile,
        private readonly string $version = Plugin::VERSION,
    ) {
    }

    public static function fromPluginFile(string $pluginFile): self
    {
        return new self($pluginFile);
    }

    public function pluginFile(): string
    {
        return $this->pluginFile;
    }

    public function pluginDir(): string
    {
        return dirname($this->pluginFile);
    }

    public function version(): string
    {
        return $this->version;
    }

    public function textDomain(): string
    {
        return self::TEXT_DOMAIN;
    }

    public function versionOptionKey(): string
    {
        return 'openmeta_version';
    }
}
