<?php

declare(strict_types=1);

namespace OpenMeta\Database\Configuration;

/**
 * Immutable database configuration snapshot.
 */
final class DatabaseConfig
{
    /**
     * @param array<string, array<string, mixed>> $connections
     */
    public function __construct(
        public readonly string $default,
        public readonly array $connections = [],
    ) {
    }

    /**
     * @param array<string, mixed> $config
     */
    public static function fromArray(array $config): self
    {
        $default = (string) ($config['default'] ?? 'memory');
        /** @var array<string, array<string, mixed>> $connections */
        $connections = is_array($config['connections'] ?? null) ? $config['connections'] : [];

        return new self($default, $connections);
    }

    /**
     * @return array<string, mixed>
     */
    public function connection(string $name): array
    {
        return $this->connections[$name] ?? ['driver' => 'memory', 'prefix' => 'om_'];
    }
}
