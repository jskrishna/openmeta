<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Navigation;

/**
 * Breadcrumb trail item.
 */
final class Breadcrumb
{
    public function __construct(
        public readonly string $label,
        public readonly ?string $pageId = null,
    ) {
    }
}
