<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Lifecycle;

use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Wordpress\Configuration\PluginConfiguration;
use OpenMeta\Wordpress\Contracts\LifecycleManagerInterface;
use OpenMeta\Wordpress\Events\AdminLoaded;
use OpenMeta\Wordpress\Events\PluginActivated;
use OpenMeta\Wordpress\Events\PluginDeactivated;
use OpenMeta\Wordpress\Events\RestInitialized;
use OpenMeta\Wordpress\Hooks\ActionBridge;
use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * Wires plugin lifecycle hooks and dispatches Core events.
 */
final class LifecycleManager implements LifecycleManagerInterface
{
    public function __construct(
        private readonly WordPressRuntimeInterface $runtime,
        private readonly EventDispatcherInterface $events,
        private readonly UpgradeManager $upgrade,
        private readonly PluginConfiguration $configuration,
    ) {
    }

    public function register(): void
    {
        $this->runtime->addAction('admin_init', [$this, 'onAdminLoaded']);
        $this->runtime->addAction(ActionBridge::REST_INIT, [$this, 'onRestInitialized']);
    }

    public function activate(): void
    {
        $this->upgrade->maybeUpgrade();
        $this->events->dispatch(new PluginActivated($this->configuration));
        $this->runtime->doAction('openmeta_activate');
    }

    public function deactivate(): void
    {
        $this->events->dispatch(new PluginDeactivated($this->configuration));
        $this->runtime->doAction('openmeta_deactivate');
    }

    public function onAdminLoaded(): void
    {
        $this->events->dispatch(new AdminLoaded());
    }

    public function onRestInitialized(): void
    {
        $this->events->dispatch(new RestInitialized());
    }
}
