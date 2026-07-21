# Phase 05 — Security

> Scope: **`packages/security` only.** Independent. No UI.

---

## Deliverables

| Component | Status |
| --------- | ------ |
| Sanitization | ✅ `Sanitizer` |
| Escaping | ✅ `Escaper` |
| Permissions | ✅ `Permission` / `PermissionMap` / `Gate` |
| Capabilities | ✅ Array + WordPress checkers |
| Nonce | ✅ HMAC + WordPress handlers |

---

## Exit Criteria

| Criterion | Status |
| --------- | ------ |
| Independent package | ✅ (depends on `core` + `support` only) |
| No UI | ✅ |

---

## Verify

```bash
composer test:security
composer ci
```

Contract: [`packages/security/SPEC.md`](../../packages/security/SPEC.md).

---

## Next

**v0.3.0-alpha** — Database.
