---
title: Service Providers
description: How packages register and boot services into the container.
category: concepts
version: next
status: stable
last_updated: 2026-07-21
tags: [providers, lifecycle, core]
---

# Service Providers

A **service provider** is how every package plugs into the framework. It has two
phases, enforced by the kernel:

```mermaid
flowchart LR
  register["register() — bind services"] --> boot["boot() — wire & start"]
```

- `register()` runs first for **all** providers — bind services here; do not
  resolve another provider's bindings yet.
- `boot()` runs after every provider has registered — safe to resolve bindings
  and wire listeners.

## Writing one

```php
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Providers\ServiceProvider;

final class BlogServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $container->singleton(PostRepository::class, fn ($c) => new PostRepository(
            $c->get(\OpenMeta\Database\Contracts\ConnectionInterface::class)
        ));
    }

    public function boot(ContainerInterface $container): void
    {
        // Resolve and wire — everything is registered by now.
    }
}
```

Scaffold one with the [code generator](../packages/generator.md):

```bash
php bin/openmeta make:provider BlogServiceProvider
```

## Registration

Providers are passed to the application at boot (or aggregated by the
[`framework`](../packages/framework.md) meta package):

```php
$app = \OpenMeta\Core\Bootstrap\Bootstrap::run($config, [BlogServiceProvider::class]);
```

## Related

- [Dependency Injection](./dependency-injection.md) · [Lifecycle](./lifecycle.md)
- [core package](../packages/core.md) · [Extension SDK](../packages/extensions.md)

## Next steps

- [Events](./events.md)
