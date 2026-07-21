<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Registry;

use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Fields\Events\FieldRegistered;
use OpenMeta\Fields\Exceptions\UnknownFieldTypeException;
use OpenMeta\Fields\Field\Field;

/**
 * Single catalog of field types — O(1) resolve after boot.
 *
 * Supports aliases, versioning, discovery, and removal. No static state.
 */
final class FieldRegistry
{
    /** @var array<string, class-string<Field>|callable(string, array<string, mixed>): Field> */
    private array $types = [];

    /** @var array<string, string> alias → canonical type */
    private array $aliases = [];

    /** @var array<string, array<string, class-string<Field>|callable(string, array<string, mixed>): Field>> */
    private array $versions = [];

    public function __construct(
        private readonly ?EventDispatcherInterface $events = null,
    ) {
    }

    /**
     * @param class-string<Field>|callable(string, array<string, mixed>): Field $factory
     */
    public function register(string $type, string|callable $factory, ?string $version = null): void
    {
        $type = $this->normalize($type);

        if ($version !== null) {
            $this->versions[$type][$version] = $factory;
        }

        $this->types[$type] = $factory;
        $this->events?->dispatch(new FieldRegistered($type, $version));
    }

    public function remove(string $type): void
    {
        $type = $this->normalize($type);
        unset($this->types[$type], $this->versions[$type], $this->aliases[$type]);

        foreach ($this->aliases as $alias => $canonical) {
            if ($canonical === $type) {
                unset($this->aliases[$alias]);
            }
        }
    }

    public function alias(string $alias, string $canonical): void
    {
        $this->aliases[$this->normalize($alias)] = $this->normalize($canonical);
    }

    /**
     * @param iterable<string, class-string<Field>|callable(string, array<string, mixed>): Field> $types
     */
    public function discover(iterable $types): void
    {
        foreach ($types as $type => $factory) {
            if (! is_string($type)) {
                continue;
            }

            $this->register($type, $factory);
        }
    }

    public function has(string $type): bool
    {
        $resolved = $this->resolveTypeName($type);

        return isset($this->types[$resolved]);
    }

    /**
     * @return list<string>
     */
    public function all(): array
    {
        return array_keys($this->types);
    }

    /**
     * @return list<string>
     */
    public function versions(string $type): array
    {
        $resolved = $this->resolveTypeName($type);

        return array_keys($this->versions[$resolved] ?? []);
    }

    /**
     * @param array<string, mixed> $settings
     */
    public function make(string $type, string $name, array $settings = [], ?string $version = null): Field
    {
        $resolved = $this->resolveTypeName($type);
        $factory = $this->resolveFactory($resolved, $version);

        if (is_string($factory)) {
            /** @var Field $field */
            $field = new $factory($name, $settings);

            return $field;
        }

        return $factory($name, $settings);
    }

    /**
     * @return class-string<Field>|callable(string, array<string, mixed>): Field
     */
    public function resolve(string $type, ?string $version = null): string|callable
    {
        return $this->resolveFactory($this->resolveTypeName($type), $version);
    }

    private function resolveTypeName(string $type): string
    {
        $type = $this->normalize($type);

        return $this->aliases[$type] ?? $type;
    }

    /**
     * @return class-string<Field>|callable(string, array<string, mixed>): Field
     */
    private function resolveFactory(string $type, ?string $version): string|callable
    {
        if ($version !== null) {
            if (! isset($this->versions[$type][$version])) {
                throw new UnknownFieldTypeException(
                    sprintf('Unknown field type [%s] version [%s].', $type, $version)
                );
            }

            return $this->versions[$type][$version];
        }

        if (! isset($this->types[$type])) {
            throw new UnknownFieldTypeException(sprintf('Unknown field type [%s].', $type));
        }

        return $this->types[$type];
    }

    private function normalize(string $type): string
    {
        return strtolower(trim($type));
    }
}
