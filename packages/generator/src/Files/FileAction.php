<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Files;

/**
 * What a generator intends to do with a target file.
 */
enum FileAction: string
{
    case Create = 'create';
    case Merge = 'merge';
}
