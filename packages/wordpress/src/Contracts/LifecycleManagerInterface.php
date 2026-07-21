<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Contracts;

/**
 * Plugin lifecycle hooks (activate, deactivate, upgrade).
 */
interface LifecycleManagerInterface
{
    public function register(): void;

    public function activate(): void;

    public function deactivate(): void;
}
