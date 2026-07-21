<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Manager;

use OpenMeta\Generator\Files\Conflict;
use OpenMeta\Generator\Files\FileStatus;

/**
 * The realised result for one file in a generation run.
 */
final class FileOutcome
{
    public function __construct(
        public readonly string $path,
        public readonly FileStatus $status,
        public readonly string $contents = '',
        public readonly ?Conflict $conflict = null,
    ) {
    }

    public function wasWritten(): bool
    {
        return $this->status === FileStatus::Created || $this->status === FileStatus::Merged;
    }
}
