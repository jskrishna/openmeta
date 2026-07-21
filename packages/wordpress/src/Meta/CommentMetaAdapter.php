<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Meta;

use OpenMeta\Wordpress\Contracts\MetaAdapterInterface;
use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * Comment meta adapter via WordPress runtime.
 */
final class CommentMetaAdapter implements MetaAdapterInterface
{
    public function __construct(private readonly WordPressRuntimeInterface $runtime)
    {
    }

    public function get(int|string $objectId, string $key, bool $single = true): mixed
    {
        return $this->runtime->getCommentMeta((int) $objectId, $key, $single);
    }

    public function update(int|string $objectId, string $key, mixed $value): bool
    {
        return $this->runtime->updateCommentMeta((int) $objectId, $key, $value);
    }

    public function delete(int|string $objectId, string $key): bool
    {
        return $this->runtime->deleteCommentMeta((int) $objectId, $key);
    }
}
