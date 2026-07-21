# OpenMeta Roadmap

---

# Purpose

This roadmap provides a high-level overview of the planned evolution of OpenMeta.

While the complete implementation strategy is documented in the `/docs/roadmap` directory, this document serves as a quick reference for contributors, maintainers, and users to understand the project's current progress and long-term direction.

---

# Vision

OpenMeta aims to become a modern, extensible, and developer-friendly content modeling framework for WordPress.

The project is built using an **Architecture First** and **Documentation First** approach, ensuring that every major feature is carefully designed before implementation.

---

# Current Status

**Project Stage**

```text
Architecture & Documentation
████████████████████████████████████████ 100%

Core Bootstrap (v0.1.0-alpha)
████████████████████████████████████████ 100%

Implementation (Support → Builder)
██░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ ~10%

Testing (Core suite + CI)
████████████░░░░░░░░░░░░░░░░░░░░░░░░░░░░ ~30%

Stable Release (v1.0.0)
□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□ 0%
```

Current Focus:

- ✅ Phase 01.5 — Cursor rules (`.cursor/rules` + `context`)
- ✅ Project Architecture
- ✅ Documentation
- ✅ `v0.1.0-alpha` — Core
- ✅ `v0.2.0-alpha` — Support
- ✅ `v0.3.0-alpha` — Validation
- ✅ `v0.4.0-alpha` — Security
- ✅ `v0.5.0-alpha` — Database
- ✅ `v0.6.0-alpha` — Field Engine ⭐
- ✅ `v0.7.0-alpha` — REST API
- ✅ `v0.8.0-alpha` — Admin (+ UI)
- ✅ `v0.9.0-beta` — Builder (+ WordPress)
- ✅ Phase 12 — Testing (five-layer gate)
- ✅ Phase 13 — Release train documented
- ⏳ **v1.0.0** — Stable

### Package roadmap after Core

Authoritative version train: [docs/roadmap/release-milestones.md](docs/roadmap/release-milestones.md) · [phase-13-releases.md](docs/roadmap/phase-13-releases.md).

```text
v0.1.0-alpha
Core ✅

↓

v0.2.0-alpha
Support ✅

↓

v0.3.0-alpha
Validation ✅

↓

v0.4.0-alpha
Security ✅

↓

v0.5.0-alpha
Database ✅

↓

v0.6.0-alpha
Field Engine ✅

↓

v0.7.0-alpha
REST API ✅

↓

v0.8.0-alpha
Admin ✅

↓

v0.9.0-beta
Builder ✅

↓

v1.0.0
Stable ⏳
```

Track each train with a **GitHub milestone + project board** — see [`.github/MILESTONES.md`](.github/MILESTONES.md).

---

# Development Roadmap

## Phase 00 — Planning

Status: ✅ Completed

Deliverables

- Project vision
- Architecture
- Documentation
- ADRs
- Development standards
- Project roadmap

---

## Phase 01 — Bootstrap

Status: ✅ Completed (`v0.1.0-alpha`)

Objectives

- Repository setup
- Development environment
- Build configuration
- Continuous Integration (Composer → PHPStan → PHPCS → PHPUnit)
- Initial framework bootstrap (Application, Kernel, Container, Config, Providers)

---

## Phase 02 — Core Framework

Status: ✅ Completed (`packages/core`, `v0.1.0-alpha`)

Objectives

- Core services ✅
- Configuration ✅
- Dependency management (Container) ✅
- Event system ✅
- Extension infrastructure (Service Providers) ✅
- Bootstrap + Contracts + Exceptions ✅
- Exit: boots, unit tests green, **no WordPress dependency** ✅

See [docs/roadmap/phase-02-core.md](docs/roadmap/phase-02-core.md).

---

## Phase 02b — Support Package

Status: ✅ Completed (`packages/support`)

Objectives

- Arr, Str, Collections, Path, Filesystem ✅
- Env, UUID, Reflection, Helpers, Traits ✅
- Exit: zero business logic, pure utilities only ✅

See [docs/roadmap/phase-03-support.md](docs/roadmap/phase-03-support.md).

---

## Phase 02c — Validation Package

Status: ✅ Completed (`packages/validation`)

Objectives

- Validator, Rules, Messages, ErrorBag, Exceptions ✅
- Exit: validate arrays + objects; custom rules ✅

See [docs/roadmap/phase-04-validation.md](docs/roadmap/phase-04-validation.md).

---

## Phase 02d — Security Package

Status: ✅ Completed (`packages/security`)

Objectives

- Sanitization, Escaping, Permissions, Capabilities, Nonce ✅
- Exit: independent package, no UI ✅

See [docs/roadmap/phase-05-security.md](docs/roadmap/phase-05-security.md).

---

## Phase 03 — Database Layer

Status: ✅ Completed (`packages/database`, `v0.3.0-alpha`)

Objectives

- Connection, Schema, Migration, Repository, Query, Storage, Relationships ✅
- Exit: CRUD, migrations, repository abstraction ✅

See [docs/roadmap/phase-06-database.md](docs/roadmap/phase-06-database.md).

---

## Phase 04 — Field Engine

Status: ✅ Completed (`packages/fields`, `v0.4.0-alpha`)

Objectives

- Registry, Base Field, built-in types, Validation, Storage, Rendering ✅
- Exit: register / save / validate / render ✅

