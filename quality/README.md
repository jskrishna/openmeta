# OpenMeta — Quality, QA & Performance

Production-readiness infrastructure for OpenMeta: testing, static analysis,
benchmarks, security auditing, coverage, backward-compatibility, and release
validation. **No framework features live here** — this is the quality gate.

## Layout

```text
quality/
  benchmarks/   run.php — dependency-free performance suite
  tools/        bc-check.php · coverage-check.php · release-validate.php
  coverage/     clover.xml (generated)
  reports/      benchmarks.json · api-baseline.json (generated)
tests/
  E2E/          cross-package end-to-end journeys
.github/workflows/  ci · tests · static-analysis · coding-standards · coverage · security · benchmarks · release
```

## Commands

```bash
composer ci                # phpstan → phpcs → phpunit (the gate)
composer qa                # ci + backward-compatibility check
composer phpunit           # all suites
composer bench             # performance benchmark suite → quality/reports/benchmarks.json
composer coverage          # phpunit with clover coverage (needs pcov/xdebug)
composer coverage:check    # enforce the coverage threshold
composer bc:check          # backward-compatibility check vs baseline
composer bc:update         # (re)write the public-API baseline
composer audit             # dependency security advisories
composer release:validate  # full release gate (validate + phpstan + phpcs + phpunit + audit + bc)
```

## Static analysis

- **PHPStan** (`phpstan.neon`, level 5) — no errors allowed.
- **PHPCS** (`phpcs.xml`, PSR-12) — no errors allowed.
- **Optional (documented, not required):** PHP CS Fixer, Psalm, and Infection
  (mutation testing) can be layered on top without changing the gate; add them
  as dev dependencies and a workflow when desired.

## Testing layers

Unit + Integration + WordPress-compat + Performance live **per package**
(`packages/*/tests`, see [packages/TESTING.md](../packages/TESTING.md)); this
directory adds **cross-package E2E journeys** (`tests/E2E`) and the benchmark
suite. Coverage target is **95%** (enforced via `coverage-check.php`; the CI
floor is configurable via the `COVERAGE_FLOOR` repo variable while coverage
ramps toward the target).

## Security

- `composer audit` (advisories) on every push + weekly schedule.
- Secret scanning (gitleaks) and dependency review (PRs) via
  [`.github/workflows/security.yml`](../.github/workflows/security.yml).

## Compatibility

- **PHP:** 8.3, 8.4 (the framework requires `>=8.3`; CI matrix covers both).
- **Composer:** latest 2.x.
- **WordPress:** latest (the WordPress adapter guards host calls with
  `function_exists()` and is exercised in `packages/wordpress/tests`).
- **Database drivers:** memory + PDO (see `packages/database`).

## Backward compatibility

`bc-check.php` snapshots every public type/method/constant into
`quality/reports/api-baseline.json` and fails on a removed type, removed public
member, or a stricter method signature. Refresh intentionally with
`composer bc:update` when a break is accepted (major version).

See [PERFORMANCE.md](./PERFORMANCE.md) and
[docs/development/release-checklist.md](../docs/development/release-checklist.md).
