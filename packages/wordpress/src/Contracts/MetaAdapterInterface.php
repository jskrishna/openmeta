<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Contracts;

/**
 * Thin meta read/write adapter over WordPress meta APIs.
 */
interface MetaAdapterInterface
{
    public function get(int|string $objectId, string $key, bool $single = true): mixed;

    public function update(int|string $objectId, string $key, mixed $value): bool;

    public function delete(int|string $objectId, string $key): bool;
}
