<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Serialization;

use OpenMeta\Builder\Exceptions\BuilderException;
use OpenMeta\Builder\Schema\SchemaVersion;

/**
 * Validates imported schema structure before load.
 */
final class SchemaImporter
{
    public function __construct(private readonly SchemaSerializer $serializer)
    {
    }

    /**
     * @param array<string, mixed> $schema
     */
    public function validate(array $schema): void
    {
        $this->serializer->validate($schema);

        $version = (string) ($schema['version'] ?? '');
        if (! in_array($version, SchemaVersion::SUPPORTED, true)) {
            throw new BuilderException(sprintf('Unsupported schema version [%s].', $version));
        }
    }
}
