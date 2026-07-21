<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Events;

use OpenMeta\Wordpress\Configuration\PluginConfiguration;

/**
 * Dispatched when the plugin activation hook runs.
 */
final class PluginActivated
{
    public function __construct(public readonly PluginConfiguration $configuration)
    {
    }
}
