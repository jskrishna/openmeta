# First classes (architecture only)

Canonical runtime types for `@openmeta/core`. **Decide what exists** — full behavior lands incrementally; do not expand into database/fields/API/admin/builder.

---

## Class inventory

| Class | Namespace (planned) | Role |
| ----- | ------------------- | ---- |
| **Application** | `OpenMeta\Core\Application\Application` | **Main entry point**: hold container, load config, start kernel, register/boot providers |
| **Kernel** | `OpenMeta\Core\Kernel\Kernel` | Ordered **register** then **boot** of service providers |
| **Container** | `OpenMeta\Core\Container\Container` | DI: bind / singleton / instance / get / has |
| **ServiceProvider** | `OpenMeta\Core\Providers\ServiceProvider` | Base + contract: `register(Container)`, `boot(Container)` |
| **EventDispatcher** | `OpenMeta\Core\Events\EventDispatcher` | Sync listen / dispatch for framework events |
| **ConfigRepository** | `OpenMeta\Core\Config\ConfigRepository` | Nested configuration store (get / set / has / all) |
| **Bootstrapper** | `OpenMeta\Core\Bootstrap\Bootstrapper` | Thin alias of `Application::boot()` |

### Supporting (not “first classes”, but allowed)

| Type | Role |
| ---- | ---- |
| `ContainerInterface` | Contract for Container |
| `EventDispatcherInterface` | Contract for EventDispatcher |
| `OpenMetaException` | Base exception |

### Explicitly not first classes (out of scope for core)

No `Database`, `Field`, `RestController`, `GraphQL`, `AdminScreen`, `Builder` types in this package.

---

## Name mapping (current → canonical)

| Canonical | Current code (if different) |
| --------- | --------------------------- |
| `Bootstrapper` | `Bootstrap\Bootstrap` (alias / rename target) |
| `ConfigRepository` | `Config\Repository` (alias / rename target) |
| `ServiceProvider` | `Providers\ServiceProviderInterface` |

Until rename PRs land, both names may appear; **canonical names above win** for new docs and new code.

---

## Responsibilities per class

### Application

- **Main entry point** via `Application::boot()`
- Hold container, load config, start kernel, register providers, boot providers
- Expose `container()`, `kernel()`, `config()`, `events()`, `isBooted()`, `version()`
- Do not load WordPress screens

### Kernel

- Accept provider list
- Phase 1: `register()` all providers
- Phase 2: `boot()` all providers
- Refuse provider changes after registration starts

### Container

- Store bindings and shared instances
- Resolve closures / concrete classes
- Throw typed resolution errors

### ServiceProvider

- `register`: bindings only (no side effects that need other providers)
- `boot`: start services after all registrations

### EventDispatcher

- `listen(eventClass, callable)`
- `dispatch(object $event): object`
- No persistence, no HTTP

### ConfigRepository

- Load/merge array config
- Dot-notation get/set/has
- No WP options UI

### Bootstrapper

- Thin alias of `Application::boot()` (kept for compatibility)
- Accept config array + provider list only (no domain modules)

---

## Lifecycle

```text
Application Starts
        │
        ▼
Load Configuration
        │
        ▼
Create Container
        │
        ▼
Register Providers
        │
        ▼
Boot Providers
        │
        ▼
Initialize Services
        │
        ▼
Framework Ready
```

### Step mapping

| Step | Owner |
| ---- | ----- |
| Application Starts | Caller invokes `Bootstrapper` |
| Load Configuration | `ConfigRepository` filled from input array |
| Create Container | `Container` (+ bind itself, config, events) |
| Register Providers | `Kernel` → each `ServiceProvider::register` |
| Boot Providers | `Kernel` → each `ServiceProvider::boot` |
| Initialize Services | Providers bind/resolve app services; `Application` composed |
| Framework Ready | `Application` returned; `isBooted() === true` |

---

## Sequence (conceptual)

```text
Bootstrapper
    → ConfigRepository (load)
    → Container (create)
    → EventDispatcher (bind)
    → Kernel (with providers)
    → Application (compose)
    → Kernel.register providers
    → Kernel.boot providers
    → return Application  // Framework Ready
```

---

## Non-goals

- Database connections or repositories
- Field registry / types
- REST / GraphQL
- Admin UI / Builder / WordPress screens

See package [README.md](../README.md) and [packages/README.md](../../README.md) dependency rules.
