<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Contracts;

use OpenMeta\Extensions\Bootstrap\BootstrapReport;
use OpenMeta\Extensions\Compatibility\Environment;
use OpenMeta\Extensions\Registry\Extension;

/**
 * Public façade for the Extension SDK.
 *
 * This is the single supported entry point; the resolver, checker, loader and
 * other collaborators are implementation details.
 */
interface ExtensionManagerInterface
{
    /**
     * @return list<ManifestInterface>
     */
    public function discover(): array;

    public function install(ManifestInterface $manifest): Extension;

    public function activate(string $packageId): Extension;

    public function deactivate(string $packageId): Extension;

    public function disable(string $packageId): Extension;

    public function update(ManifestInterface $manifest): Extension;

    public function uninstall(string $packageId): void;

    /**
     * Discover, gate on compatibility, order by dependency, and activate.
     */
    public function bootstrap(Environment $environment): BootstrapReport;

    public function registry(): ExtensionRegistryInterface;

    public function resources(): ResourceLoaderInterface;

    public function flags(): FeatureFlagsInterface;
}
