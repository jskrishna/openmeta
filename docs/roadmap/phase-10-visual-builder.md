# Phase 11 — Visual Builder (canonical number)

> Historical filename: `phase-10-visual-builder.md`. **Canonical phase = 11** ([ADR-0024](../adr/ADR-0024-post-rest-phase-order.md)).  
> Scope: **`packages/builder`**. Train: **`v0.10.0-beta`**.

---

## Deliverables

| Component | Status |
| --------- | ------ |
| Canvas | ✅ selection, layout, nodes |
| Drag & Drop | ✅ insert / move + keyboard a11y |
| Templates | ✅ register / apply / preview |
| Conditions | ✅ DSL eval + visible filter |
| Preview | ✅ live escaped field render |
| Admin mount | ✅ screen + menu via provider |

---

## Verify

```bash
composer test:builder
composer ci
```

---

## Next

**v1.0.0** — Stable.
