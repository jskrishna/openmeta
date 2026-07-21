<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Schema;

use OpenMeta\Builder\Canvas\Canvas;
use OpenMeta\Builder\Contracts\SchemaManagerInterface;
use OpenMeta\Builder\Exceptions\BuilderException;
use OpenMeta\Builder\Layouts\LayoutEngine;
use OpenMeta\Builder\Serialization\SchemaImporter;
use OpenMeta\Builder\Serialization\SchemaMigrator;
use OpenMeta\Builder\Serialization\SchemaSerializer;

/**
 * Schema manager — build, load, export, import portable schemas.
 */
final class SchemaManager implements SchemaManagerInterface
{
    public function __construct(
        private Canvas $canvas,
        private readonly LayoutEngine $layouts,
        private readonly SchemaSerializer $serializer,
        private readonly SchemaImporter $importer,
        private readonly SchemaMigrator $migrator,
    ) {
    }

    public function canvas(): Canvas
    {
        return $this->canvas;
    }

    public function build(): array
    {
        return (new SchemaBuilder($this->canvas, $this->layouts))->build();
    }

    public function load(array $schema): void
    {
        $schema = $this->migrator->migrate($schema);
        $this->importer->validate($schema);

        $canvasData = is_array($schema['canvas'] ?? null) ? $schema['canvas'] : [];
        $loaded = Canvas::fromArray($canvasData);
        $this->canvas->replaceNodes($loaded->nodes());
        $this->canvas->setWorkspace($loaded->workspace());

        $layouts = is_array($schema['layouts'] ?? null) ? $schema['layouts'] : [];
        $this->layouts->load($layouts);
    }

    public function export(): array
    {
        return $this->serializer->export($this->build());
    }

    public function import(array $payload): array
    {
        $schema = $this->serializer->import($payload);
        $this->load($schema);

        return $schema;
    }
}
