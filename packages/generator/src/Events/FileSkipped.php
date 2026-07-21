<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Events;

use OpenMeta\Generator\Files\Conflict;

/**
 * Dispatched when a file is skipped because of a conflict.
 */
final class FileSkipped
{
    public function __construct(
        public readonly string $path,
        public readonly Conflict $conflict,
    ) {
    }
}
