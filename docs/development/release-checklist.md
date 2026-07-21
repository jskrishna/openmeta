# Release Checklist

Run before tagging any `vX.Y.Z`. The whole list is automated by
`composer release:validate` — this document is the human-readable contract.

## Automated gate (`composer release:validate`)

- [ ] **Composer validation** — `composer validate --no-check-publish --strict`
- [ ] **Static analysis** — `composer phpstan` (level 5, no errors)
- [ ] **Coding standards** — `composer phpcs` (PSR-12, no errors)
- [ ] **Tests** — `composer phpunit` (all suites green)
- [ ] **Dependency audit** — `composer audit` (no known advisories)
- [ ] **Backward compatibility** — `composer bc:check` (no unintended breaks)

## Coverage & performance

- [ ] **Coverage** — `composer coverage && composer coverage:check` meets the threshold
- [ ] **Benchmarks** — `composer bench` shows no unexplained regression vs the last release

## Compatibility matrix

- [ ] PHP **8.3** and **8.4** green (CI matrix)
- [ ] Latest **WordPress** — `packages/wordpress` suite green
- [ ] Latest **Composer** 2.x
- [ ] Supported **database drivers** (memory + PDO)

## Packaging & metadata

- [ ] Every `packages/*/composer.json` validates and has the correct version
- [ ] `openmeta/framework` meta package requires the intended set
- [ ] `CHANGELOG.md` updated for the release
- [ ] `ROADMAP.md` / `release-milestones.md` reflect the shipped train
- [ ] Version bumped per [ADR-0019](../adr/ADR-0019-versioning.md) (SemVer) and [ADR-0027](../adr/ADR-0027-dx-first-roadmap.md)

## Backward-compatibility policy

- Patch/minor: `bc-check` must pass unchanged.
- Major: an accepted break is recorded by running `composer bc:update` and
  committing the new `quality/reports/api-baseline.json` alongside migration notes.

## Tag & publish

- [ ] Tag `vX.Y.Z` pushed → the `Release` workflow runs
- [ ] Artifacts / packages published as configured
