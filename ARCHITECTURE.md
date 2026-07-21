# OpenMeta Architecture

> Version: Pre-Alpha
> Status: Accepted
> Last Updated: 2026-07-21

---

# Purpose

This document defines the overall architecture of OpenMeta.

It describes how the project is structured, how different components communicate, and the engineering principles that guide every implementation.

No production code should be written that conflicts with this document.

Detailed decisions live in `docs/adr/`. Implementation guides live under `docs/architecture/` and related `docs/` sections.

---

# Table of Contents

1. Vision
2. Architecture Principles
3. High-Level Architecture
4. Repository Structure
5. Package Strategy
6. Plugin Architecture
7. Bootstrap Flow
8. Service Container
9. Dependency Injection
10. Core Modules
11. Database Architecture
12. Field Engine
13. Rendering Pipeline
14. Validation Engine
15. Location Rules
16. Conditional Logic
17. Event System
18. API Layer
19. Admin UI
20. Security
21. Performance
22. Coding Standards
23. Testing Strategy
24. Future Packages

---

# Vision

OpenMeta is designed as a modern content modeling framework for WordPress.

The architecture prioritizes:

- Maintainability
- Extensibility
- Performance
- Testability
- Developer Experience

---

# Architecture Principles

Every architectural decision should follow these principles.

## Simplicity

Prefer simple solutions over clever ones.

---

## Extensibility

Every major system should be extendable.

---

## Separation of Concerns

Each module should have a single responsibility.

---

## Backward Compatibility

Avoid unnecessary breaking changes.

---

## Performance First

Avoid unnecessary queries and memory usage.

---

## Developer Experience

Developer productivity is a primary design goal.

---

# High-Level Architecture

OpenMeta uses a modular layered architecture with clear boundaries between presentation, application services, domain logic, and storage.

```text
WordPress

↓

Plugin Bootstrap

↓

Service Container

↓

Core Services

↓

Field Engine

↓

Validation

↓

Storage

↓

REST / GraphQL

↓

Admin UI

↓

Developer API
```

---

# Repository Structure

The monorepo separates documentation, packages, tooling, and examples.

```text
.github/          CI, issue templates, community config
docs/             Architecture, guides, ADRs, roadmap
packages/         Domain packages (core, fields, api, …)
tests/            Automated tests
examples/         Sample integrations and usage
bin/              CLI / executable entrypoints
scripts/          Maintainer automation scripts
tools/            Generators, migration helpers, release tooling
website/          Project site / docs site assets
```

Root documents (`README.md`, `ARCHITECTURE.md`, `CONTRIBUTING.md`, `SECURITY.md`, `TECH_STACK.md`, `ROADMAP.md`) provide the public entry points.

---

# Package Strategy

Packages isolate major capabilities so they can evolve independently while sharing contracts through `packages/core`.

```text
packages/

core/         Bootstrap, container, events, shared contracts
admin/        WordPress admin screens and app shell
api/          REST and GraphQL public API layer
database/     Schema, migrations, repositories, storage
fields/       Field registry, types, lifecycle
builder/      Visual field / schema builder
ui/           Shared React / UI component library
validation/   Validation rules and error contracts
security/     Capabilities, nonces, authorization helpers
support/      Shared helpers and utilities
```

Only documented public APIs should be consumed by extensions and third-party code.

---

# Plugin Architecture

OpenMeta loads as a WordPress plugin and registers services during the WordPress bootstrap lifecycle.

Responsibilities:

- Plugin entry point and activation/deactivation hooks
- Environment and capability checks
- Service container bootstrapping
- Module discovery and registration
- Hooking into WordPress without leaking domain logic into templates

---

# Bootstrap Flow

```text
WordPress loads plugin

↓

OpenMeta bootstrap entry

↓

Register service providers

↓

Boot core modules

↓

Register fields, APIs, and admin UI

↓

Ready for requests
```

Bootstrap must remain idempotent and fail safely when environment requirements are not met.

---

# Service Container

OpenMeta uses a service container to manage object lifetimes and dependencies.

Responsibilities:

- Register services and factories
- Resolve dependencies
- Prefer interfaces over concrete classes
- Support singleton and transient lifetimes where appropriate

Business logic should request collaborators through the container rather than constructing global state.

---

# Dependency Injection

Rules:

- Prefer constructor injection
- Program to interfaces
- Avoid hidden global dependencies
- Keep WordPress globals at the edges (adapters), not in core domain classes

---

# Core Modules

Core modules provide shared infrastructure used by fields, APIs, and the admin UI.

Examples:

- Configuration
- Asset Manager
- Settings
- Logger
- Helpers
- Utilities
- Permissions
- Localization

Each module should expose a narrow public surface and remain replaceable for tests.

---

# Database Architecture

OpenMeta ships a **Database Abstraction Layer (DAL)** — **no Active Record** ([ADR-0023](docs/adr/ADR-0023-database-dal-no-active-record.md)).

