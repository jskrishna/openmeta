# SPEC — `@openmeta/core`

> **Implementation contract.** Implement against this document. [README](./README.md) is the short overview.

**Status:** ✅ Complete — `v0.1.0-alpha`

---

## Purpose

Minimum working framework runtime. Core owns the spine every other package plugs into — and nothing else (no database, fields, API, admin, builder, WordPress screens).

---

## Component map

```text
Application
    Responsibilities
        ↓
Container
    Responsibilities
        ↓
Kernel
    Responsibilities
        ↓
Providers
    Responsibilities
        ↓
Bootstrap
    Responsibilities
```

Supporting (not in the spine diagram, but required): **Config**, **Events**, **Contracts**, **Exceptions**.

---

## Application

### Responsibilities

- Act as the ready-application façade after boot
- Hold references to Container, Config, Kernel, Events
- Expose `container()`, `config()`, `kernel()`, `events()`, `isBooted()`, `version()`, `get()` / `has()`
- Provide step methods used by Bootstrap: `loadConfig`, `createContainer`, `registerCoreServices`, `registerProviders`, `bootProviders`, `ready`
- Remain free of domain logic

### Public contracts

- `Application` / `ApplicationInterface`
- `Application::boot()` → delegates to `Bootstrap::run()`

### Must not

- Register providers itself outside Kernel
- Load WordPress screens or domain packages

---

## Container

### Responsibilities

- Bind services (`bind`, `singleton`, `instance`)
- Resolve services (`resolve` / `get`)
- Support aliases
- Report `has()` and fail closed on missing bindings
- Serve as the shared object graph for all providers

### Public contracts

- `Container` / `ContainerInterface`

### Must not

- Know about fields, database, HTTP, or WordPress
- Auto-resolve constructor graphs yet (future scope)

```text
Future (not now): Auto-resolution · Deferred services
```

---

## Kernel

### Responsibilities

- Manage framework lifecycle only:

```text
Bootstrap → Initialize → Ready
```

- During Initialize: run all providers **Register**, then all providers **Boot**
- Accept providers before Initialize; refuse changes after
- Expose `phase()`, `isReady()`, `providers()`, `container()`

### Public contracts

- `Kernel` / `KernelInterface` / `KernelPhase`

### Must not

- Load config from disk (Application / Config)
- Contain WordPress-specific bootstrap logic
- Own domain services

---

## Providers

### Responsibilities

- Define the extension unit for every future package
- Enforce two phases:

```text
Register  →  bind into Container only
    ↓
Boot      →  start / wire after all registrations
```

- Provide abstract `ServiceProvider` base for packages to extend
- Guarantee no provider boots before all providers have registered

### Public contracts

- `ServiceProvider` / `ServiceProviderInterface`

### Must not

- Put domain schema, REST, or admin screens inside Core providers
- Resolve other providers’ bindings during `register()`

---

## Bootstrap

### Responsibilities

- Own the canonical boot sequence and return a ready `Application`:

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

- Dispatch `FrameworkBooted` when ready
- Accept config overrides + provider list only (no domain modules)

### Public contracts

- `Bootstrap::run()`
- `Bootstrapper::boot()` (alias)

### Must not

- Connect to databases or register HTTP routes
- Embed WordPress plugin bootstrap concerns

---

## Supporting components

### Config

**Responsibilities:** load `config/*.php`, merge overrides, dot-notation get/set/has.  
**Contracts:** `ConfigRepository`, `ConfigRepositoryInterface`, `ConfigLoader`.

### Events

**Responsibilities:** sync listen/dispatch; emit `FrameworkBooted`.  
**Contracts:** `EventDispatcher`, `EventDispatcherInterface`, `FrameworkBooted`.

### Contracts / Exceptions

**Responsibilities:** stable `OpenMeta\Core\Contracts\*` surfaces; typed failures (`OpenMetaException`, `BindingResolutionException`).

---

## Public Contracts (package index)

| API | Component |
| --- | --------- |
| `Application` / `ApplicationInterface` | Application |
| `Container` / `ContainerInterface` | Container |
| `Kernel` / `KernelInterface` / `KernelPhase` | Kernel |
| `ServiceProvider` / `ServiceProviderInterface` | Providers |
| `Bootstrap::run()` / `Bootstrapper::boot()` | Bootstrap |
| `ConfigRepository` / `ConfigLoader` | Config |
| `EventDispatcher` / `FrameworkBooted` | Events |

---

## Internal Components

| Component | Location |
| --------- | -------- |
| Application | `src/Application/` |
| Container | `src/Container/` |
| Kernel | `src/Kernel/` |
| Providers | `src/Providers/` |
| Bootstrap | `src/Bootstrap/` |
| Config | `src/Config/` + `config/` |
| Events | `src/Events/` |
| Contracts | `src/Contracts/` |
| Exceptions | `src/Exceptions/` |

---

## Folder Structure

```text
packages/core/
├── config/
├── docs/
├── src/
│   ├── Application/
│   ├── Bootstrap/
│   ├── Config/
│   ├── Container/
│   ├── Contracts/
│   ├── Events/
│   ├── Exceptions/
│   ├── Kernel/
│   └── Providers/
├── tests/
├── README.md
└── SPEC.md
```

---

## Dependency Rules

| Rule | Detail |
| ---- | ------ |
| Required | PHP `>=8.3` only |
| Forbidden | Any other OpenMeta package |
| Consumers | Every other package must depend on `core` |

---

## Lifecycle

Package boot (Bootstrap responsibilities):

```text
Load Config → Create Container → Register Core Services
→ Register Providers → Boot Providers → Application Ready
```

Kernel phases:

```text
Bootstrap → Initialize (Register → Boot) → Ready
```

---

## Extension Points

- Pass `ServiceProvider` classes into `Bootstrap::run($config, $providers)`
- Bind/alias during `register()`; wire during `boot()`
- Listen for `FrameworkBooted`
- Merge config overrides at boot

---

## Performance

- No DB/HTTP/heavy I/O during Core boot
- In-memory container; avoid unnecessary singletons
- Config files loaded once per boot

---

## Security

- Do not log secrets from config
- No caps/nonces in Core (`security` package later)
- Fail closed on missing bindings

---

## Testing Strategy

| Layer | Covers |
| ----- | ------ |
| **Unit** | Application, Container, Kernel, Providers, Bootstrap, Config, Event Dispatcher, Exceptions |
| **Integration** | Full `Bootstrap::run` / smoke path |
| **WordPress compatibility** | N/A for Core spine today (no WP bootstrap) |
| **Performance** | Boot budget — keep Core boot free of DB/HTTP I/O |

```bash
composer test:core
composer ci
```

See [packages/TESTING.md](../../TESTING.md) (Phase 12 gate).

---

## Future Scope

- Container auto-resolution
- Deferred providers
- Richer core event catalog
- Never: fields, database, REST, admin UI inside Core
