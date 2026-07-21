<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Contracts;

use OpenMeta\GraphQL\Schema\SchemaRegistries;

/**
 * A unit that contributes types, queries, mutations, scalars, or directives
 * to the schema — the extension point third parties implement.
 */
interface SchemaExtensionInterface
{
    /**
     * Register contributions against the shared registries.
     */
    public function extend(SchemaRegistries $registries): void;
}
