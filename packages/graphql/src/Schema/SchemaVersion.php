<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Schema;

/**
 * A named, immutable snapshot of a built schema.
 */
final class SchemaVersion
{
    public function __construct(
        public readonly string $version,
        public readonly Schema $schema,
    ) {
    }
}
