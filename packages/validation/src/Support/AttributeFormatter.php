<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Support;

/**
 * Formats attribute keys for human-readable message placeholders.
 */
final class AttributeFormatter
{
    public static function display(string $attribute): string
    {
        $attribute = str_replace('_', ' ', $attribute);
        $attribute = str_replace('.', ' ', $attribute);

        return $attribute;
    }
}
