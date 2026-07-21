---
title: Core
description: Bootstrap, container, kernel, events, config, and shared contracts.
package: core
category: packages
version: next
status: stable
last_updated: 2026-07-21
tags: [core, package, container]
---

# Core

The heart of the framework: application bootstrap, the service
[container](../concepts/dependency-injection.md), the kernel, the
[event dispatcher](../concepts/events.md), [configuration](../concepts/configuration.md),
service providers, and the shared contracts every other package depends on.

Authoritative reference: [`packages/core/README.md`](../../packages/core/README.md)
· [`SPEC.md`](../../packages/core/SPEC.md).

## Public API

- `Application`, `Bootstrap::run()` — boot the framework
- `Container` / `ContainerInterface` — DI
- `Kernel` — lifecycle
- `EventDispatcher` / `EventDispatcherInterface`
- `ServiceProvider` — the extension unit
- `OpenMetaException` — base exception

## Example

```php
use OpenMeta\Core\Bootstrap\Bootstrap;

$app = Bootstrap::run(['app' => ['key' => 'secret']], $providers);
$service = $app->get(MyService::class);
```

## Extension points

Everything plugs in via [service providers](../concepts/service-providers.md);
no other package may be imported by Core.

## Related

- [Lifecycle](../concepts/lifecycle.md) · [Dependency Injection](../concepts/dependency-injection.md)
