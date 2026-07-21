# Changelog

All notable changes to the OpenMeta project will be documented in this file.

This project follows the principles of **Keep a Changelog** and adheres to **Semantic Versioning (SemVer)** for all releases.

---

# Purpose

The changelog provides a transparent history of OpenMeta's evolution, allowing contributors and users to understand what has changed between releases.

It records:

- New features
- Improvements
- Bug fixes
- Performance enhancements
- Security updates
- Breaking changes
- Deprecations
- Documentation updates

---

# Versioning Policy

OpenMeta follows Semantic Versioning.

```text
MAJOR.MINOR.PATCH

Example

1.0.0
```

## Major

Breaking architectural or API changes.

Examples:

- Removed APIs
- Database changes
- Breaking extension interfaces

---

## Minor

Backward-compatible new functionality.

Examples:

- New Field Types
- New APIs
- UI improvements
- New integrations

---

## Patch

Backward-compatible bug fixes.

Examples:

- Security fixes
- Performance improvements
- Documentation corrections
- Minor bug fixes

---

# Changelog Format

Each release should contain the following sections when applicable.

```text
Version

Added

Changed

Deprecated

Removed

Fixed

Security

Documentation
```

---

# Release History

## [Unreleased]

### Added

- None (post-`v0.9.0-beta` work toward **v1.0.0** Stable).

### Changed

- Phase 13 release train is authoritative (one package theme per alpha; Builder at beta; then Stable).

### Next

```text
v1.0.0 — Stable
```

---

## [0.9.0-beta] - 2026-07-21

**Builder beta** — visual field builder + WordPress plugin mount.

### Added

- `packages/builder` (`v0.9.0-beta`): Canvas, Drag & Drop, Templates, Conditions, Preview.
- `packages/wordpress` (`v0.9.0-beta`): Plugin bootstrap, Hooks, Filters, Admin pages, Capabilities, Gutenberg, REST bridge.
- Root `openmeta.php` plugin entry.
- Phase 12 five-layer testing gate on every package.

### Documentation

- [phase-13-releases.md](docs/roadmap/phase-13-releases.md), release train realigned.

---

## [0.8.0-alpha] - 2026-07-21

**Admin** — WordPress admin experience + UI kit.

### Added

- `packages/ui` (`v0.8.0-alpha`): Tokens, Primitives, Card/Form/DataTable, Theme.
- `packages/admin` (`v0.8.0-alpha`): Dashboard, Menus, Screens, Forms, Tables, Settings.

---

## [0.7.0-alpha] - 2026-07-21

**REST API** — public HTTP API layer.

### Added

- `packages/api` (`v0.7.0-alpha`): Router, RestKernel, Controllers, Resources, Token + WP Auth, Authorizer.

---

## [0.6.0-alpha] - 2026-07-21

**Field Engine** — content modeling heart.

### Added

- `packages/fields` (`v0.6.0-alpha`): Registry, types (text/number/boolean/repeater/relationship), validation, storage, rendering, REST/GQL maps.

---

## [0.5.0-alpha] - 2026-07-21

**Database** — schema and persistence spine.

### Added

- `packages/database` (`v0.5.0-alpha`): Connections (PDO + Memory), Schema, Migrator, QueryBuilder, Repository, Relations.

---

## [0.4.0-alpha] - 2026-07-21

**Security** — capabilities, nonces, sanitize/escape.

### Added

- `packages/security` (`v0.4.0-alpha`): Gate, PermissionMap, Array + WP capability checkers, Nonce, Sanitizer, Escaper.

---

## [0.3.0-alpha] - 2026-07-21

**Validation** — rule engine and error bags.

### Added

- `packages/validation` (`v0.3.0-alpha`): Validation facade, RuleEngine, ErrorBag, MessageBag, built-in rules.

---

## [0.2.0-alpha] - 2026-07-21

**Support** — shared helpers.

### Added

- `packages/support` (`v0.2.0-alpha`): Arr, Str, Collection, Path, Filesystem, Env, Uuid, Reflector, Helpers, Conditionable.

