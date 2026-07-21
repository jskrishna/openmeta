# Service providers

What service providers are for — and the lifecycle every package must follow.

---

## Purpose

A **Service Provider** is the extension unit for core: a package (or core itself) teaches the application what services exist and when they may start.

It answers: *How does another package plug into core without core knowing about that package’s domain?*

**Every future OpenMeta package** (support, validation, security, database, fields, api, ui, admin, builder, …) registers through this system — not by reaching into Core internals.

---

## Lifecycle

```text
Register
   ↓
 Boot
```

Enforced by the **Kernel** for the ordered provider list:

1. Call `register()` on **every** provider  
2. Call `boot()` on **every** provider  

No provider may `boot` before all providers have `register`ed.

| Phase | Intent |
| ----- | ------ |
| **Register** | Bind services, contracts, and factories into the container. Stay free of work that requires *other* providers’ bindings to already exist. |
| **Boot** | Wire listeners, warm services, or perform startup that needs a **complete** container graph. |

Base class for packages: `OpenMeta\Core\Providers\ServiceProvider`  
Contract: `OpenMeta\Core\Contracts\ServiceProviderInterface`

---

## What providers must not do (in core’s model)

- Own database schema or field types inside `packages/core`
- Register REST/GraphQL routes as a *core* concern (other packages may boot those via *their* providers)
- Assume WordPress admin screens exist

Providers living in **other packages** may do domain work appropriate to that package — core only defines the **register → boot** contract.

---

## Relationship to other parts

| Part | Role |
| ---- | ---- |
| **Container** | Where providers put services |
| **Kernel** | When providers run (register → boot) |
| **Application** | What callers use after providers have finished |
| **Events** | Optional way providers announce or react at boot |
| **Bootstrapper** | Accepts the provider list at entry |

---

## Related

- [kernel.md](./kernel.md)
- [container.md](./container.md)
- [lifecycle.md](./lifecycle.md)
- [architecture.md](./architecture.md)
