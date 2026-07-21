# Kernel/

Lifecycle manager only — no WordPress logic.

## Lifecycle

```text
Bootstrap
    ↓
Initialize   ← Register → Boot (service providers)
    ↓
Ready
```

| Phase | Method | Role |
| ----- | ------ | ---- |
| **Bootstrap** | `bootstrap()` | Prepare kernel; accept providers |
| **Initialize** | `initialize()` | Register then boot all providers |
| **Ready** | `ready()` | Kernel ready for Application use |

Full run: `run()` → Bootstrap → Initialize → Ready.

## Code

- `Kernel.php`
- `KernelPhase.php`
