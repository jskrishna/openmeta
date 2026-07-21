# Phase 10 — Admin UI (canonical number)

> Historical filename: `phase-09-admin.md`. **Canonical phase = 10** ([ADR-0024](../adr/ADR-0024-post-rest-phase-order.md)).  
> Scope: **`packages/ui` + `packages/admin`**. Train: **`v0.9.0-alpha`** (after WordPress Adapter `v0.8`).

---

## Deliverables

| Component | Status |
| --------- | ------ |
| Components (UI kit) | ✅ Tokens, Primitives, Card/Form/Table |
| Dashboard | ✅ |
| Menus | ✅ |
| Settings | ✅ |
| Forms | ✅ (nonce + validation) |
| Tables | ✅ (paginated) |

---

## Verify

```bash
composer test:ui
composer test:admin
composer ci
```

---

## Next

**v0.9.0-beta** — Builder.
