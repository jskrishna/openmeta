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

- None.

### Changed

- None.

### Deprecated

- None.

### Removed

- None.

### Fixed

- None.

### Security

- None.

### Documentation

- None.

---

## [0.1.0-alpha] - 2026-07-21

First public pre-release. Milestone v0.1 — **core kernel only**.

### Added

- `packages/core` runtime spine: Bootstrap → Container → Config → Kernel → Service Provider.
- `OpenMeta\Core\Bootstrap\Bootstrap::init()` entrypoint (`VERSION = 0.1.0-alpha`).
- Minimal DI container, nested config repository, kernel boot sequence, and `ServiceProviderInterface`.
- Domain package layout stubs (`admin`, `api`, `database`, `fields`, `builder`, `ui`, `validation`, `security`, `support`).
- Core smoke test (`composer test:core`).
- Architecture-first documentation, ADRs, roadmap, security, and contribution guides.
- GitHub workflows, issue/PR templates, and community files.

### Changed

- Monorepo package map moved from feature stubs (graphql/react/cli/…) to domain packages.

### Deprecated

- None.

### Removed

- None.

### Fixed

- Documentation consistency (PHP 8.3+, roadmap phases, changelog honesty).

### Security

- Security policy and private vulnerability reporting enabled on GitHub.

### Documentation

- Package README contracts (Purpose, Responsibilities, Public APIs, Dependencies, Extension Points, Folder Structure).

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