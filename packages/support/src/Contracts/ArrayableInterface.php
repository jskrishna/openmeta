<?php

declare(strict_types=1);

namespace OpenMeta\Support\Contracts;

/**
 * Marks a type that can expose its data as a plain PHP array.
 */
interface ArrayableInterface
{
    /**
     * @return array<array-key, mixed>
     */
    public function toArray(): array;
}
