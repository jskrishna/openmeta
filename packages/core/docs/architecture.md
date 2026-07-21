# Architecture

What `@openmeta/core` is for — not how individual classes are coded.

---

## Purpose

Core is the **runtime spine** of OpenMeta. It starts the application, holds shared services, and gives every other package a stable place to register and boot.

It answers: *How does the framework come alive, and how do packages plug into that process?*

---

## What core owns

- Application lifecycle (start → ready)
- Service container (shared object graph)
- Service providers (registration + boot)
- Configuration (runtime settings available at boot)
- Events (in-process notifications between core parts and packages)
- Kernel + bootstrapper (orchestration of the above)
- Common contracts and base errors other packages can rely on

---

## What core does not own

- Database or storage engines
- Fields or content models
- REST or GraphQL
- Admin UI, builder, or WordPress screens

Those belong in other packages that **depend on** core. Core never depends on them.

---

## Mental model

```text
Bootstrapper  →  starts the process
Configuration →  answers “what settings do we have?”
Container     →  answers “how do we get shared services?”
Providers     →  teach the container about services
Kernel        →  runs register, then boot, in order
Events        →  announce that things happened
Application   →  the ready framework others talk to
```

---

## Related

- [lifecycle.md](./lifecycle.md)
- [first-classes.md](./first-classes.md)
- [container.md](./container.md) · [kernel.md](./kernel.md) · [service-providers.md](./service-providers.md)
- [configuration.md](./configuration.md) · [events.md](./events.md)
