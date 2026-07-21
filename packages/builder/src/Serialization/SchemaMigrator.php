<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Serialization;

use OpenMeta\Builder\Schema\SchemaVersion;

/**
 * Migrates older schema versions to the current format.
 */
final class SchemaMigrator
{
    /**
     * @param array<string, mixed> $schema
     * @return array<string, mixed>
     */
    public function migrate(array $schema): array
    {
        $version = (string) ($schema['version'] ?? SchemaVersion::CURRENT);

        if ($version === '0.9.0' || $version === '0.9.0-beta') {
            return $this->fromLegacyNodes($schema);
        }

        return $schema;
    }

    /**
     * @param array<string, mixed> $schema
     * @return array<string, mixed>
     */
    private function fromLegacyNodes(array $schema): array
    {
        if (isset($schema['canvas']) && is_array($schema['canvas'])) {
            return [...$schema, 'version' => SchemaVersion::CURRENT];
        }

        $nodes = is_array($schema['nodes'] ?? null) ? $schema['nodes'] : [];
        foreach ($nodes as &$node) {
            if (! is_array($node)) {
                continue;
            }

            if (isset($node['condition']) && is_string($node['condition'])) {
                $expression = trim($node['condition']);
                if ($expression !== '') {
                    $node['condition'] = ['field' => $expression, 'operator' => 'not_empty'];
                } else {
                    unset($node['condition']);
                }
            }
        }
        unset($node);

        return [
            'version' => SchemaVersion::CURRENT,
            'canvas' => ['nodes' => $nodes],
            'layouts' => [],
            'metadata' => is_array($schema['metadata'] ?? null) ? $schema['metadata'] : [],
        ];
    }
}
