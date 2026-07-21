<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Contracts;

use OpenMeta\Admin\Support\ScreenContext;

/**
 * Single layout composer.
 */
interface LayoutInterface
{
    public function id(): string;

    public function render(ScreenContext $context): string;
}
