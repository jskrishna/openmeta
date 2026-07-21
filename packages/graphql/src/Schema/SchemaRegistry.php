<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Schema;

use OpenMeta\GraphQL\Errors\TypeNotFoundException;

/**
 * Stores built schema versions and tracks the latest.
 */
final class SchemaRegistry
{
    /** @var array<string, SchemaVersion> */
    private array $versions = [];

    private ?string $latest = null;

    public function record(string $version, Schema $schema): SchemaVersion
    {
        $entry = new SchemaVersion($version, $schema);
        $this->versions[$version] = $entry;
        $this->latest = $version;

        return $entry;
    }

    public function has(string $version): bool
    {
        return isset($this->versions[$version]);
    }

    public function get(string $version): SchemaVersion
    {
        return $this->versions[$version] ?? throw TypeNotFoundException::named($version);
    }

    public function latest(): ?SchemaVersion
    {
        return $this->latest === null ? null : $this->versions[$this->latest];
    }

    /**
     * @return list<string>
     */
    public function versions(): array
    {
        return array_keys($this->versions);
    }
}
