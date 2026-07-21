<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Support;

use ArrayAccess;
use stdClass;
use Traversable;

/**
 * Normalizes array/object payloads into associative arrays for the validator.
 */
final class DataNormalizer
{
    /**
     * @param array<string, mixed>|object $data
     * @return array<string, mixed>
     */
    public static function normalize(array|object $data): array
    {
        if (is_array($data)) {
            return $data;
        }

        if ($data instanceof stdClass) {
            /** @var array<string, mixed> */
            return (array) $data;
        }

        if ($data instanceof ArrayAccess && $data instanceof Traversable) {
            /** @var array<string, mixed> */
            return iterator_to_array($data);
        }

        /** @var array<string, mixed> */
        return get_object_vars($data);
    }
}
