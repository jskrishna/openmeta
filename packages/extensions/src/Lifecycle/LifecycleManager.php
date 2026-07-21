<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Lifecycle;

use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Extensions\Contracts\ExtensionRegistryInterface;
use OpenMeta\Extensions\Contracts\LifecycleManagerInterface;
use OpenMeta\Extensions\Contracts\ManifestInterface;
use OpenMeta\Extensions\Contracts\ServiceProviderLoaderInterface;
use OpenMeta\Extensions\Events\ExtensionActivated;
use OpenMeta\Extensions\Events\ExtensionDeactivated;
use OpenMeta\Extensions\Events\ExtensionDisabled;
use OpenMeta\Extensions\Events\ExtensionInstalled;
use OpenMeta\Extensions\Events\ExtensionRemoved;
use OpenMeta\Extensions\Events\ExtensionUpdated;
use OpenMeta\Extensions\Exceptions\LifecycleException;
use OpenMeta\Extensions\Registry\Extension;
use OpenMeta\Extensions\Support\FeatureFlags;
use OpenMeta\Extensions\Versioning\VersionManager;

/**
 * Drives extension lifecycle transitions and emits the matching events.
 *
 * This is a pure state machine: compatibility and dependency gating happen
 * upstream (in {@see \OpenMeta\Extensions\Bootstrap\ExtensionBootstrapper} or the
 * caller) so that this class has a single reason to change.
 */
final class LifecycleManager implements LifecycleManagerInterface
{
    public function __construct(
        private readonly ExtensionRegistryInterface $registry,
        private readonly ServiceProviderLoaderInterface $loader,
        private readonly ContainerInterface $container,
        private readonly EventDispatcherInterface $events,
        private readonly FeatureFlags $flags,
        private readonly VersionManager $versions,
    ) {
    }

    public function install(ManifestInterface $manifest): Extension
    {
        $id = $manifest->packageId();

        if ($this->registry->has($id)) {
            throw LifecycleException::alreadyInstalled($id);
        }

        $extension = new Extension($manifest, ExtensionState::Installed);
        $this->registry->add($extension);

        $this->flags->seed($id, $manifest->featureFlags());
        $this->versions->record($id, $manifest->version());

        $this->events->dispatch(new ExtensionInstalled($id, $manifest->version()));

        return $extension;
    }

    public function activate(string $packageId): Extension
    {
        $extension = $this->registry->get($packageId);
        $this->assertState($extension, [ExtensionState::Installed, ExtensionState::Disabled], 'activate');

        $this->loader->load($extension->manifest()->providers(), $this->container);
        $extension->transitionTo(ExtensionState::Active);

        $this->events->dispatch(new ExtensionActivated($packageId, $extension->manifest()->version()));

        return $extension;
    }

    public function deactivate(string $packageId): Extension
    {
        $extension = $this->registry->get($packageId);
        $this->assertState($extension, [ExtensionState::Active], 'deactivate');

        $extension->transitionTo(ExtensionState::Installed);

        $this->events->dispatch(new ExtensionDeactivated($packageId, $extension->manifest()->version()));

        return $extension;
    }

    public function disable(string $packageId): Extension
    {
        $extension = $this->registry->get($packageId);
        $this->assertState($extension, [ExtensionState::Installed, ExtensionState::Active], 'disable');

        $extension->transitionTo(ExtensionState::Disabled);

        $this->events->dispatch(new ExtensionDisabled($packageId, $extension->manifest()->version()));

        return $extension;
    }

    public function update(ManifestInterface $manifest): Extension
    {
        $id = $manifest->packageId();
        $extension = $this->registry->get($id);
        $previous = $extension->manifest()->version();

        $extension->replaceManifest($manifest);
        $this->flags->seed($id, $manifest->featureFlags());
        $this->versions->record($id, $manifest->version());

        $this->events->dispatch(new ExtensionUpdated($id, $previous, $manifest->version()));

        return $extension;
    }

    public function uninstall(string $packageId): void
    {
        $extension = $this->registry->get($packageId);
        $version = $extension->manifest()->version();

        $this->registry->remove($packageId);
        $this->versions->forget($packageId);

        $this->events->dispatch(new ExtensionRemoved($packageId, $version));
    }

    /**
     * @param list<ExtensionState> $allowed
     */
    private function assertState(Extension $extension, array $allowed, string $action): void
    {
        if (! in_array($extension->state(), $allowed, true)) {
            throw LifecycleException::illegalTransition($extension->id(), $extension->state(), $action);
        }
    }
}
