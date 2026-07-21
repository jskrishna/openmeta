# OpenMeta — Phase 03: Support Package

You are a Staff PHP Engineer and Framework Architect contributing to the OpenMeta project.

Before writing any code, you MUST read:

1. README.md
2. ARCHITECTURE.md
3. docs/
4. docs/adr/
5. packages/core/README.md
6. packages/core/SPEC.md
7. packages/support/README.md
8. packages/support/SPEC.md

Documentation is the source of truth.

Never violate the documented architecture.

---

# Goal

Build ONLY the Support package.

The Support package provides reusable utilities that are shared across the framework.

It MUST NOT contain:

- Business logic
- Database logic
- WordPress-specific logic
- Field logic
- REST API logic
- UI logic

Support exists only to help other packages.

---

# Responsibilities

Implement reusable utilities for:

- Collections
- Arrays
- Strings
- Filesystem helpers
- Path utilities
- UUID generation
- Reflection helpers
- Object helpers
- Value objects (only if listed in SPEC — do not invent)
- Traits
- Helper functions (only if justified)
- Environment utilities

---

# Folder Structure

Respect the existing package structure.

Never rename packages.

Never move directories.

---

# Development Rules

Follow:

- PSR-12
- SOLID
- DRY
- KISS
- Composition over inheritance
- Dependency Injection
- Interfaces first
- Immutable value objects whenever possible
- No global state

---

# Dependency Rules

Support may depend on:

- Core

Support MUST NOT depend on:

- Database
- Validation
- Security
- Fields
- API
- Admin
- Builder
- WordPress

---

# Implementation Order

Step 1 — Contracts (FilesystemInterface, etc. as SPEC requires).

Step 2 — Collections.

Step 3 — Array utilities.

Step 4 — String utilities.

Step 5 — Filesystem utilities.

Step 6 — Path utilities.

Step 7 — UUID utilities.

Step 8 — Reflection helpers.

Step 9 — Traits.

Step 10 — Exceptions.

Also: Environment, Helpers, SupportServiceProvider per SPEC.

---

# Quality Requirements

Every public class must:

- Include PHPDoc
- Be typed
- Follow SOLID
- Have unit tests

---

# Tests

Generate PHPUnit tests for:

Collections, Array helpers, String helpers, Filesystem, Paths, UUID, Reflection, Traits (where appropriate), Env, Helpers.

Target high coverage. Phase 12 layers under `tests/{Unit,Integration,WordPress,Performance,Security}/`.

Package script: `composer test:support`

---

# Code Quality

Run and pass:

- PHPUnit
- PHPStan
- PHPCS

Fix all reported issues before completion.

Prefer: `php composer.phar ci`

---

# Constraints

Do not implement convenience methods that are not required.

Avoid unnecessary abstractions.

Do not duplicate PHP native functionality unless it provides measurable architectural value.

Do not introduce third-party dependencies without strong justification.

Keep APIs small and predictable.

SPEC is the contract — do not invent modules (e.g. ValueObjects) unless SPEC lists them.

---

# Deliverables

Produce:

- Production-ready code
- Unit tests
- PHPDoc
- Updated package documentation (if required)
- Suggested commit message

Do not implement any package other than Support.

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
