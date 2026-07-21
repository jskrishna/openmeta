<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Resources;

use OpenMeta\Extensions\Contracts\ResourceLoaderInterface;

/**
 * Default in-memory resource aggregator.
 *
 * Extensions call the typed helpers (or {@see register()}) from their service
 * providers; hosts read {@see ofType()} through their own adapters.
 */
final class ResourceLoader implements ResourceLoaderInterface
{
    /** @var list<ResourceRegistration> */
    private array $registrations = [];

    public function register(ResourceType $type, string $id, mixed $payload, ?string $extensionId = null): void
    {
        $this->registrations[] = new ResourceRegistration($type, $id, $payload, $extensionId);
    }

    public function field(string $id, mixed $payload, ?string $extensionId = null): void
    {
        $this->register(ResourceType::Field, $id, $payload, $extensionId);
    }

    public function component(string $id, mixed $payload, ?string $extensionId = null): void
    {
        $this->register(ResourceType::Component, $id, $payload, $extensionId);
    }

    public function page(string $id, mixed $payload, ?string $extensionId = null): void
    {
        $this->register(ResourceType::Page, $id, $payload, $extensionId);
    }

    public function route(string $id, mixed $payload, ?string $extensionId = null): void
    {
        $this->register(ResourceType::Route, $id, $payload, $extensionId);
    }

    public function middleware(string $id, mixed $payload, ?string $extensionId = null): void
    {
        $this->register(ResourceType::Middleware, $id, $payload, $extensionId);
    }

    public function template(string $id, mixed $payload, ?string $extensionId = null): void
    {
        $this->register(ResourceType::Template, $id, $payload, $extensionId);
    }

    public function asset(string $id, mixed $payload, ?string $extensionId = null): void
    {
        $this->register(ResourceType::Asset, $id, $payload, $extensionId);
    }

    public function migration(string $id, mixed $payload, ?string $extensionId = null): void
    {
        $this->register(ResourceType::Migration, $id, $payload, $extensionId);
    }

    public function menu(string $id, mixed $payload, ?string $extensionId = null): void
    {
        $this->register(ResourceType::Menu, $id, $payload, $extensionId);
    }

    public function widget(string $id, mixed $payload, ?string $extensionId = null): void
    {
        $this->register(ResourceType::Widget, $id, $payload, $extensionId);
    }

    public function all(): array
    {
        return $this->registrations;
    }

    public function ofType(ResourceType $type): array
    {
        return array_values(array_filter(
            $this->registrations,
            static fn (ResourceRegistration $registration): bool => $registration->type === $type,
        ));
    }

    public function forExtension(string $extensionId): array
    {
        return array_values(array_filter(
            $this->registrations,
            static fn (ResourceRegistration $registration): bool => $registration->extensionId === $extensionId,
        ));
    }
}
