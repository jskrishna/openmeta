# Container

What the dependency injection container is for — and which features are in scope now.

---

## Purpose

The **Container** is the shared registry of services for a running OpenMeta application.

It answers: *Given an id (usually a class or contract name), what object should the framework use?*

---

## Features (v0.1)

| Feature | Status | Notes |
| ------- | ------ | ----- |
| **Bind** | ✅ | Transient or shared factory / class / instance |
| **Singleton** | ✅ | One shared instance per container |
| **Resolve** | ✅ | `resolve()` / `get()` — alias-aware |
| **Aliases** | ✅ | Alternate ids for the same abstract |
| **Auto-resolution** | ⏳ Future | Constructor injection via reflection |
| **Deferred services** | ⏳ Future | Lazy / deferred provider loading |

---

## What it does

- Accepts **bindings**: “when something asks for X, provide Y”
- Distinguishes **shared** services (one instance) from **new** resolutions when that matters
- Allows an already-built object to be registered as the canonical instance
- Maps **aliases** onto abstracts
- Reports whether a binding exists
- Returns the resolved service when asked
- Fails clearly when something is requested that was never registered (or on circular aliases)

---

## What it is not

- Not a database
- Not a router or HTTP layer
- Not a place for field or admin business rules
- Not a global variable dump — bindings should be intentional and documented by providers

---

## Who uses it

| Actor | Relationship |
| ----- | ------------ |
| **Service providers** | Register bindings during `register` |
| **Kernel** | Passes the container into providers |
| **Application** | Exposes the container after the framework is ready |
| **Other packages** | Resolve services they registered (or core contracts) |

---

## Related

- [service-providers.md](./service-providers.md)
- [kernel.md](./kernel.md)
- [architecture.md](./architecture.md)
