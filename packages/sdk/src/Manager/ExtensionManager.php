<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Manager;

use OpenMeta\Sdk\Bootstrap\BootstrapReport;
use OpenMeta\Sdk\Bootstrap\ExtensionBootstrapper;
use OpenMeta\Sdk\Compatibility\Environment;
use OpenMeta\Sdk\Contracts\DiscoveryInterface;
use OpenMeta\Sdk\Contracts\ExtensionManagerInterface;
use OpenMeta\Sdk\Contracts\ExtensionRegistryInterface;
use OpenMeta\Sdk\Contracts\FeatureFlagsInterface;
use OpenMeta\Sdk\Contracts\LifecycleManagerInterface;
use OpenMeta\Sdk\Contracts\ManifestInterface;
use OpenMeta\Sdk\Contracts\ResourceLoaderInterface;
use OpenMeta\Sdk\Registry\Extension;

/**
 * Default extension manager — the SDK's public entry point.
 *
 * Thin façade that delegates to the lifecycle manager, discovery, registry,
 * and bootstrapper. Holds no lifecycle logic of its own.
 */
final class ExtensionManager implements ExtensionManagerInterface
{
    public function __construct(
        private readonly DiscoveryInterface $discovery,
        private readonly ExtensionRegistryInterface $registry,
        private readonly LifecycleManagerInterface $lifecycle,
        private readonly ResourceLoaderInterface $resources,
        private readonly FeatureFlagsInterface $flags,
        private readonly ExtensionBootstrapper $bootstrapper,
    ) {
    }

    public function discover(): array
    {
        return $this->discovery->discover();
    }

    public function install(ManifestInterface $manifest): Extension
    {
        return $this->lifecycle->install($manifest);
    }

    public function activate(string $packageId): Extension
    {
        return $this->lifecycle->activate($packageId);
    }

    public function deactivate(string $packageId): Extension
    {
        return $this->lifecycle->deactivate($packageId);
    }

    public function disable(string $packageId): Extension
    {
        return $this->lifecycle->disable($packageId);
    }

    public function update(ManifestInterface $manifest): Extension
    {
        return $this->lifecycle->update($manifest);
    }

    public function uninstall(string $packageId): void
    {
        $this->lifecycle->uninstall($packageId);
    }

    public function bootstrap(Environment $environment): BootstrapReport
    {
        return $this->bootstrapper->bootstrap($environment);
    }

    public function registry(): ExtensionRegistryInterface
    {
        return $this->registry;
    }

    public function resources(): ResourceLoaderInterface
    {
        return $this->resources;
    }

    public function flags(): FeatureFlagsInterface
    {
        return $this->flags;
    }
}
