<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Events;

use OpenMeta\GraphQL\Schema\Schema;

/**
 * Dispatched after a schema is assembled.
 */
final class SchemaBuilt
{
    public function __construct(
        public readonly Schema $schema,
        public readonly string $version,
    ) {
    }
}
