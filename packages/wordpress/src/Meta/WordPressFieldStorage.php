<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Meta;

use OpenMeta\Fields\Contracts\FieldStorageInterface;
use OpenMeta\Fields\Field\Field;
use OpenMeta\Wordpress\Contracts\MetaAdapterInterface;

/**
 * Field storage bridge delegating to WordPress meta adapters by entity type.
 */
final class WordPressFieldStorage implements FieldStorageInterface
{
    /** @var array<string, MetaAdapterInterface> */
    private array $adapters;

    public function __construct(
        PostMetaAdapter $postMeta,
        UserMetaAdapter $userMeta,
        TermMetaAdapter $termMeta,
        CommentMetaAdapter $commentMeta,
        OptionsAdapter $options,
    ) {
        $this->adapters = [
            'post' => $postMeta,
            'user' => $userMeta,
            'term' => $termMeta,
            'comment' => $commentMeta,
            'option' => $options,
        ];
    }

    public function save(string $entityType, int|string $entityId, Field $field, mixed $value): void
    {
        $this->adapter($entityType)->update($entityId, $field->name(), $value);
    }

    public function load(string $entityType, int|string $entityId, Field $field): mixed
    {
        return $this->adapter($entityType)->get($entityId, $field->name());
    }

    public function delete(string $entityType, int|string $entityId, Field $field): bool
    {
        return $this->adapter($entityType)->delete($entityId, $field->name());
    }

    private function adapter(string $entityType): MetaAdapterInterface
    {
        if (! isset($this->adapters[$entityType])) {
            throw new \InvalidArgumentException(sprintf('Unsupported entity type [%s].', $entityType));
        }

        return $this->adapters[$entityType];
    }
}
