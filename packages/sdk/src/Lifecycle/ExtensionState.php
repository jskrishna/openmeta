<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Lifecycle;

/**
 * Lifecycle state of a registered extension.
 *
 *   install    → Installed
 *   activate   → Active      (Installed | Disabled → Active)
 *   deactivate → Installed   (Active → Installed)
 *   disable    → Disabled    (Installed | Active → Disabled)
 *   uninstall  → removed from the registry
 */
enum ExtensionState: string
{
    case Installed = 'installed';
    case Active = 'active';
    case Disabled = 'disabled';

    public function isActive(): bool
    {
        return $this === self::Active;
    }
}
