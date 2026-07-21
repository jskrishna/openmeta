# Configuration

What configuration is for — and how Core loads it.

---

## Purpose

**Configuration** is the structured set of runtime settings available when the application starts.

It answers: *What values should the framework and providers read during boot and afterward?*

The façade is **ConfigRepository** (`Contracts\ConfigRepositoryInterface`).

---

## Default files (`packages/core/config/`)

```text
config/
├── app.php
├── database.php
└── api.php
```

| File | Example keys | Notes |
| ---- | ------------ | ----- |
| `app.php` | `app.name`, `app.env`, `app.version` | Simple app defaults |
| `database.php` | `database.default` | Placeholder only (no DB connection in Core) |
| `api.php` | `api.enabled`, `api.prefix` | Placeholder only (no REST/GraphQL in Core) |

`ConfigLoader` maps each file to a top-level key (`app.php` → `app`).

`Bootstrapper::boot($overrides)` loads these files, then merges `$overrides` with `array_replace_recursive`.

---

## What it does

- Holds configuration as a nested document (logical sections and keys)
- Allows reading values by key (including nested / dot keys)
- Allows checking whether a key exists
- Allows updating values when the runtime needs to adjust settings
- Exposes the full config tree when something must inspect everything

---

## What configuration is not

- Not a substitute for the database
- Not user-facing settings UI (admin package later)
- Not secrets management or encryption
- Not WordPress options API wrapping (later, outside pure core spine)

---

## When it is loaded

Configuration is loaded **early** in the lifecycle — before providers register — so providers can read settings while binding and booting services.

See [lifecycle.md](./lifecycle.md).

---

## Who consumes it

- Bootstrapper (initial load)
- Application (exposes config after ready)
- Service providers (read settings while registering/booting)
- Other packages (via Application / container), never by making core depend on them

---

## Related

- [lifecycle.md](./lifecycle.md)
- [architecture.md](./architecture.md)
- [first-classes.md](./first-classes.md)
