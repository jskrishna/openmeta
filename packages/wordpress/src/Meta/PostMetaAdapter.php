<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Meta;

use OpenMeta\Wordpress\Contracts\MetaAdapterInterface;
use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * Post meta adapter via WordPress runtime.
 */
final class PostMetaAdapter implements MetaAdapterInterface
{
    public function __construct(private readonly WordPressRuntimeInterface $runtime)
    {
    }

    public function get(int|string $objectId, string $key, bool $single = true): mixed
    {
        return $this->runtime->getPostMeta((int) $objectId, $key, $single);
    }

    public function update(int|string $objectId, string $key, mixed $value): bool
    {
        return $this->runtime->updatePostMeta((int) $objectId, $key, $value);
    }

    public function delete(int|string $objectId, string $key): bool
    {
        return $this->runtime->deletePostMeta((int) $objectId, $key);
    }
}