See [docs/roadmap/phase-07-fields.md](docs/roadmap/phase-07-fields.md).

---

## Phase 05 — REST API

Status: ✅ Completed (`packages/api`, `v0.5.0-alpha`)

Objectives

- Routes, Controllers, Resources, Authentication, Authorization ✅

See [docs/roadmap/phase-08-api.md](docs/roadmap/phase-08-api.md).

---

## Phase 06 — Admin UI

Status: ✅ Completed (`packages/ui` + `packages/admin`, `v0.6.0-alpha`)

Objectives

- Dashboard, Menus, Settings, Forms, Tables, Components ✅

See [docs/roadmap/phase-09-admin.md](docs/roadmap/phase-09-admin.md).
---

## Phase 03 — Database Layer

Status: ⏳ Planned

Objectives

- Storage abstraction
- Schema management
- Migrations
- Repository layer
- Query infrastructure

---

## Phase 04 — Field Engine

Status: ⏳ Planned

Objectives

- Field registry
- Validation
- Storage integration
- Default field library
- Field lifecycle

---

## Phase 05 — Administration UI

Status: ⏳ Planned

Objectives

- Dashboard
- Navigation
- Component library
- Settings
- Forms
- Accessibility

---

## Phase 06 — Field Builder

Status: ⏳ Planned

Objectives

- Visual field builder
- Drag-and-drop
- Field groups
- Templates
- Live validation

---

## Phase 07 — Advanced Fields

Status: ⏳ Planned

Objectives

- Relationship fields
- Repeater fields
- Flexible content
- Conditional logic
- Advanced field library

---

## Phase 08 — API Layer

Status: ⏳ Planned

Objectives

- REST API
- GraphQL
- Authentication
- Authorization
- API documentation

---

## Phase 09 — Integrations

Status: ⏳ Planned

Objectives

- WordPress integration
- Gutenberg
- Plugin ecosystem
- Import/Export
- Webhooks
- Developer SDK

---

## Phase 10 — Testing

Status: ⏳ Planned

Objectives

- Unit testing
- Integration testing
- API testing
- Performance testing
- Security testing
- Accessibility testing

---

## Phase 11 — Release

Status: ⏳ Planned

Objectives

- Release candidate
- Documentation review
- Stable release
- Versioning
- Migration guides

---

# Milestones

```text
✓ v0.1.0-alpha — Core

↓

✓ v0.2.0-alpha — Support

↓

✓ v0.3.0-alpha — Validation

↓

✓ v0.4.0-alpha — Security

↓

✓ v0.5.0-alpha — Database

↓

✓ v0.6.0-alpha — Field Engine

↓

✓ v0.7.0-alpha — REST API

↓

✓ v0.8.0-alpha — Admin

↓

✓ v0.9.0-beta — Builder

↓

□ v1.0.0 — Stable
```

Full exit criteria: [docs/roadmap/release-milestones.md](docs/roadmap/release-milestones.md) · [phase-13-releases.md](docs/roadmap/phase-13-releases.md).

---

# Release Strategy

Versioned release plan (authoritative — Phase 13):

```text
v0.1.0-alpha → Core
v0.2.0-alpha → Support
v0.3.0-alpha → Validation
v0.4.0-alpha → Security
v0.5.0-alpha → Database
v0.6.0-alpha → Field Engine
v0.7.0-alpha → REST API
v0.8.0-alpha → Admin
v0.9.0-beta  → Builder
v1.0.0       → Stable
```

Details: [docs/roadmap/release-milestones.md](docs/roadmap/release-milestones.md). Process: [docs/development/release-process.md](docs/development/release-process.md).

Maturity lifecycle:

```text
Planning → Development → Alpha trains → Beta → Stable → Maintenance
```

---

# Long-Term Goals

OpenMeta aims to provide:

- Modern content modeling
- Modular architecture
- Visual field builder
- Extensible framework
- Comprehensive APIs
- WordPress-native integration
- Developer-friendly tooling
- Enterprise-ready scalability
- Long-term backward compatibility

---

# Future Vision

Potential future initiatives include:

- AI-assisted content modeling
- Visual schema designer
- Workflow automation
- Real-time collaboration
- Additional integrations
- Cloud services
- Developer SDK enhancements
- Marketplace for extensions

These initiatives are exploratory and may evolve based on community feedback and project priorities.

---

# Success Criteria

The roadmap is considered successful when:

- All planned phases are completed.
- Architecture remains consistent.
- Documentation stays synchronized with implementation.
- Public APIs remain stable.
- Comprehensive test coverage is achieved.
- The project reaches a stable production release.

---

# Documentation

Detailed implementation planning is available in:

```text
docs/roadmap/
```

Additional architectural decisions can be found in:

```text
docs/adr/
```

---

# Best Practices

- Complete phases sequentially.
- Preserve architectural consistency.
- Keep documentation current.
- Avoid unnecessary technical debt.
- Validate each milestone before progressing.
- Prioritize maintainability over rapid feature development.

---

# Summary

The OpenMeta roadmap defines a structured, architecture-driven path from project planning to a stable production release. By following clearly defined development phases, measurable milestones, and documentation-first practices, the project aims to deliver a scalable, maintainable, and extensible framework for the WordPress ecosystem.