# Phase 03 — Support Package

> Scope: **`packages/support` only.** Reusable utilities.

---

## Purpose

Shared, framework-agnostic helpers so domain packages do not reinvent Arr/Str/Collections/Path/Filesystem/Env/UUID/Reflection/Traits.

---

## Deliverables

| Component | Status |
| --------- | ------ |
| Collections | ✅ `Collection` |
| Arr | ✅ |
| Str | ✅ |
| Filesystem | ✅ `FilesystemInterface` / `LocalFilesystem` |
| Path | ✅ |
| UUID | ✅ v4 |
| Helpers | ✅ |
| Reflection | ✅ `Reflector` |
| Traits | ✅ `Conditionable` |
| Environment | ✅ `Env` (supporting) |
| SupportServiceProvider | ✅ |

---

## Exit Criteria

| Criterion | Status |
| --------- | ------ |
| Zero business logic | ✅ |
| Pure utilities only | ✅ |
| Depends only on `core` among OM packages | ✅ |
| No WordPress dependency | ✅ |
| Unit + integration tests | ✅ |
| `composer ci` green | ✅ |

---

## Verify

```bash
composer test:support
composer ci
```

Contract: [`packages/support/SPEC.md`](../../packages/support/SPEC.md).

---

## Next

Validation package (still **v0.2.0-alpha** train), then Security.
