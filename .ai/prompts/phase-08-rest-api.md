# OpenMeta — Phase 08: REST API

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

packages/api/README.md

packages/api/SPEC.md

docs/api/

packages/fields/SPEC.md

packages/security/SPEC.md

packages/BLUEPRINTS.md

packages/TESTING.md

---

## Goal

Build ONLY the API package (WordPress REST first).

Nothing else.

Do NOT implement:

- Admin UI
- Builder
- Field-type engine (consume Fields)
- Full GraphQL server unless SPEC explicitly includes a parallel mount stub

---

## Responsibilities

Implement only (SPEC spine):

REST (Router, RestKernel, Request/Response)

Controllers

Resources

Authentication

Authorization

ApiServiceProvider

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

Step 1 — Request / Response + Router contracts.

Step 2 — Resources.

Step 3 — Authentication (Token + WordPress bridge).

Step 4 — Authorization (→ Gate).

Step 5 — Controllers + RouteRegistrar.

Step 6 — RestKernel + ApiServiceProvider.

---

## Tests

Minimum coverage: health route, auth deny, authz deny, field value happy path, resources, WP auth fail-closed.

Package script: `composer test:api`

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
