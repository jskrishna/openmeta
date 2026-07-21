# AGENTS.md

## Cursor Cloud specific instructions

OpenMeta is a **pre-alpha, PHP-only Composer monorepo** (a WordPress content-modeling framework). There is no runnable web server or WordPress install in this repo yet; the "application" is the PHP framework, and the canonical end-to-end proof that it works is booting it via the core smoke test.

### Services / scope
- Only two packages currently have source + tests: `packages/core` and `packages/support`. The other package dirs (`validation`, `security`, `database`, `fields`, `api`, `ui`, `admin`, `builder`, `wordpress`, ...) are placeholders with just a `README.md` on `main`.
- The Node/frontend pipeline is intentionally stubbed: `package.json` `lint`/`build`/`test` scripts only `echo` a "not configured yet" message. There is no real frontend to run.

### How to run / lint / test (PHP)
- Standard commands live in root `composer.json` scripts: `composer phpcs`, `composer phpstan`, `composer phpunit`, `composer ci`, and per-package `composer test:core` / `composer test:support`.
- Framework boot ("hello world"): `php packages/core/tests/smoke.php` → prints `OK Core Bootstrap ... — Framework Booted`. This exercises Application → Kernel → Container → ServiceProviders → Config end-to-end.
- `composer phpcs` passes cleanly.

### Known pre-existing breakage on `main` (NOT an environment problem)
These fail identically in GitHub Actions CI on `main` — do not try to "fix" them as part of environment setup:
- `composer phpstan` aborts with `Path .../packages/validation/src does not exist` because `phpstan.neon` lists package paths not yet created.
- `composer phpunit` / `composer test:core` fatal with `Trait "OpenMeta\Tests\Phase12\AssertsPerformanceBudget" not found` because the `tests/Phase12/` infrastructure referenced by the `*/tests/Performance/*Test.php` files only exists on the `develop` branch.
- To run the working tests on `main`, exclude the two Performance tests, e.g.:
  `vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php $(find packages/core/tests packages/support/tests -name '*Test.php' ! -path '*/Performance/*')` → 65 tests / 188 assertions pass.
- The `develop` branch has more packages and the Phase12 test infra, so `composer ci` is expected to behave differently there.

### Environment notes
- PHP 8.3 CLI + Composer 2.x are provisioned at the system level (captured in the VM snapshot); the startup update script only refreshes Composer deps.
