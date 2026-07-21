<?php

declare(strict_types=1);

namespace OpenMeta\Api\Rest\Resources;

/**
 * Transform a domain entity into a public API payload.
 */
interface ResourceInterface
{
    /** @return array<string, mixed> */
    public function toArray(): array;
}
