<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Events;

use OpenMeta\Admin\Pages\Page;

/**
 * Fired when an admin page begins rendering.
 */
final class PageLoaded
{
    public function __construct(public readonly Page $page)
    {
    }
}
