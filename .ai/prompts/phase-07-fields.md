# OpenMeta — Phase 07: Fields (Field Engine)

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

packages/fields/README.md

packages/fields/SPEC.md

docs/fields/

packages/validation/SPEC.md

packages/database/SPEC.md

packages/BLUEPRINTS.md

packages/TESTING.md

---

## Goal

Build ONLY the Fields package (heart of OpenMeta).

Nothing else.

Do NOT implement:

- Admin screens
- Visual Builder
- Full GraphQL server
- Standalone REST router (only field REST/GQL **support contracts**)

---

## Responsibilities

Implement only (SPEC spine):

Field Registry

Base Field

Field Types (built-ins per SPEC)

Rendering

Storage

Validation bridge

REST Support

GraphQL Support (type maps / contracts)

FieldEngine + FieldsServiceProvider

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

Step 1 — Field contracts + Registry.

Step 2 — Base Field + built-in types.

Step 3 — FieldValidator (→ Validation package).

Step 4 — FieldValueStorage (→ Database).

Step 5 — FieldRenderer.

Step 6 — REST / GraphQL support maps.

Step 7 — FieldEngine + FieldsServiceProvider.

---

## Tests

Minimum coverage: Registry, types, validate→save→load, render escape, exposure contracts.

Package script: `composer test:fields`

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
