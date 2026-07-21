# OpenMeta Roadmap

---

# Purpose

This roadmap provides a high-level overview of the planned evolution of OpenMeta.

While the complete implementation strategy is documented in the `/docs/roadmap` directory, this document serves as a quick reference for contributors, maintainers, and users to understand the project's current progress and long-term direction.

---

# Vision

OpenMeta aims to become a modern, extensible **PHP content-modeling framework** with a **WordPress-first adapter** — not “only a plugin.” Domain packages stay host-independent; Wordpress/Admin/Builder/GraphQL/CLI mount on the foundation (Core → … → Field Engine → REST).

The project is built using an **Architecture First** and **Documentation First** approach, ensuring that every major feature is carefully designed before implementation.

---

# Current Status

**Project Stage**

```text
Architecture & Documentation
████████████████████████████████████████ 100%

Core Bootstrap (v0.1.0-alpha)
████████████████████████████████████████ 100%

Implementation (WP Adapter → … → v1.0)
███░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ ~20%

Testing (continuous five-layer gate)
████████████░░░░░░░░░░░░░░░░░░░░░░░░░░░░ ~30%

Stable Release (v1.0.0)
□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□□ 0%
```

Foundation (Core → REST) is in place. Next production train: **WordPress Adapter**.
Current Focus:

- ✅ Phase 01.5 — Cursor rules (`.cursor/rules` + `context`)
- ✅ Project Architecture + Documentation
- ✅ Foundation train through **REST** (`v0.1`–`v0.7`)
- ⏳ **Phase 09 — WordPress Adapter** (`v0.8.0-alpha`) ← next
- ⏳ Phase 10 — Admin UI
- ⏳ Phase 11 — Visual Builder
- ⏳ Phase 12 — GraphQL
- ⏳ Phase 13 — CLI
- ⏳ Phase 14 — Testing & QA (also continuous gate)
- ⏳ Phase 15 — **v1.0.0** Stable

Post-REST order (Accepted — [ADR-0024](docs/adr/ADR-0024-post-rest-phase-order.md)):

```text
WordPress Adapter → Admin UI → Visual Builder → GraphQL → CLI → Testing & QA → v1.0
```

### Package roadmap after Core

Authoritative version train: [docs/roadmap/release-milestones.md](docs/roadmap/release-milestones.md) · [phase-15-releases.md](docs/roadmap/phase-15-releases.md).

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
REST (framework HTTP) ✅

↓

v0.8.0-alpha
WordPress Adapter ⏳

↓

v0.9.0-alpha
Admin UI

↓

v0.10.0-beta
Visual Builder

↓

v0.11.0-beta
GraphQL

↓

v0.12.0-beta
CLI

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

Status: ✅ Completed (`packages/database`, `v0.5.0-alpha`)

Objectives

- Connection, Schema, Migration, Repository, Query, Storage, Relationships ✅
- Exit: CRUD, migrations, repository abstraction ✅

See [docs/roadmap/phase-06-database.md](docs/roadmap/phase-06-database.md).

---

## Phase 04 — Field Engine

Status: ✅ Completed (`packages/fields`, `v0.6.0-alpha`)

Objectives

- Registry, Factory, Manager, Types, Validation bridge, Storage/Rendering contracts ✅
- Exit: register / save / validate / hydrate ✅

See [docs/roadmap/phase-07-fields.md](docs/roadmap/phase-07-fields.md).

---

## Phase 05–07 — (see package train)

Historical docs sometimes numbered Validation/Security/Database differently. Canonical package train:

- Phase 03 Support · Phase 04 Validation · Phase 05 Security · Phase 06 Database · Phase 07 Fields  

(Completed under `v0.2`–`v0.6` — see Current Focus above.)

---

## Phase 08 — REST (framework HTTP)

Status: ✅ Completed (`packages/rest`, `v0.7.0-alpha`)

Objectives

- Framework-independent Router, Request/Response, Middleware, Resources
- Auth contracts + Security Gate authorization bridge
- Validation + Database pagination integration
- **No** WordPress REST mount in this package

See [docs/roadmap/phase-08-api.md](docs/roadmap/phase-08-api.md) · [packages/rest/SPEC.md](packages/rest/SPEC.md).

---

## Phase 09 — WordPress Adapter

Status: ⏳ Next (`v0.8.0-alpha`)

Objectives

- Plugin bootstrap / lifecycle bridges
- Hooks, filters, capabilities seed
- Mount `@openmeta/rest` onto WP REST
- Gutenberg / admin slot glue (no domain engines inside Wordpress)

See [docs/roadmap/phase-11-wordpress-integration.md](docs/roadmap/phase-11-wordpress-integration.md) (historical filename; **Phase = 09**) · [packages/wordpress/SPEC.md](packages/wordpress/SPEC.md).

---

## Phase 10 — Admin UI

Status: ⏳ Planned (`v0.9.0-alpha`)

Objectives

- Dashboard, menus, screens, forms, tables, settings
- Consume Fields + Security + Rest; render via UI package
- Require WordPress adapter bridges for production mount

See [docs/roadmap/phase-09-admin.md](docs/roadmap/phase-09-admin.md) (historical filename; **Phase = 10**).

---

## Phase 11 — Visual Builder

Status: ⏳ Planned (`v0.10.0-beta`)

Objectives

- Canvas, drag-and-drop, templates, conditions, preview
- Save pipeline through Fields + Validation + Security (nonce/caps)
- Admin slot via WordPress adapter

See [docs/roadmap/phase-10-visual-builder.md](docs/roadmap/phase-10-visual-builder.md) (historical filename; **Phase = 11**).

---

## Phase 12 — GraphQL

Status: ⏳ Planned (`v0.11.0-beta`)

Objectives

- GraphQL type maps / resolvers on Field Engine contracts
- Reuse Security + Validation; host mount via adapter

See [docs/roadmap/phase-12-graphql.md](docs/roadmap/phase-12-graphql.md).

---

## Phase 13 — CLI

Status: ⏳ Planned (`v0.12.0-beta`)

Objectives

- Developer CLI over Core container
- Inspect / migrate / health commands (WP-optional)

See [docs/roadmap/phase-13-cli.md](docs/roadmap/phase-13-cli.md).

---

## Phase 14 — Testing & QA

Status: ⏳ Ongoing + pre-v1.0 hardening

Objectives

- Five-layer gate on every package
- Matrix compliance before Stable

See [docs/roadmap/phase-14-testing.md](docs/roadmap/phase-14-testing.md) · [packages/TESTING.md](packages/TESTING.md).

---

## Phase 15 — v1.0 Release

Status: ⏳ Planned

Objectives

- SemVer-stable public contracts
- Migration notes + production checklist

See [docs/roadmap/phase-15-releases.md](docs/roadmap/phase-15-releases.md).

---

## Legacy phase notes (superseded numbering)

Older roadmap sections that listed Admin → Builder → Integrations → Testing → Release as Phases 05–11, or Admin before WordPress after REST, are **superseded** by [ADR-0024](docs/adr/ADR-0024-post-rest-phase-order.md). Keep historical `docs/roadmap/phase-*.md` files for content; trust **phase numbers in this file + release-milestones.md**.- Versioning
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

The OpenMeta roadmap defines a structured, architecture-driven path from planning to **v1.0**. OpenMeta is a **PHP framework** with a **WordPress-first adapter**. After REST, the train is WordPress Adapter → Admin → Builder → GraphQL → CLI → Testing & QA → Stable ([ADR-0024](docs/adr/ADR-0024-post-rest-phase-order.md)).