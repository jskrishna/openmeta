---
title: Dependency Injection
description: The service container, bindings, and constructor injection.
category: concepts
version: next
status: stable
last_updated: 2026-07-21
tags: [container, dependency-injection, core]
---

# Dependency Injection

OpenMeta resolves collaborators through a **service container** rather than
constructing global state. The container lives in
[`core`](../packages/core.md) as `OpenMeta\Core\Container\Container`.

## Bindings

```php
use OpenMeta\Core\Contracts\ContainerInterface;

$container->bind('id', fn (ContainerInterface $c) => new Service());
$container->singleton(Service::class, fn () => new Service());   // one instance
$container->instance(Clock::class, new SystemClock());           // existing object
$container->alias(Service::class, 'service');                    // alternate id
```

## Resolving

```php
$service = $container->get(Service::class);   // or resolve()
$has = $container->has(Service::class);
```

Closures receive the container, so a binding can resolve its own dependencies:

```php
$container->singleton(Repo::class, fn ($c) => new Repo($c->get(Connection::class)));
```

## Principles

- **Program to interfaces** — bind an interface to a concrete and depend on the
  interface. Every package binds its contracts in a
  [service provider](./service-providers.md).
- **Constructor injection** — declare dependencies as constructor parameters;
  avoid hidden globals.
- **Keep WordPress at the edges** — domain classes never call WP functions; the
  `wordpress` adapter bridges them.

## Related

- [Service Providers](./service-providers.md) · [Lifecycle](./lifecycle.md)
- [core package](../packages/core.md)

## Next steps

- [Service Providers](./service-providers.md)
