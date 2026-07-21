<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Resources;

use OpenMeta\Rest\Contracts\ResourceInterface;
use OpenMeta\Rest\Contracts\TransformerInterface;

/**
 * Collection of resources.
 */
final class ResourceCollection implements ResourceInterface
{
    /**
     * @param list<mixed> $items
     */
    public function __construct(
        private readonly array $items,
        private readonly ?TransformerInterface $transformer = null,
    ) {
    }

    public function toArray(): array
    {
        $result = [];

        foreach ($this->items as $item) {
            if ($item instanceof ResourceInterface) {
                $result[] = $item->toArray();

                continue;
            }

            if ($this->transformer !== null) {
                $result[] = $this->transformer->transform($item);

                continue;
            }

            $result[] = is_array($item) ? $item : ['value' => $item];
        }

        return $result;
    }
}
