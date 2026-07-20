# Non-Goals

---

# Purpose

Clearly defining what OpenMeta does **not** aim to become is just as important as defining its goals.

Non-goals establish project boundaries, reduce unnecessary complexity, and help contributors make consistent architectural decisions.

Every proposed feature should be evaluated against these boundaries.

---

# Project Scope

OpenMeta is a content modeling framework.

Its responsibility is to define, manage, validate, and expose structured content.

Responsibilities outside this scope should remain the responsibility of other tools or plugins.

---

# OpenMeta Is Not a CMS

OpenMeta extends WordPress.

It does not replace WordPress Core.

WordPress remains responsible for:

- Content management
- Authentication
- User management
- Media library
- Theme system

---

# OpenMeta Is Not a Page Builder

The framework does not provide:

- Drag-and-drop page editing
- Landing page builders
- Visual layout editors
- Website design tools

These features belong to dedicated page builder solutions.

---

# OpenMeta Is Not a Theme Framework

OpenMeta does not generate:

- Themes
- Templates
- Frontend layouts
- Styling systems

Presentation remains outside the framework's responsibility.

---

# OpenMeta Is Not a Website Builder

The framework is not intended to become:

- Wix
- Squarespace
- Webflow

Its purpose is structured content, not visual site creation.

---

# OpenMeta Is Not a Form Builder

Although fields may resemble forms, OpenMeta is not designed for:

- Contact forms
- Surveys
- Lead capture
- Marketing forms

Dedicated form solutions are better suited for these use cases.

---

# OpenMeta Is Not an ORM

The framework does not attempt to replace:

- Doctrine
- Eloquent
- Other database abstraction layers

Repositories provide the necessary persistence abstraction.

---

# OpenMeta Is Not a Full Framework

OpenMeta does not replace frameworks such as:

- Laravel
- Symfony
- ASP.NET Core

Instead, it provides content modeling capabilities within WordPress.

---

# OpenMeta Is Not a Low-Code Platform

The framework is designed primarily for developers.

While future visual tools may exist, the architecture prioritizes code-first development.

---

# OpenMeta Is Not an Analytics Platform

The framework does not include:

- Reporting
- Business intelligence
- Dashboards
- Usage analytics

These responsibilities belong to specialized tools.

---

# OpenMeta Is Not a Workflow Engine

The framework is not responsible for:

- Business process automation
- Approval workflows
- Task orchestration

Extensions may implement these capabilities independently.

---

# OpenMeta Is Not an E-commerce Platform

OpenMeta does not replace:

- WooCommerce
- Shopping carts
- Payment gateways
- Order management

It may integrate with these systems but does not duplicate their functionality.

---

# OpenMeta Is Not a Replacement for Plugins

The framework complements existing plugins.

Its goal is interoperability rather than replacement whenever possible.

---

# Why Non-Goals Matter

Defining non-goals helps:

- Prevent feature creep
- Simplify architecture
- Improve maintainability
- Preserve project focus
- Make roadmap decisions easier

Every feature request should support the project's core mission.

---

# Summary

OpenMeta focuses exclusively on structured content modeling. By intentionally avoiding responsibilities such as page building, theme development, form building, analytics, and full application frameworks, the project maintains a clear architectural focus, remains easier to maintain, and delivers a better developer experience.