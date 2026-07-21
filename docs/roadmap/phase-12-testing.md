# Phase 12 — Testing

> Scope: **every package** — Unit · Integration · WordPress · Performance · Security (`v1.0.0` gate).

---

## Deliverables

| Layer | Status |
| ----- | ------ |
| Unit | ✅ all 11 packages |
| Integration | ✅ all 11 packages |
| WordPress | ✅ bridges / N/A documented |
| Performance | ✅ budgets via `AssertsPerformanceBudget` |
| Security | ✅ deny / escape / injection gates |
| Matrix compliance | ✅ `tests/Phase12/MatrixComplianceTest.php` |

---

## Layout

```text
packages/<name>/tests/
  Unit/
  Integration/
  WordPress/
  Performance/
  Security/
```

Contract: [packages/TESTING.md](../../packages/TESTING.md)

---

## Verify

```bash
composer test:phase12
composer test:phase12:unit
composer test:phase12:integration
composer test:phase12:wordpress
composer test:phase12:performance
composer test:phase12:security
composer ci
```

---

## Next

**v1.0.0** — Stable.
