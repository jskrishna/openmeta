<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Plugin;

use OpenMeta\Admin\AdminServiceProvider;
use OpenMeta\Api\ApiServiceProvider;
use OpenMeta\Builder\BuilderServiceProvider;
use OpenMeta\Core\Application\Application;
use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Database\DatabaseServiceProvider;
use OpenMeta\Fields\FieldsServiceProvider;
use OpenMeta\Rest\RestServiceProvider;
use OpenMeta\Security\SecurityServiceProvider;
use OpenMeta\Ui\UiServiceProvider;
use OpenMeta\Validation\ValidationServiceProvider;
use OpenMeta\Wordpress\Capabilities\CapabilityRegistrar;
use OpenMeta\Wordpress\Configuration\PluginConfiguration;
use OpenMeta\Wordpress\Filters\FilterBridge;
use OpenMeta\Wordpress\Lifecycle\UpgradeManager;
use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;
use OpenMeta\Wordpress\Providers\WordpressServiceProvider;

/**
 * WordPress plugin shell — requirements, boot, activate / deactivate.
 */
final class Plugin
{
    public const VERSION = '0.8.0-alpha';

    private ?Application $app = null;

    private bool $booted = false;

    public function __construct(
        private readonly WordPressRuntimeInterface $wp,
        private readonly Requirements $requirements,
        private readonly string $pluginFile,
    ) {
    }

    /**
     * @param array<string, mixed> $config
     */
    public function boot(array $config = []): ?Application
    {
        if ($this->booted) {
            return $this->app;
        }

        $wpVersion = defined('WP_VERSION') ? (string) WP_VERSION : null;
        $failures = $this->requirements->check(null, $wpVersion);

        if ($failures !== []) {
            foreach ($failures as $message) {
                $this->wp->adminNotice($message, 'error');
            }

            return null;
        }

        $filters = new FilterBridge($this->wp);
        $config = (array) $filters->apply(FilterBridge::CONFIG, $config);

        $this->app = Bootstrap::run($config, [
            ValidationServiceProvider::class,
            SecurityServiceProvider::class,
            DatabaseServiceProvider::class,
            FieldsServiceProvider::class,
            RestServiceProvider::class,
            ApiServiceProvider::class,
            UiServiceProvider::class,
            AdminServiceProvider::class,
            BuilderServiceProvider::class,
            new WordpressServiceProvider($this->wp, $this->pluginFile),
        ]);

        $this->booted = true;

        return $this->app;
    }

    public function activate(): void
    {
        (new CapabilityRegistrar($this->wp))->register();

        $configuration = PluginConfiguration::fromPluginFile($this->pluginFile);
        (new UpgradeManager($this->wp, $configuration))->maybeUpgrade();

        $this->wp->doAction('openmeta_activate');
    }

    public function deactivate(): void
    {
        $this->wp->doAction('openmeta_deactivate');
    }

    public function app(): ?Application
    {
        return $this->app;
    }

    public function isBooted(): bool
    {
        return $this->booted;
    }

    public function pluginFile(): string
    {
        return $this->pluginFile;
    }

    public function runtime(): WordPressRuntimeInterface
    {
        return $this->wp;
    }
}
