<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Contracts;

use OpenMeta\Sdk\Exceptions\ExtensionNotFoundException;
use OpenMeta\Sdk\Exceptions\LifecycleException;
use OpenMeta\Sdk\Registry\Extension;

/**
 * Manages extension lifecycle transitions.
 *
 * @throws ExtensionNotFoundException When targeting an unregistered extension
 * @throws LifecycleException         When a transition is not allowed
 */
interface LifecycleManagerInterface
{
    public function install(ManifestInterface $manifest): Extension;

    public function activate(string $packageId): Extension;

    public function deactivate(string $packageId): Extension;

    public function disable(string $packageId): Extension;

    public function update(ManifestInterface $manifest): Extension;

    public function uninstall(string $packageId): void;
}
