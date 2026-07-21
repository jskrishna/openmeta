<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Files;

/**
 * The realised outcome for a single generated file.
 */
enum FileStatus: string
{
    case Created = 'created';
    case Merged = 'merged';
    case Skipped = 'skipped';
    case Previewed = 'previewed';
}
