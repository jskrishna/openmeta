# OpenMeta — Phase 11: Visual Builder

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

packages/builder/README.md

packages/builder/SPEC.md

packages/admin/SPEC.md

packages/fields/SPEC.md

packages/ui/SPEC.md

packages/BLUEPRINTS.md

packages/TESTING.md

---

## Goal

Build ONLY the Builder package (hosted in Admin slots).

Nothing else.

Do NOT implement:

- Field-type engine
- GraphQL server
- Raw migrations
- Owning WP admin menus (Admin / Wordpress packages)

---

## Responsibilities

Implement only (SPEC spine):

Visual Builder (App shell)

Canvas

Components

Drag & Drop

Templates

Conditions

Preview (as SPEC allows)

BuilderServiceProvider

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

Step 1 — CanvasNode + Canvas state.

Step 2 — DragDrop.

Step 3 — Field cards / component mapping.

Step 4 — TemplateRegistry.

Step 5 — ConditionEvaluator.

Step 6 — Preview.

Step 7 — VisualBuilder (mount / save / discard + security).

Step 8 — BuilderServiceProvider (admin screen/menu mount).

---

## Tests

Minimum coverage: canvas, drag/drop, templates, conditions, preview, save authz, admin slot smoke, canvas scale budget.

Package script: `composer test:builder`

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
