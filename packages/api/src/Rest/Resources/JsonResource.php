<?php

declare(strict_types=1);

namespace OpenMeta\Api\Rest\Resources;

/**
 * Single resource wrapper.
 */
class JsonResource implements ResourceInterface
{
    /** @param array<string, mixed> $data */
    public function __construct(private readonly array $data)
    {
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
