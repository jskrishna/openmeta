<?php

declare(strict_types=1);

namespace OpenMeta\Builder\App;

use OpenMeta\Builder\Application\BuilderApplication;

/**
 * Backward-compatible alias for {@see BuilderApplication}.
 *
 * @deprecated Use {@see BuilderApplication} or {@see \OpenMeta\Builder\Builder}.
 */
final class VisualBuilder extends BuilderApplication
{
}