---

## [0.1.0-alpha] - 2026-07-21

**First Core Bootstrap pre-release.** Minimum working framework — no database, fields, API, or WordPress integration.

### Completed

- ✅ Core Bootstrap  
- ✅ Container (bind / singleton / resolve / aliases)  
- ✅ Config (`app` / `database` / `api` + ConfigRepository)  
- ✅ Kernel (Bootstrap → Initialize → Ready)  
- ✅ Providers (Register → Boot)  
- ✅ Application + Bootstrap sequence → Application Ready  
- ✅ PHPUnit suite + smoke test  
- ✅ CI: Composer Install → PHPStan → PHPCS → PHPUnit → Success  

### Added

- `packages/core` runtime: Contracts, Application, Bootstrap, Container, Config, Kernel, Providers, Events.
- Entrypoints: `Bootstrap::run()`, `Application::boot()`, `Bootstrapper::boot()`.
- Default config files under `packages/core/config/`.
- PHPUnit tests: Application, Container, Provider, Config, Kernel, Bootstrap.
- GitHub Actions CI sequence (PHP 8.3 / 8.4).
- Domain package layout stubs (`support`, `validation`, `security`, `database`, `fields`, `api`, `ui`, `admin`, `builder`).
- Architecture-first documentation, ADRs, roadmap, security, and contribution guides.

### Changed

- Monorepo package map aligned to Core-first dependency rules (`core` depends on zero other OpenMeta packages).

### Deprecated

- `Bootstrap::init()` — use `Bootstrap::run()`.
- Legacy Kernel `boot()` / `isBooted()` — prefer `run()` / `isReady()`.

### Removed

- None.

### Fixed

- Documentation and tooling consistency (PHP 8.3+, phpstan/phpcs green).

### Security

- Security policy and private vulnerability reporting enabled on GitHub.

### Documentation

- Core milestone doc, build order, and [`.github/MILESTONES.md`](.github/MILESTONES.md) package-tracking process.

### Next

```text
Support → Validation → Security → Database → Fields → API → Admin → Builder → v1.0.0
```

---

# Breaking Changes

Major releases should clearly document all breaking changes.

Each breaking change should include:

- Description
- Reason
- Migration guidance
- Related documentation
- Related ADR (if applicable)

Example

```text
Breaking Change

Description

Migration Steps

References
```

---

# Deprecation Policy

Deprecated functionality should:

- Be clearly documented.
- Include migration guidance.
- Remain supported for at least one major release unless a security issue requires immediate removal.
- Be removed only in a future major release.

---

# Security Updates

Security-related releases should include:

- Vulnerability summary
- Impact
- Severity
- Resolution
- Upgrade recommendations

Sensitive implementation details should not be disclosed until fixes are publicly available.

---

# Documentation Updates

Documentation changes should also be tracked.

Examples:

- New guides
- Architecture updates
- ADR additions
- API documentation
- Examples
- Tutorials

---

# Release Process

Each release should follow this workflow.

```text
Development

↓

Testing

↓

Documentation Review

↓

Version Assignment

↓

Release Notes

↓

Git Tag

↓

Public Release

↓

Maintenance
```

---

# Release Checklist

Before publishing a release, verify:

- [ ] Documentation updated.
- [ ] CHANGELOG updated.
- [ ] Version number assigned.
- [ ] Migration guide completed.
- [ ] Tests passing.
- [ ] Security review completed.
- [ ] Performance verified.
- [ ] Release notes prepared.
- [ ] Git tag created.
- [ ] Release artifacts generated.

---

# Best Practices

- Update the changelog for every release.
- Keep entries concise and descriptive.
- Record only user-visible changes.
- Clearly identify breaking changes.
- Link related documentation where appropriate.
- Preserve historical release information.
- Never rewrite published release notes.

---

# Summary

The OpenMeta changelog provides a complete, transparent history of the project's evolution, ensuring contributors and users can confidently understand new features, improvements, fixes, and architectural changes across every release.