<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Menus;

/**
 * One wp-admin menu / submenu item definition.
 */
final class MenuItem
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $screenId,
        public readonly string $permission,
        public readonly ?string $parent = null,
        public readonly int $position = 10,
    ) {
    }
}
