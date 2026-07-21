# Phase 08 — REST API

> Scope: **`packages/api`.**

---

## Deliverables

| Component | Status |
| --------- | ------ |
| Routes | ✅ `Router` / `RouteRegistrar` |
| Controllers | ✅ `FieldValueController` |
| Resources | ✅ JSON + FieldValue + Collection |
| Authentication | ✅ Token + WP bridge |
| Authorization | ✅ Gate-backed `Authorizer` |

---

## Verify

```bash
composer test:api
composer ci
```

Contract: [`packages/api/SPEC.md`](../../packages/api/SPEC.md).

---

## Next

**v0.6.0-alpha** — Admin UI (+ UI kit).
