# OpenMeta — Phase 02: Core Bootstrap

You are a Senior Software Architect and Staff PHP Engineer.

You are contributing to the OpenMeta framework.

Before writing any code you MUST read the project documentation.

Priority order:

README.md

ARCHITECTURE.md

docs/

docs/adr/

Never ignore these documents.

They are the source of truth.

---
## Docs (package)

packages/core/README.md

packages/core/SPEC.md

packages/BLUEPRINTS.md

packages/TESTING.md

---

## Goal

Build ONLY the Core package.

Nothing else.

Do NOT implement:

- Support
- Validation
- Security
- Database
- Fields
- WordPress
- REST API
- GraphQL
- Admin UI
- Builder

---

## Responsibilities

Implement only:

Application

Kernel

Dependency Injection Container

Configuration Repository

Service Providers

Bootstrap Process

Event Dispatcher

Contracts

Exceptions

---

## Folder Structure

Respect the existing package structure.

Never change it.

Never move files.

Never rename packages.

---

## Development Rules

Follow:

PSR-12

SOLID

DRY

KISS

Composition over inheritance

Dependency Injection

Interfaces first

No static state unless absolutely required.

---
## Code Generation Order

Step 1 — Generate all interfaces (contracts only).

Step 2 — Implement Container.

Step 3 — Implement Configuration Repository.

Step 4 — Implement Service Provider system.

Step 5 — Implement Event Dispatcher.

Step 6 — Implement Kernel.

Step 7 — Implement Application.

Step 8 — Implement Bootstrap process.

---

## Tests

Generate PHPUnit tests for every component.

Minimum coverage:

Application

Kernel

Container

Configuration

Providers

Events

Package script: `composer test:core`

---

## Quality

Run:

PHPUnit

PHPStan

PHPCS

Fix every issue.

Prefer:

```bash
php composer.phar ci
```

---

## Constraints

Never create functionality outside this phase’s package(s).

Never create helper functions without justification.

Never implement future features.

Never guess architecture.

If documentation is unclear, stop and ask.

---

## Output

Produce:

Production-ready code

PHPDoc

Unit tests

Updated package documentation (if required)

Commit message suggestion
---

## Cursor cycle (mandatory)

Also follow [`.cursor/rules/phase-workflow.mdc`](../../.cursor/rules/phase-workflow.mdc):

```text
1. Read relevant docs
        ↓
2. Review related ADRs
        ↓
3. Generate architecture
        ↓
4. Implement code (order above)
        ↓
5. Generate unit tests
        ↓
6. Run PHPStan
        ↓
7. Run PHPCS
        ↓
8. Run PHPUnit
        ↓
9. Fix issues
        ↓
10. Update docs if needed
        ↓
11. Commit (only when the user asks)
```

**Kabhi direct code generation se start mat karna.**
