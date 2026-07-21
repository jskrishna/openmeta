<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Resources;

use OpenMeta\Rest\Contracts\ResourceInterface;
use OpenMeta\Rest\Contracts\TransformerInterface;

/**
 * Wrap arbitrary data with optional transformation.
 */
final class JsonResource implements ResourceInterface
{
    public function __construct(
        private readonly mixed $data,
        private readonly ?TransformerInterface $transformer = null,
    ) {
    }

    public function toArray(): ?array
    {
        if ($this->transformer !== null) {
            return $this->transformer->transform($this->data);
        }

        if (is_array($this->data)) {
            return $this->data;
        }

        if ($this->data === null) {
            return null;
        }

        return ['value' => $this->data];
    }
}
