<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Contracts;

/**
 * Transform domain values into REST-safe arrays.
 */
interface TransformerInterface
{
    /** @return array<string, mixed>|list<mixed>|null */
    public function transform(mixed $value): ?array;
}
