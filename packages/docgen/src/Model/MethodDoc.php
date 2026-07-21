<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Model;

/**
 * Documentation for one public method.
 */
final class MethodDoc
{
    public function __construct(
        public readonly string $name,
        public readonly string $signature,
        public readonly string $summary = '',
    ) {
    }
}
