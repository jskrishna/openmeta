# Phase 02 — Core Package Bootstrap

> Scope: **`packages/core` only.** Effort guide: 1–2 days.

---

## Purpose

Implement the minimum working framework runtime — every later package plugs into this spine. No database, fields, API, admin, builder, or WordPress bootstrap.

---

## Deliverables

| Component | Location | Status |
| --------- | -------- | ------ |
| Application | `src/Application/` | ✅ |
| Container | `src/Container/` | ✅ |
| Kernel | `src/Kernel/` | ✅ |
| Service Provider | `src/Providers/` | ✅ |
| Configuration | `src/Config/` + `config/` | ✅ |
| Event Dispatcher | `src/Events/` | ✅ |
| Bootstrap | `src/Bootstrap/` | ✅ |
| Exceptions | `src/Exceptions/` | ✅ |
| Contracts | `src/Contracts/` | ✅ |

Contract: [`packages/core/SPEC.md`](../../packages/core/SPEC.md).

---

## Boot sequence

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
Application Ready  (+ FrameworkBooted)
```

```php
use OpenMeta\Core\Bootstrap\Bootstrap;

$app = Bootstrap::run($configOverrides, $providers);
$app->isBooted(); // true
```

---

## Exit Criteria

| Criterion | Status |
| --------- | ------ |
| Framework successfully boots | ✅ smoke + `Bootstrap::run` |
| Unit tests pass | ✅ `composer test:core` / `composer ci` |
| No WordPress dependency yet | ✅ Core requires PHP `>=8.3` only |

---

## Verify

```bash
composer test:core
composer ci
```

---

## Dependencies

- Phase 00 (Planning)
- Phase 01 (Workspace Setup)

---

## Next

**v0.2.0-alpha** — Support → Validation → Security ([release plan](./release-milestones.md)).

---

## Summary

Phase 02 delivers `@openmeta/core` (`v0.1.0-alpha`): a WordPress-free bootable runtime with Application, Container, Kernel, Providers, Config, Events, Bootstrap, Exceptions, and Contracts.
