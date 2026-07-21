<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Registry;

use OpenMeta\Extensions\Contracts\ExtensionInterface;
use OpenMeta\Extensions\Contracts\ManifestInterface;
use OpenMeta\Extensions\Lifecycle\ExtensionState;

/**
 * A registered extension entity.
 *
 * State transitions are driven by the lifecycle manager; consumers should
 * treat this object as read-only and mutate it only through the SDK.
 */
final class Extension implements ExtensionInterface
{
    public function __construct(
        private ManifestInterface $manifest,
        private ExtensionState $state = ExtensionState::Installed,
    ) {
    }

    public function id(): string
    {
        return $this->manifest->packageId();
    }

    public function manifest(): ManifestInterface
    {
        return $this->manifest;
    }

    public function state(): ExtensionState
    {
        return $this->state;
    }

    public function isActive(): bool
    {
        return $this->state->isActive();
    }

    /**
     * @internal Driven by the lifecycle manager.
     */
    public function transitionTo(ExtensionState $state): void
    {
        $this->state = $state;
    }

    /**
     * @internal Driven by the lifecycle manager on update.
     */
    public function replaceManifest(ManifestInterface $manifest): void
    {
        $this->manifest = $manifest;
    }
}
