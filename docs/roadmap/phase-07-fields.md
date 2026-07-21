# Phase 07 — Field Engine

> Scope: **`packages/fields`.** Heart of OpenMeta.

---

## Deliverables

| Component | Status |
| --------- | ------ |
| Field Registry | ✅ |
| Base Field | ✅ |
| Text / Number / Boolean | ✅ |
| Repeater | ✅ |
| Relationship | ✅ |
| Validation bridge | ✅ |
| Storage | ✅ |
| Rendering | ✅ |

---

## Exit Criteria

| Criterion | Status |
| --------- | ------ |
| Register field | ✅ |
| Save field | ✅ |
| Validate field | ✅ |
| Render field | ✅ |

---

## Verify

```bash
composer test:fields
composer ci
```

Contract: [`packages/fields/SPEC.md`](../../packages/fields/SPEC.md).

---

## Next

**v0.5.0-alpha** — REST API.
