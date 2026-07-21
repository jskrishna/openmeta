# Lifecycle

What happens from “start” to “application ready”.

---

## Purpose

The lifecycle is the **ordered boot story** of `@openmeta/core`. Every start should follow the same stages so packages can rely on when the container, config, and providers are available.

---

## Bootstrap sequence

```text
Load Config
    ↓
Create Container
    ↓
Register Core Services
    ↓
Register Providers
    ↓
Boot Providers
    ↓
Application Ready
```

Implemented by `Bootstrap::run()` (also `Application::boot()` / `Bootstrapper::boot()`).

---

## What each stage means

| Stage | Meaning |
| ----- | ------- |
| **Load Config** | Runtime settings available via ConfigRepository (`config/*.php` + overrides). |
| **Create Container** | Empty service container exists. |
| **Register Core Services** | Container, config, events, kernel, and application are bound. |
| **Register Providers** | Every package provider declares bindings. |
| **Boot Providers** | Every provider may start services that need a complete registry. |
| **Application Ready** | Kernel is Ready; `FrameworkBooted` dispatched; `isBooted()` is true. |

---

## Guarantees

- Register always completes for all providers before any boot runs
- After Application Ready, providers are not added as part of normal boot
- Core still knows nothing about database, fields, REST, GraphQL, admin, builder, or WordPress at any stage

---

## Related

- [architecture.md](./architecture.md)
- [kernel.md](./kernel.md)
- [service-providers.md](./service-providers.md)
- [configuration.md](./configuration.md)
- [container.md](./container.md)
- [events.md](./events.md)
