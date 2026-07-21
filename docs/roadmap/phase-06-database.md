# Phase 06 — Database

> Scope: **`packages/database`.** Most important after Core.

---

## Deliverables

| Component | Status |
| --------- | ------ |
| Connection | ✅ PDO + Memory |
| Schema | ✅ Blueprint / Schema |
| Migration | ✅ Migrator |
| Repository | ✅ TableRepository |
| Query | ✅ QueryBuilder |
| Storage | ✅ TableStorage |
| Relationships | ✅ RelationLoader |

---

## Exit Criteria

| Criterion | Status |
| --------- | ------ |
| CRUD works | ✅ |
| Migrations work | ✅ |
| Repository abstraction works | ✅ |

---

## Verify

```bash
composer test:database
composer ci
```

Contract: [`packages/database/SPEC.md`](../../packages/database/SPEC.md).

---

## Next

**v0.4.0-alpha** — Field Engine.
