<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Contracts;

use OpenMeta\Builder\Canvas\Canvas;
use OpenMeta\Builder\Preview\PreviewResult;

/**
 * Preview contract — no UI rendering in the builder package.
 */
interface PreviewEngineInterface
{
    /**
     * @param array<string, mixed> $values
     */
    public function generate(Canvas $canvas, array $values = []): PreviewResult;
}
