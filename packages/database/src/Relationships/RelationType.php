<?php

declare(strict_types=1);

namespace OpenMeta\Database\Relationships;

/**
 * Supported relation kinds (infrastructure only — not Active Record).
 */
enum RelationType: string
{
    case HasOne = 'has_one';
    case HasMany = 'has_many';
    case BelongsTo = 'belongs_to';
    case BelongsToMany = 'belongs_to_many';
}
