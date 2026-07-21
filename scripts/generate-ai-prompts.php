<?php

declare(strict_types=1);

/**
 * Generate .ai/prompts/phase-*.md files.
 */

$root = dirname(__DIR__);
$dir = $root . '/.ai/prompts';
if (! is_dir($dir)) {
    mkdir($dir, 0777, true);
}

$cycle = <<<'MD'

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
MD;

$commonRules = <<<'MD'
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
MD;

$quality = <<<'MD'
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
MD;

function headerBlock(string $phaseTitle): string
{
    return <<<MD
# OpenMeta — {$phaseTitle}

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
MD;
}

$phases = [];

// phase-02 already exists — rewrite for consistency with generator
$phases['phase-02-core-bootstrap.md'] = headerBlock('Phase 02: Core Bootstrap') . <<<MD

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

{$commonRules}
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

{$quality}
MD;

$phases['phase-03-support.md'] = headerBlock('Phase 03: Support') . <<<MD

## Docs (package)

packages/support/README.md

packages/support/SPEC.md

packages/core/SPEC.md (dependency boundary)

packages/BLUEPRINTS.md

packages/TESTING.md

---

## Goal

Build ONLY the Support package.

Nothing else.

Do NOT implement:

- Validation engine
- Security
- Database
- Fields
- REST / GraphQL
- Admin / Builder / WordPress

---

## Responsibilities

Implement only (SPEC spine):

Collections

Helpers

Str

Arr

Filesystem

Paths

Environment

UUID

Reflection

Traits (e.g. Conditionable)

SupportServiceProvider

{$commonRules}
## Code Generation Order

Step 1 — Contracts / interfaces if SPEC requires.

Step 2 — Arr + Str.

Step 3 — Collections.

Step 4 — Paths + Filesystem.

Step 5 — Environment + UUID.

Step 6 — Reflection + Helpers + Traits.

Step 7 — SupportServiceProvider.

---

## Tests

Minimum coverage: Arr, Str, Collection, Path, Filesystem, Env, Uuid, Helpers, Traits, provider boot.

Package script: `composer test:support`

---

{$quality}
MD;

$phases['phase-04-validation.md'] = headerBlock('Phase 04: Validation') . <<<MD

## Docs (package)

packages/validation/README.md

packages/validation/SPEC.md

packages/support/SPEC.md (allowed dependency)

packages/BLUEPRINTS.md

packages/TESTING.md

---

## Goal

Build ONLY the Validation package.

Nothing else.

Do NOT implement:

- Field types
- Database persistence
- REST controllers
- Admin forms UI
- Security authz (capabilities / nonces)

---

## Responsibilities

Implement only (SPEC spine):

Validation (façade / provider)

Rule Engine

Validator

Error Bag

Messages

Built-in rules as SPEC requires

{$commonRules}
## Code Generation Order

Step 1 — Contracts / rule interface.

Step 2 — Error Bag + Messages.

Step 3 — Rule Engine + built-in rules.

Step 4 — Validator.

Step 5 — Validation façade + ValidationServiceProvider.

---

## Tests

Minimum coverage: RuleEngine, Validator, ErrorBag, MessageBag, integration pipeline.

Package script: `composer test:validation`

---

{$quality}
MD;

$phases['phase-05-security.md'] = headerBlock('Phase 05: Security') . <<<MD

## Docs (package)

packages/security/README.md

packages/security/SPEC.md

SECURITY.md

docs/security/

packages/BLUEPRINTS.md

packages/TESTING.md

---

## Goal

Build ONLY the Security package.

Nothing else.

Do NOT implement:

- Field storage
- REST routing
- Admin screens
- Database schema
- Builder

---

## Responsibilities

Implement only (SPEC spine):

Permissions

Capabilities (Array + WordPress bridge)

Nonce

Sanitization

Escaping

Gate / PermissionMap

SecurityServiceProvider

{$commonRules}
## Code Generation Order

Step 1 — Contracts (CapabilityChecker, NonceHandler, etc.).

Step 2 — Permission + PermissionMap + Gate.

Step 3 — Capability checkers (Array; WordPress fail-closed).

Step 4 — Nonce (HMAC; WordPress bridge).

