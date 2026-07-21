<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Events;

use OpenMeta\Builder\Preview\PreviewResult;

final class PreviewGenerated
{
    public function __construct(public readonly PreviewResult $result)
    {
    }
}
