<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Contracts;

/**
 * Single condition or composite group — extensible conditional logic.
 */
interface ConditionInterface
{
    /**
     * @param array<string, mixed> $values Field values keyed by name
     */
    public function matches(array $values): bool;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
