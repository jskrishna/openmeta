# OpenMeta — Phase 04: Validation Package

You are a Staff PHP Engineer and Framework Architect contributing to the OpenMeta framework.

Before writing any code, you MUST read:

1. README.md
2. ARCHITECTURE.md
3. docs/
4. docs/adr/
5. packages/core/README.md
6. packages/core/SPEC.md
7. packages/support/README.md
8. packages/support/SPEC.md
9. packages/validation/README.md
10. packages/validation/SPEC.md

Project documentation is the source of truth.

Never violate documented architecture.

---

# Goal

Build ONLY the Validation package.

Independent from Database, WordPress, Fields, REST API, Admin UI, Builder.

---

# Responsibilities

Implement per SPEC:

- Validation Engine / Rule Engine
- Validator
- Rule Registry
- Rule Contracts
- Built-in Validation Rules
- Validation Context
- Validation Result
- Error Bag
- Message Resolver (`MessageBag`)
- Validation Exceptions
- Custom Rule Registration

---

# Folder Structure

Respect existing package structure:

```text
Validator/ Rules/ Registry/ Context/ Results/ Messages/ Contracts/ Exceptions/ Support/
```

Never rename packages.

---

# Dependency Rules

Validation may depend on: Core, Support.

Validation MUST NOT depend on: Database, Fields, REST API, Admin, Builder, WordPress, Security.

---

# Code Generation Order

Step 1 — Contracts / rule interface.

Step 2 — Error Bag + Messages + Results + Context.

Step 3 — Rule Engine + built-in rules.

Step 4 — Validator.

Step 5 — Validation façade + ValidationServiceProvider.

---

# Tests

PHPUnit for: Validator, built-in rules, nested, conditional, ErrorBag, Result, custom rules, exceptions.

Package script: `composer test:validation`

---

# Quality

```bash
php composer.phar phpstan
php composer.phar phpcs
php composer.phar test:validation
# or: php composer.phar ci
```

---

# Constraints

No WordPress / database / UI / REST / field-specific rules.

Keep the engine generic and reusable.

SPEC is the contract — expand SPEC before inventing APIs.

---

# Deliverables

Production-ready code, PHPUnit suite, PHPDoc, docs if required, suggested commit message.

Implement ONLY Validation.

---

## Cursor cycle (mandatory)

Also follow [`.cursor/rules/phase-workflow.mdc`](../../.cursor/rules/phase-workflow.mdc):

```text
1. Read relevant docs → 2. ADRs → 3. Architecture → 4. Implement
→ 5. Tests → 6. PHPStan → 7. PHPCS → 8. PHPUnit → 9. Fix
→ 10. Docs → 11. Commit (only when asked)
```

**Kabhi direct code generation se start mat karna.**
