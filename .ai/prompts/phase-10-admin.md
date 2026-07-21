# OpenMeta — Phase 10: Admin UI

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

packages/ui/README.md

packages/ui/SPEC.md

packages/admin/README.md

packages/admin/SPEC.md

docs/ui/ (if present)

packages/security/SPEC.md

packages/BLUEPRINTS.md

packages/TESTING.md

---

## Goal

Build the Admin product surface **and** its UI kit dependency.

Coding order: **UI first**, then Admin.

Do NOT implement:

- Public REST server
- Field storage engine
- Visual Builder canvas
- WordPress `add_menu_page` native wiring (that is Wordpress package)

---

## Responsibilities

### UI (`packages/ui`)

Tokens

Primitives (Button, Input, Notice)

Components (Card, Form, DataTable)

Theme

UiServiceProvider

### Admin (`packages/admin`)

Dashboard

Menus

Screens

Forms

Tables

Settings

Admin renderer + AdminServiceProvider

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

Step 1 — UI Tokens + Theme.

Step 2 — UI Primitives + Components.

Step 3 — UiServiceProvider.

Step 4 — Admin MenuRegistry + ScreenRegistry.

Step 5 — Dashboard + Settings.

Step 6 — AdminForm + AdminTable.

Step 7 — Admin + AdminServiceProvider.

---

## Tests

Minimum coverage: UI primitives/components escape; Admin screens, menus, forms (nonce+validation), capability deny.

Package scripts: `composer test:ui` · `composer test:admin`

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
