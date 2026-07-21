<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Contracts;

use OpenMeta\Admin\Pages\Page;

/**
 * Register and resolve admin pages (screens).
 */
interface PageManagerInterface
{
    public function register(Page $page): void;

    public function has(string $id): bool;

    public function get(string $id): Page;
}
