<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Storage;

use OpenMeta\Database\Contracts\ConnectionInterface;
use OpenMeta\Database\Repositories\TableRepository;
use OpenMeta\Database\Schema\Blueprint;
use OpenMeta\Database\Schema\Schema;
use OpenMeta\Fields\Contracts\FieldStorageInterface;
use OpenMeta\Fields\Exceptions\StorageFailureException;
use OpenMeta\Fields\Field\Field;
use JsonException;

/**
 * Database-backed field value adapter (custom table via `@openmeta/database`).
 *
 * Not WordPress meta — WP adapters belong in `@openmeta/wordpress`.
 */
final class FieldValueStorage implements FieldStorageInterface
{
    public const TABLE = 'field_values';

    private readonly TableRepository $repository;

    private bool $ready = false;

    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly Schema $schema,
    ) {
        $this->repository = new TableRepository($connection, self::TABLE);
    }

    public function ensureTable(): void
    {
        if ($this->ready || $this->schema->hasTable(self::TABLE)) {
            $this->ready = true;

            return;
        }

        $this->schema->create(self::TABLE, static function (Blueprint $table): void {
            $table->id();
            $table->string('entity_type', 100);
            $table->string('entity_id', 64);
            $table->string('field_key', 100);
            $table->text('value', true);
            $table->index('entity_type');
            $table->index('field_key');
        });

        $this->ready = true;
    }

    public function save(string $entityType, int|string $entityId, Field $field, mixed $value): void
    {
        $this->ensureTable();
        $encoded = $this->encode($field->cast($field->sanitize($value)));
        $existing = $this->findRow($entityType, $entityId, $field->name());

        if ($existing === null) {
            $this->repository->create([
                'entity_type' => $entityType,
                'entity_id' => (string) $entityId,
                'field_key' => $field->name(),
                'value' => $encoded,
            ]);

            return;
        }

        $this->repository->update($existing['id'], ['value' => $encoded]);
    }

    public function load(string $entityType, int|string $entityId, Field $field): mixed
    {
        $this->ensureTable();
        $row = $this->findRow($entityType, $entityId, $field->name());

        if ($row === null) {
            return null;
        }

        return $field->cast($this->decode((string) ($row['value'] ?? '')));
    }

    public function delete(string $entityType, int|string $entityId, Field $field): bool
    {
        $this->ensureTable();
        $row = $this->findRow($entityType, $entityId, $field->name());

        if ($row === null) {
            return false;
        }

        return $this->repository->delete($row['id']);
    }

    /** @return array<string, mixed>|null */
    private function findRow(string $entityType, int|string $entityId, string $fieldKey): ?array
    {
        return $this->repository->query()
            ->where('entity_type', $entityType)
            ->where('entity_id', (string) $entityId)
            ->where('field_key', $fieldKey)
            ->first();
    }

    private function encode(mixed $value): string
    {
        try {
            return json_encode($value, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new StorageFailureException('Failed to encode field value for storage.', 0, $e);
        }
    }

    private function decode(string $value): mixed
    {
        if ($value === '') {
            return null;
        }

        try {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new StorageFailureException('Failed to decode field value from storage.', 0, $e);
        }
    }
}
