<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Contracts;

use OpenMeta\GraphQL\Schema\Schema;

/**
 * Assembles, validates, versions, and caches the schema.
 */
interface SchemaManagerInterface
{
    public function schema(): Schema;

    public function rebuild(): Schema;

    public function version(): string;

    public function extend(SchemaExtensionInterface $extension): void;
}
