# Kernel

What the kernel is for — lifecycle only. No WordPress-specific logic.

---

## Purpose

The **Kernel** manages the framework lifecycle in a fixed order.

It answers: *Where are we in startup, and when may providers register / boot?*

---

## Lifecycle

```text
Bootstrap
    ↓
Initialize
    ↓
Ready
```

| Phase | Method | Meaning |
| ----- | ------ | ------- |
| **Bootstrap** | `bootstrap()` | Kernel prepared; container attached; providers may still be added |
| **Initialize** | `initialize()` | Service providers run **Register → Boot** |
| **Ready** | `ready()` | Lifecycle complete; Application may serve |

Call `run()` to execute all three phases in order.

Inside **Initialize**, the provider contract remains:

```text
Register
   ↓
 Boot
```

---

## What it does

- Tracks `KernelPhase` (`pending` → `bootstrap` → `initialize` → `ready`)
- Accepts service providers before Initialize
- Runs provider register then boot during Initialize
- Refuses adding providers after Initialize has started
- Holds a reference to the container
- Contains **no** WordPress hooks, plugins, or admin logic

---

## What it is not

- Not responsible for loading configuration files (Application / Config)
- Not responsible for HTTP, fields, or database
- Not a WordPress bootstrap file
- Not a long-running worker or cron system

---

## Related

- [lifecycle.md](./lifecycle.md)
- [service-providers.md](./service-providers.md)
- [container.md](./container.md)
- [architecture.md](./architecture.md)
