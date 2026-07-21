<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Events;

use OpenMeta\Wordpress\Configuration\PluginConfiguration;

/**
 * Dispatched when the plugin deactivation hook runs.
 */
final class PluginDeactivated
{
    public function __construct(public readonly PluginConfiguration $configuration)
    {
    }
}
