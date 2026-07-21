<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Contracts;

use OpenMeta\Admin\Support\ScreenContext;

/**
 * Compose screen regions into a layout shell.
 */
interface LayoutManagerInterface
{
    public function render(string $layoutId, ScreenContext $context): string;

    public function has(string $layoutId): bool;
}