Step 5 — Sanitizer + Escaper.

Step 6 — SecurityServiceProvider.

---

## Tests

Minimum coverage: Gate, Nonce, Sanitizer, Escaper, WordPress fail-closed bridges, provider integration.

Package script: `composer test:security`

---

{$quality}
MD;

$phases['phase-06-database.md'] = headerBlock('Phase 06: Database') . <<<MD

## Docs (package)

packages/database/README.md

packages/database/SPEC.md

docs/database/

packages/BLUEPRINTS.md

packages/TESTING.md

---

## Goal

Build ONLY the Database package.

Nothing else.

Do NOT implement:

- Field types / field UI
- REST / GraphQL servers
- Admin / Builder
- Raw WordPress `\$wpdb` leakage into public API (bridges only as SPEC allows)

---

## Responsibilities

Implement only (SPEC spine):

Connection

Schema

Migration

Repository

Query Builder

Storage

Relationships

DatabaseServiceProvider

{$commonRules}
## Code Generation Order

Step 1 — Connection contracts + Memory / PDO adapters.

Step 2 — Schema + Blueprint.

Step 3 — Migrator.

Step 4 — Query Builder.

Step 5 — Repository + Storage.

Step 6 — Relationships.

Step 7 — DatabaseServiceProvider.

---

## Tests

Minimum coverage: Connection, Schema, Migration, QueryBuilder, Repository, Relationships, integration.

Package script: `composer test:database`

---

{$quality}
MD;

$phases['phase-07-fields.md'] = headerBlock('Phase 07: Fields (Field Engine)') . <<<MD

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

{$commonRules}
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

{$quality}
MD;

$phases['phase-08-rest-api.md'] = headerBlock('Phase 08: REST API') . <<<MD

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

{$commonRules}
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

{$quality}
MD;

$phases['phase-09-wordpress.md'] = headerBlock('Phase 09: WordPress Integration') . <<<MD

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
- Raw migrations / `\$wpdb` schema ownership
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

{$commonRules}
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

{$quality}
MD;

$phases['phase-10-admin.md'] = headerBlock('Phase 10: Admin UI') . <<<MD

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

{$commonRules}
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

{$quality}
MD;

$phases['phase-11-builder.md'] = headerBlock('Phase 11: Visual Builder') . <<<MD

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

{$commonRules}
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

{$quality}
MD;

$phases['phase-12-testing.md'] = headerBlock('Phase 12: Testing') . <<<MD

## Docs

packages/TESTING.md

docs/testing/

docs/roadmap/phase-12-testing.md

tests/Phase12/

Every package SPEC → Testing Strategy

---

## Goal

Enforce the **five-layer gate on every package**. Do not claim packages complete without it.

Layers:

Unit

Integration

WordPress

Performance

Security

---

## Responsibilities

For **each** package under `packages/*`:

Ensure `tests/{Unit,Integration,WordPress,Performance,Security}/` exists with ≥1 `*Test.php`

Keep matrix compliance green: `tests/Phase12/MatrixComplianceTest.php`

Wire Composer / PHPUnit suites (`test:phase12`, layer suites)

Document N/A WordPress surfaces honestly (still ship a gate test)

Do NOT implement new product features in this phase unless required to make a test meaningful.

{$commonRules}
## Code Generation Order

Step 1 — Read TESTING.md matrix; audit gaps per package.

Step 2 — Add missing Unit gates.

Step 3 — Add missing Integration gates.

Step 4 — Add missing WordPress gates (or N/A assertions).

Step 5 — Add missing Performance budgets.

Step 6 — Add missing Security gates.

Step 7 — MatrixComplianceTest + `composer test:phase12*`.

Step 8 — `composer ci` green.

---

## Tests

Run:

```bash
php composer.phar test:phase12
php composer.phar test:phase12:unit
php composer.phar test:phase12:integration
php composer.phar test:phase12:wordpress
php composer.phar test:phase12:performance
php composer.phar test:phase12:security
php composer.phar ci
```

---

{$quality}
MD;

foreach ($phases as $file => $body) {
    file_put_contents($dir . '/' . $file, $body . $cycle . "\n");
    echo "wrote {$file}\n";
}

echo "done\n";
