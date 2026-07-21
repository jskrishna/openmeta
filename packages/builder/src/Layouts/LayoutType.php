<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Layouts;

/**
 * Supported layout node kinds.
 */
enum LayoutType: string
{
    case Row = 'row';
    case Column = 'column';
    case Container = 'container';
    case Section = 'section';
}
