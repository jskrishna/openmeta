<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Contracts;

/**
 * Portable schema build, export, import, and migration.
 */
interface SchemaManagerInterface
{
    /**
     * @return array<string, mixed>
     */
    public function build(): array;

    /**
     * @param array<string, mixed> $schema
     */
    public function load(array $schema): void;

    /**
     * @return array<string, mixed>
     */
    public function export(): array;

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function import(array $payload): array;
}
