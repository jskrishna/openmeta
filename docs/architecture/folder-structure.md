# Folder Structure

> **Document:** Folder Structure
> **Status:** Draft
> **Version:** Pre-Alpha

---

# Purpose

This document defines the official directory structure of the OpenMeta repository.

Every directory has a single responsibility and should only contain files related to that responsibility.

This structure is designed to keep the project scalable, maintainable, and contributor-friendly.

---

# Design Principles

The repository follows these principles.

- Single Responsibility
- Clear Separation of Concerns
- Documentation First
- Modular Development
- Future Scalability
- Developer Friendly

No directory should contain unrelated files.

---

# Repository Structure

```text
openmeta/
│
├── .github/
├── docs/
├── examples/
├── packages/
├── tools/
├── website/
│
├── README.md
├── ARCHITECTURE.md
├── FEATURES.md
├── ROADMAP.md
├── TECH_STACK.md
├── CONTRIBUTING.md
├── CHANGELOG.md
├── CODE_OF_CONDUCT.md
├── SECURITY.md
├── LICENSE
├── composer.json
├── package.json
├── .gitignore
├── .gitattributes
└── .editorconfig
```

---

# Root Directory

The root directory contains project-wide configuration and documentation.

It should never contain implementation source code.

Only global configuration files and documentation belong here.

---

# .github/

Purpose:

GitHub specific configuration.

Contains:

```text
.github/

ISSUE_TEMPLATE/

DISCUSSION_TEMPLATE/

workflows/

CODEOWNERS

FUNDING.yml

dependabot.yml

PULL_REQUEST_TEMPLATE.md
```

Responsibilities:

- GitHub Actions
- Issue Templates
- Pull Request Templates
- Discussions
- Automation

---

# docs/

Purpose:

Central documentation for the project.

Contains:

```text
vision/

architecture/

database/

fields/

api/

ui/

development/

security/

testing/

adr/
```

Responsibilities:

- Technical documentation
- Specifications
- Engineering decisions
- Development guides

---

# examples/

Purpose:

Example implementations demonstrating OpenMeta usage.

Possible examples:

```text
Basic Blog

Portfolio

WooCommerce

Headless WordPress

Custom Plugin Integration
```

Examples should be complete, documented, and runnable.

---

# packages/

Purpose:

Contains all production source code.

Current Status:

Reserved for future development.

Future structure:

```text
packages/

core/

blocks/

graphql/

react/

sdk/

cli/

ai/
```

Every package should have a clearly defined responsibility.

No package should depend on implementation details of another package.

---

# tools/

Purpose:

Internal development utilities.

Examples:

```text
Code Generators

Migration Scripts

Build Helpers

Release Tools

Development Scripts
```

This directory is intended for maintainers only.

---

# website/

Purpose:

Official OpenMeta website.

Future contents:

```text
Landing Page

Documentation Website

Blog

Release Notes

Community Pages
```

The website is maintained separately from the plugin.

---

# Documentation Files

## README.md

Project introduction.

---

## ARCHITECTURE.md

High-level architecture index.

---

## FEATURES.md

Complete feature registry.

---

## ROADMAP.md

Project milestones and release planning.

---

## TECH_STACK.md

Technology decisions.

---

## CONTRIBUTING.md

Contribution guidelines.

---

## CHANGELOG.md

Release history.

---

## SECURITY.md

Security policy.

---

## CODE_OF_CONDUCT.md

Community rules.

---

# Source Code Policy

The repository follows a strict separation between documentation and implementation.

Documentation lives inside:

```text
docs/
```

Implementation lives inside:

```text
packages/
```

Examples live inside:

```text
examples/
```

Development utilities live inside:

```text
tools/
```

Website code lives inside:

```text
website/
```

Mixing responsibilities is discouraged.

---

# Naming Rules

Directory names use:

- lowercase
- kebab-case when required
- descriptive names

Examples:

✅

```text
service-container

field-engine

plugin-bootstrap
```

Avoid:

```text
misc

temp

new

test123
```

---

# Future Growth

The directory structure is intentionally minimal during the planning phase.

Additional directories should only be introduced when they solve a real architectural problem.

Avoid creating folders "just in case."

---

# Summary

The OpenMeta repository is organized around responsibility rather than technology.

Each directory has a clearly defined purpose.

Maintaining this separation helps improve readability, onboarding, scalability, and long-term maintenance.

All contributors should follow this structure unless an Architecture Decision Record (ADR) explicitly changes it.