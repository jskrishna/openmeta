<div align="center">

# OpenMeta

### Modern Content Modeling Framework for WordPress

Build powerful custom fields, structured content models, and developer-first experiences with a modern open-source framework.

![License](https://img.shields.io/badge/license-GPL--2.0-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?logo=php&logoColor=white)
![WordPress](https://img.shields.io/badge/WordPress-6.8+-21759B?logo=wordpress&logoColor=white)
![Status](https://img.shields.io/badge/status-Beta-yellow)
![Contributions](https://img.shields.io/badge/contributions-welcome-brightgreen)

---

**Open Source • Developer First • Performance Focused • Community Driven**

</div>

---

# About OpenMeta

OpenMeta is a modern content modeling framework built for WordPress.

It enables developers to create custom fields, field groups, structured content models, validation rules, conditional logic, and advanced editing experiences using a clean and extensible architecture.

Rather than being just another custom fields plugin, OpenMeta aims to provide a complete foundation for structured content development in modern WordPress projects.

---

# Why OpenMeta?

WordPress developers deserve modern tooling.

OpenMeta is being built to provide:

- Modern architecture
- Excellent developer experience
- High performance
- Clean APIs
- Extensible field system
- React-powered administration
- Native REST support
- First-class GraphQL support
- Open-source development
- Long-term maintainability

---

# Vision

Our vision is simple.

Build the best open-source content modeling framework for WordPress.

OpenMeta should become the foundation developers choose when building modern WordPress applications.

---

# Project Goals

- Build a scalable architecture
- Improve developer productivity
- Reduce technical debt
- Support modern WordPress development
- Maintain excellent documentation
- Encourage community contributions
- Keep the project open forever

---

# Planned Features

## Core

- Plugin Framework
- Settings System
- Field Groups
- Validation Engine
- Location Rules
- Conditional Logic
- Asset Manager

## Field Types

- Text
- Textarea
- Number
- Email
- URL
- Select
- Checkbox
- Radio
- Toggle
- Date
- Time
- Image
- Gallery
- File
- Relationship
- Taxonomy
- User
- WYSIWYG

## Advanced Fields

- Repeater
- Flexible Content
- Group
- Clone
- Accordion
- Tabs

## Developer Experience

- PHP API
- JavaScript API
- REST API
- GraphQL
- CLI
- Hooks
- Filters

## Integrations

- Gutenberg
- WooCommerce
- WPGraphQL
- Elementor
- Bricks Builder

---

# Current Status

Current Stage:

**Beta — Developer-Experience train `v0.12.0-beta` ✅**

Shipped: Foundation (Core → Support → Validation → Security → Database → Fields → REST → API),
WordPress adapter, Admin UI, Visual Builder, Extension SDK, and the GraphQL package.

Next: **CLI & Developer Tools** (`v0.13.0-beta`) → Code Generator → Documentation Generator
→ Marketplace (optional) → `v1.0.0`.

Phase order is authoritative in [ADR-0027](docs/adr/ADR-0027-dx-first-roadmap.md).

Track progress: [milestones](https://github.com/jskrishna/openmeta/milestones) · [`.github/MILESTONES.md`](.github/MILESTONES.md)

No production-ready (`v1.0.0`) release yet.

---

# Documentation

Project documentation is organized into dedicated sections.

```
docs/

vision/
architecture/
database/
fields/
api/
ui/
development/
security/
testing/
roadmap/
adr/
getting-started/
guides/
```

Detailed documentation will continue to evolve alongside implementation.

---

# Roadmap

```text
Foundation (Core → Support → Validation → Security → Database → Fields → REST → API) ✅
    ↓
WordPress ✅ → Admin UI ✅ → Visual Builder ✅ → Extension SDK ✅ → GraphQL ✅
    ↓
CLI → Code Generator → Documentation Generator → Marketplace (optional)
    ↓
v1.0.0
```

Foundation and the post-REST ecosystem phases are complete; the project is now on the
developer-experience train. Phase numbering follows [ADR-0027](docs/adr/ADR-0027-dx-first-roadmap.md).

## Foundation ✅

Core, Support, Validation, Security, Database, Fields, REST, and API packages
(`v0.1` – `v0.7`).

## Phase 08 — WordPress Adapter ✅ (`v0.8`)

Plugin bootstrap, hook/action bridges, and fail-closed WP runtime.

## Phase 09 — Admin UI ✅ (`v0.9`)

React + TypeScript admin: UI kit, dashboard, menus, forms, settings.

## Phase 11 — Visual Builder ✅ (`v0.10-beta`)

Canvas, drag-and-drop, schema, and live preview.

## Phase 12 — Extension SDK ✅ (`v0.11-beta`)

Discovery, manifest, and lifecycle contract (`packages/extensions`).

## Phase 13 — GraphQL Package ✅ (`v0.12-beta`)

First-class GraphQL schema and resolvers (`packages/graphql`).

## Phase 14 — CLI & Developer Tools (`v0.13-beta`) ← next

Command-line scaffolding and developer tooling (`packages/cli`).

## Phase 15 — Code Generator (`v0.14-beta`)

Scaffolding generators built on the Extension SDK.

## Phase 16 — Testing, QA & Performance

Continuous quality gate on every train plus a pre-v1.0 hardening pass (no dedicated semver train).

## Phase 17 — Documentation Generator (`v0.15-beta`)

Generated developer documentation.

## Phase 18 — Marketplace & Package Manager (`v0.16-beta`, optional)

Distribution surface; may be deferred without blocking Stable.

## Phase 19 — Stable Release (`v1.0.0`)

Public API freeze and the first production-ready release.

See **ROADMAP.md** for the complete development roadmap.

---

# Contributing

OpenMeta is a community-driven project.

We welcome contributions in the following areas:

- Development
- Documentation
- Testing
- Bug Reports
- Feature Requests
- Performance Improvements
- Security Reviews

Please read **CONTRIBUTING.md** before opening issues or pull requests.

---

# Development Principles

OpenMeta follows a few core principles.

- Documentation before implementation.
- Simplicity over complexity.
- Performance by default.
- Developer experience matters.
- Secure by design.
- Backward compatibility when practical.
- Community-driven development.

---

# Technology

The target technology stack includes:

- PHP 8.3+
- WordPress 6.8+
- Composer
- PSR Standards
- React
- TypeScript
- Vite
- PHPUnit
- Playwright
- PHPStan
- PHPCS

Tooling and dependencies are being introduced during the bootstrap phase. See **TECH_STACK.md** for details.

---

# License

OpenMeta is licensed under the GNU General Public License v2.0 or later.

See the **LICENSE** file for complete licensing information.

---

# Project Status

This repository is under active development.

Features, architecture, and APIs may change before the first stable release.

Feedback and discussions are always welcome.

---

<div align="center">

Built with ❤️ by the OpenMeta Community.

</div>