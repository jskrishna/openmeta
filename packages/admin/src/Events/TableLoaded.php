<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Events;

final class TableLoaded
{
    public function __construct(
        public readonly string $tableId,
        public readonly int $totalRows,
        public readonly int $page,
    ) {
    }
}
