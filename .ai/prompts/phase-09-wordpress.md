# OpenMeta — Phase 09: WordPress Adapter

You are a Senior Software Architect and Staff PHP Engineer.

You are contributing to the OpenMeta framework.

> Canonical phase **09** — WordPress Adapter (`v0.8.0-alpha`).  
> Order: [ADR-0024](../../docs/adr/ADR-0024-post-rest-phase-order.md). Mount `@openmeta/rest`; do not reimplement domain engines.

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

packages/wordpress/README.md

packages/wordpress/SPEC.md

openmeta.php (plugin entry)

docs/architecture/plugin-bootstrap.md

docs/adr/ (WordPress-first)

packages/BLUEPRINTS.md

packages/TESTING.md

---

## Goal

Build ONLY the WordPress integration package + root plugin entry glue.

Nothing else.

Do NOT implement:

- Field-type engine
- Validation engine
- Raw migrations / `$wpdb` schema ownership
- Admin product screens (consume Admin)
- Builder canvas logic (consume Builder)

---

## Responsibilities

Implement only (SPEC spine):

Plugin Bootstrap

Hooks

Filters

Admin Pages (bridge)

Capabilities (seed / register)

Gutenberg (block metadata)

REST (bridge to Api Router)

WordPressRuntime (Native + Array for tests)

WordpressServiceProvider

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

Step 1 — WordPressRuntimeInterface + Array + Native.

Step 2 — Requirements + Plugin shell.

Step 3 — ActionBridge + FilterBridge.

Step 4 — CapabilityRegistrar.

Step 5 — AdminPages bridge.

Step 6 — BlockRegistrar.

Step 7 — RestBridge.

Step 8 — WordpressServiceProvider + `openmeta.php` entry.

---

## Tests

Minimum coverage: requirements, boot hooks, admin/rest/gutenberg registration (Array runtime), activate caps, native fail-soft without WP.

Package script: `composer test:wordpress`

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
