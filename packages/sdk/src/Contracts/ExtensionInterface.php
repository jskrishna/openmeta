<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Contracts;

use OpenMeta\Sdk\Lifecycle\ExtensionState;

/**
 * A registered extension: its manifest plus current lifecycle state.
 */
interface ExtensionInterface
{
    public function id(): string;

    public function manifest(): ManifestInterface;

    public function state(): ExtensionState;

    public function isActive(): bool;
}
