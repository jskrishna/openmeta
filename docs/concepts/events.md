---
title: Events
description: The synchronous event dispatcher and framework/domain events.
category: concepts
version: next
status: stable
last_updated: 2026-07-21
tags: [events, core]
---

# Events

OpenMeta combines WordPress hooks (at the edges) with an internal, synchronous
**event dispatcher** for framework and domain events
(`OpenMeta\Core\Contracts\EventDispatcherInterface`).

## Listen & dispatch

```php
use OpenMeta\Core\Contracts\EventDispatcherInterface;

/** @var EventDispatcherInterface $events */
$events->listen(PostPublished::class, function (PostPublished $event): void {
    // react
});

$events->dispatch(new PostPublished($postId));
```

Events are plain, immutable objects:

```php
final class PostPublished
{
    public function __construct(public readonly int $postId) {}
}
```

## Framework events

Packages dispatch their own events through the same dispatcher — for example the
[Extension SDK](../packages/extensions.md) emits `ExtensionActivated`, the
[GraphQL](../packages/graphql.md) layer emits `QueryExecuted`, and the
[CLI](../packages/cli.md) emits `CommandFinished`. Listen for any of them the
same way.

## Guidelines

- Keep listeners side-effect aware and decoupled from private internals.
- Prefer events over tight coupling when packages need to react to each other.

## Related

- [Service Providers](./service-providers.md) · [core package](../packages/core.md)
- [ADR-0010](../adr/ADR-0010-event-system.md)

## Next steps

- [Configuration](./configuration.md)
