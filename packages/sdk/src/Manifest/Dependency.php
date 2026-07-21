<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Manifest;

/**
 * A single extension dependency: a package id, a version constraint, and
 * whether it is required or optional.
 */
final class Dependency
{
    public function __construct(
        public readonly string $packageId,
        public readonly string $constraint = '*',
        public readonly bool $optional = false,
    ) {
    }

    public function isRequired(): bool
    {
        return ! $this->optional;
    }

    /**
     * @return array{package: string, constraint: string, optional: bool}
     */
    public function toArray(): array
    {
        return [
            'package' => $this->packageId,
            'constraint' => $this->constraint,
            'optional' => $this->optional,
        ];
    }
}