```text
Application → Repository → Query Builder → Connection → Driver → Database Engine
```

Storage follows a layered strategy so domain code does not depend on raw SQL or WordPress meta details.

Responsibilities:

- Connection manager + driver contracts (memory / PDO now; WP adapter later)
- Query builder, repositories, schema, migrations
- Relationship batch loaders, transactions, pagination, metadata
- Clear boundaries for custom tables vs post meta (meta strategy via future WP driver)

See ADR-0006, `packages/database/SPEC.md`, and `docs/database/` for the storage model.

---

# Field Engine

The Field Engine is the heart of OpenMeta content modeling.

Responsibilities:

- Field registry and discovery
- Field factory / instantiation
- Base field contracts
- Rendering, saving, loading, and display
- Validation integration
- Extensible custom field types

Extensions register fields through documented APIs rather than patching internals.

---

# Rendering Pipeline

Fields follow a consistent lifecycle across admin and front-end contexts.

```text
Create

↓

Render

↓

Validate

↓

Save

↓

Load

↓

Display
```

Rendering must respect location rules, conditional logic, capabilities, and escaping rules.

---

# Validation Engine

Validation is a **core shared service**, not a feature of Fields or APIs.

It is applied before persistence and again at API / form / builder boundaries when required. Downstream packages (Database schema checks, Field definitions, form submissions, REST requests, Admin settings, Builder configuration, Import/Export, plugin extensions) **must reuse** `@openmeta/validation` — they must not ship parallel validators.

Responsibilities:

- Built-in validation rules
- Custom validation callbacks / registry extensions
- Structured error reporting (`ErrorBag` / `ValidationResult`)
- Consistent messages for UI and API consumers

Invalid data must never be silently persisted.

---

# Location Rules

Location rules determine where field groups appear.

Examples:

- Post Type
- Taxonomy
- User Role
- Template
- Custom Conditions

Rules are composable and evaluated before the admin UI or front-end render path loads fields.

---

# Conditional Logic

Conditional logic controls field visibility and requirements based on other field values or context.

Rules should be:

- Declarative where possible
- Evaluable on both server and client
- Documented for extension authors

---

# Event System

OpenMeta combines WordPress hooks with internal domain events.

Surfaces:

- Actions / filters for WordPress integration
- Internal events for framework lifecycle
- Extension-safe event contracts

Listeners must remain side-effect aware and avoid tight coupling to private internals.

---

# API Layer

OpenMeta is API-first. Resources are designed for consistent exposure across surfaces.

Supported surfaces:

- PHP API
- REST API
- JavaScript API
- GraphQL (first-class)
- CLI

Authentication, authorization, validation, pagination, and error shapes should stay consistent across APIs. See ADR-0007.

---

# Admin UI

The administration experience lives inside WordPress admin and uses React + TypeScript for interactive screens.

Responsibilities:

- Routing within admin pages
- Reusable component library
- Forms, tables, and builders
- Client state management
- Accessibility and progressive enhancement

UI components must not contain domain business rules; they communicate through documented APIs and services. See ADR-0013.

---

# Security

Security is applied at every layer.

Requirements:

- Capability checks
- Nonces for state-changing admin requests
- Input sanitization and validation
- Output escaping
- Least-privilege defaults
- Secure defaults for APIs and extensions

See `SECURITY.md` and `docs/security/`.

---

# Performance

Performance is a design constraint, not an afterthought.

Practices:

- Efficient queries and indexing
- Lazy loading of heavy modules
- Cached reads where safe
- Minimal admin asset payloads
- Avoid N+1 field loads

---

# Coding Standards

OpenMeta follows:

- PHP Standards (PSR where practical)
- WordPress Coding Standards
- TypeScript / React conventions for UI packages
- Documented naming and folder conventions
- Documentation-first changes for public APIs

See `docs/development/coding-standards.md` and related guides.

---

# Testing Strategy

Quality is enforced with multiple layers:

- Unit testing
- Integration testing
- End-to-end / UI testing
- API testing
- Performance and security testing

Public behavior should be covered before release candidates. See ADR-0018 and `docs/testing/`.

---

# Future Packages

Current domain packages:

- OpenMeta Core
- OpenMeta Admin
- OpenMeta API
- OpenMeta Database
- OpenMeta Fields
- OpenMeta Builder
- OpenMeta UI
- OpenMeta Validation
- OpenMeta Security
- OpenMeta Support

Deferred / exploratory packages (not in the monorepo yet):

- Gutenberg Blocks integration
- Standalone Developer SDK
- AI-assisted modeling

Package boundaries may refine during bootstrap, but public contracts should remain stable once released.

---

# Architecture Decision Records

Every major architectural decision must be documented in the ADR directory before implementation.

```text
docs/adr/
```

---

# Summary

This document is the architectural foundation of OpenMeta.

All implementations should follow the guidelines defined here and the accepted ADRs that refine them.
