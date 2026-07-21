<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Serialization;

use OpenMeta\Builder\Exceptions\BuilderException;
use OpenMeta\Builder\Schema\SchemaVersion;
use OpenMeta\Validation\Validation;

/**
 * Export / import schema payloads with validation.
 */
final class SchemaSerializer
{
    /**
     * @param array<string, mixed> $schema
     * @return array<string, mixed>
     */
    public function export(array $schema): array
    {
        return [
            'format' => 'openmeta-builder-schema',
            'version' => $schema['version'] ?? SchemaVersion::CURRENT,
            'payload' => $schema,
        ];
    }

    /**
     * @param array<string, mixed> $envelope
     * @return array<string, mixed>
     */
    public function import(array $envelope): array
    {
        if (($envelope['format'] ?? '') !== 'openmeta-builder-schema') {
            throw new BuilderException('Unsupported schema envelope format.');
        }

        $payload = $envelope['payload'] ?? null;
        if (! is_array($payload)) {
            throw new BuilderException('Schema payload must be an array.');
        }

        return $payload;
    }

    /**
     * @param array<string, mixed> $schema
     */
    public function validate(array $schema): void
    {
        $result = Validation::make($schema, [
            'version' => 'required|string',
            'canvas' => 'required|array',
        ]);

        if ($result->fails()) {
            throw new BuilderException('Invalid builder schema: ' . ($result->errors()->first() ?? 'unknown'));
        }
    }
}
