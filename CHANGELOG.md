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

> Pre-Alpha — no public release has been published yet.

### Added

- Project architecture and documentation foundation.
- ADR set documenting core architectural decisions.
- Contribution, security, roadmap, and technology stack documents.

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

- Planning-phase documentation published under `docs/` and top-level project guides.

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