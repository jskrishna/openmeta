# Phase 12 — Testing (every package)

> **Release / QA contract.** Har package ke liye yeh **5 layers** complete honi chahiye — pehle next release train mat claim karo.

Related: [`docs/testing/`](../docs/testing/) · [`docs/roadmap/phase-12-testing.md`](../docs/roadmap/phase-12-testing.md)

---

## Rule

```text
Every package
        ↓
Unit
        ↓
Integration
        ↓
WordPress
        ↓
Performance
        ↓
Security
        ↓
Package ✅
```

Har package: `packages/<name>/tests/{Unit,Integration,WordPress,Performance,Security}/` me kam se kam ek `*Test.php`.

Matrix compliance: `tests/Phase12/MatrixComplianceTest.php`.

---

## 1. Unit

Isolated classes — no full WP boot when avoidable.  
**Location:** `packages/<name>/tests/Unit/`

## 2. Integration

Provider boot + critical cross-component paths.  
**Location:** `packages/<name>/tests/Integration/`

## 3. WordPress

Real WP APIs when present; fail-closed / N/A documented for pure libs.  
**Location:** `packages/<name>/tests/WordPress/`

## 4. Performance

Budget assertions on hot paths (`AssertsPerformanceBudget`).  
**Location:** `packages/<name>/tests/Performance/`

## 5. Security

Authz deny, escaping, injection guards, nonce / capability fail-closed.  
**Location:** `packages/<name>/tests/Security/`

---

## Per-package matrix

| Package | Unit | Integration | WordPress | Performance | Security |
| ------- | ---- | ----------- | --------- | ----------- | -------- |
| Core | ✅ | ✅ boot | N/A (agnostic) | ✅ boot budget | ✅ typed errors |
| Support | ✅ | ✅ provider | N/A | ✅ Arr | ✅ Path safety |
| Validation | ✅ | ✅ engine→bag | N/A | ✅ bulk | ✅ unknown rules |
| Security | ✅ Gate | ✅ Nonce | ✅ WP fail-closed | ✅ cap checks | ✅ escape + deny |
| Database | ✅ QB | ✅ schema/query | ✅ memory w/o `$wpdb` | ✅ selects | ✅ injection guard |
| Fields | ✅ registry | ✅ validate→save→load | ✅ w/o WP | ✅ registry | ✅ render escape |
| REST | ✅ Resource / Router | ✅ Kernel dispatch | N/A (WP mount later) | ✅ match budget | ✅ authz deny |
| API | ✅ Resource | ✅ REST health | ✅ WP auth fail-closed | ✅ dispatch | ✅ 401 deny |
| UI | ✅ Button | ✅ Theme | N/A | ✅ Card render | ✅ Notice escape |
| Admin | ✅ Menus | ✅ Screen | ✅ screens w/o WP | ✅ dashboard | ✅ cap gate |
| Builder | ✅ Canvas | ✅ save pipeline | ✅ admin slot | ✅ canvas scale | ✅ save authz |
| Wordpress | ✅ Requirements | ✅ Plugin boot | ✅ Native runtime | ✅ boot | ✅ cap seed |

---

## Commands

```bash
composer ci                 # PHPStan + PHPCS + full suite (defaultTestSuite=ci)
composer test:phase12       # five-layer gates + matrix
composer test:phase12:unit
composer test:phase12:integration
composer test:phase12:wordpress
composer test:phase12:performance
composer test:phase12:security
composer test:<package>     # per-package suite
```

---

## Definition of done

1. Five layer folders exist with ≥1 test each (or explicit N/A behavior tested)  
2. Matrix compliance green  
3. `composer ci` green  
4. Budgets documented in Performance tests  

---

## CI

```text
Composer Install → PHPStan → PHPCS → PHPUnit (ci suite)
        ↓
composer test:phase12 (layer filter / PR check)
        ↓
WP matrix job (when harness exists — expands WordPress layer)
```
