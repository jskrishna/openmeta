<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Events;

use Throwable;

/**
 * Dispatched when a generation run throws.
 */
final class GenerationFailed
{
    public function __construct(
        public readonly string $key,
        public readonly Throwable $exception,
    ) {
    }
}
