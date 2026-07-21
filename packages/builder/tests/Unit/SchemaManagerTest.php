<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Tests\Unit;

use OpenMeta\Builder\Canvas\CanvasNode;
use OpenMeta\Builder\Exceptions\BuilderException;
use OpenMeta\Builder\Schema\SchemaVersion;
use OpenMeta\Builder\Tests\BuilderTestCase;

final class SchemaManagerTest extends BuilderTestCase
{
    public function test_build_export_import_round_trip(): void
    {
        $this->canvas->add(new CanvasNode('n1', 'text', 'title', ['label' => 'Title']));
        $schema = $this->schema->build();

        $this->assertSame(SchemaVersion::CURRENT, $schema['version']);
        $this->assertCount(1, $schema['canvas']['nodes']);

        $envelope = $this->schema->export();
        $this->assertSame('openmeta-builder-schema', $envelope['format']);

        $this->canvas->remove('n1');
        $this->assertSame(0, $this->canvas->count());

        $this->schema->import($envelope);
        $this->assertSame(1, $this->canvas->count());
    }

    public function test_legacy_schema_migration(): void
    {
        $legacy = [
            'version' => '0.9.0-beta',
            'nodes' => [
                [
                    'id' => 'n1',
                    'type' => 'text',
                    'name' => 'email',
                    'settings' => [],
                    'condition' => 'email',
                ],
            ],
        ];

        $this->schema->load($legacy);
        $node = $this->canvas->nodes()[0];
        $this->assertSame('not_empty', $node->condition['operator'] ?? null);
    }

    public function test_invalid_schema_rejected(): void
    {
        $this->expectException(BuilderException::class);
        $this->schema->import([
            'format' => 'openmeta-builder-schema',
            'payload' => ['version' => '1.0.0'],
        ]);
    }
}
