# Tests

Automated tests for OpenMeta live here (and/or beside packages as suites grow).

> **Pre-alpha:** Layout is a scaffold. Real PHPUnit / JS suites land in Phase 10 — see [docs/testing/](../docs/testing/).

## Planned layout

```text
tests/
├── README.md
├── bootstrap.php          # WP / Composer test bootstrap (TBD)
├── Unit/                  # Pure unit tests
├── Integration/           # WordPress integration tests
└── Fixtures/              # Shared fixtures
```

## Running (target)

```bash
composer test
# or
vendor/bin/phpunit
```

CI: `.github/workflows/tests.yml`

## Related

- [docs/testing/](../docs/testing/)
- [docs/development/testing-workflow.md](../docs/development/testing-workflow.md)
- `phpcs.xml`, `phpstan.neon` (quality gates, not tests)
