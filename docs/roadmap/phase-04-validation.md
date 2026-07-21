# Phase 04 — Validation

> Scope: **`packages/validation` only.**

---

## Deliverables

| Component | Status |
| --------- | ------ |
| Validator | ✅ |
| Rules (+ Rule Engine / Registry) | ✅ |
| Messages | ✅ |
| ErrorBag | ✅ |
| Exceptions | ✅ |

---

## Exit Criteria

| Criterion | Status |
| --------- | ------ |
| Validate arrays | ✅ |
| Validate objects | ✅ |
| Custom rules supported | ✅ `Validation::extend` / `RuleInterface` |

---

## Verify

```bash
composer test:validation
composer ci
```

Contract: [`packages/validation/SPEC.md`](../../packages/validation/SPEC.md).

---

## Next

Security package (still **v0.2.0-alpha** foundation), then Database (**v0.3.0-alpha**).
