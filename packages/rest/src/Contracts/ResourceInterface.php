<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Contracts;

/**
 * Serializable REST resource payload.
 */
interface ResourceInterface
{
    /** @return array<string, mixed>|list<mixed>|null */
    public function toArray(): ?array;
}
