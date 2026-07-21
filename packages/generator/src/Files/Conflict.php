<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Files;

/**
 * A detected conflict preventing (or gating) generation of a file.
 */
final class Conflict
{
    public function __construct(
        public readonly ConflictType $type,
        public readonly string $subject,
        public readonly string $message,
    ) {
    }
}
