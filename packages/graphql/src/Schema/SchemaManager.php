<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Schema;

use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\GraphQL\Contracts\SchemaExtensionInterface;
use OpenMeta\GraphQL\Contracts\SchemaManagerInterface;
use OpenMeta\GraphQL\Errors\SchemaException;
use OpenMeta\GraphQL\Events\SchemaBuilt;

/**
 * Assembles, validates, versions, and caches the schema.
 *
 * Extensions registered via {@see extend()} contribute to the registries and
 * invalidate the cached schema so the next {@see schema()} rebuilds.
 */
final class SchemaManager implements SchemaManagerInterface
{
    private ?Schema $schema = null;

    private int $revision = 0;

    public function __construct(
        private readonly SchemaRegistries $registries,
        private readonly SchemaBuilder $builder,
        private readonly SchemaValidator $validator,
        private readonly SchemaRegistry $versions,
        private readonly EventDispatcherInterface $events,
    ) {
    }

    public function schema(): Schema
    {
        if ($this->schema === null) {
            $this->build();
        }

        /** @var Schema */
        return $this->schema;
    }

    public function rebuild(): Schema
    {
        $this->schema = null;

        return $this->schema();
    }

    public function version(): string
    {
        return 'v' . $this->revision;
    }

    public function extend(SchemaExtensionInterface $extension): void
    {
        $extension->extend($this->registries);
        $this->schema = null;
    }

    public function registries(): SchemaRegistries
    {
        return $this->registries;
    }

    /**
     * @throws SchemaException When validation fails
     */
    private function build(): void
    {
        $schema = $this->builder->build($this->registries);
        $errors = $this->validator->validate($schema);

        if ($errors !== []) {
            throw new SchemaException('Invalid GraphQL schema: ' . implode('; ', $errors));
        }

        $this->revision++;
        $this->schema = $schema;
        $this->versions->record($this->version(), $schema);
        $this->events->dispatch(new SchemaBuilt($schema, $this->version()));
    }
}
