<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Files;

/**
 * The kind of conflict a target file/namespace presents.
 */
enum ConflictType: string
{
    case ExistingFile = 'existing_file';
    case NamingCollision = 'naming_collision';
    case ReservedNamespace = 'reserved_namespace';
}
