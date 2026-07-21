# OpenMeta — Phase 05: Security Package

You are a Principal Software Architect and Senior PHP Framework Engineer.

Before writing any code, you MUST read:

1. README.md
2. ARCHITECTURE.md
3. docs/
4. docs/adr/
5. packages/core/README.md
6. packages/support/README.md
7. packages/validation/README.md
8. packages/security/README.md
9. packages/security/SPEC.md

Documentation is the source of truth. Never violate documented architecture.

---

# Goal

Build ONLY the Security package — framework-independent security primitives.

Must NOT depend on: Database, Fields, REST API, Admin, Builder, WordPress (Composer).

Optional fail-closed WP bridges are allowed for capability/nonce adapters (ADR-0003); pure PHP is default for CI.

---

# Responsibilities (SPEC)

Permissions, Capabilities, Gate/Authorizer, Nonce, CSRF, Sanitization, Escaping,
Hashing/Password, Secure Random, Tokens, SecurityContext, Contracts, Exceptions, Support.

---

# Folder Structure

```text
Sanitization/ Escaping/ Authorization/ Permissions/ Capabilities/
Nonce/ CSRF/ Hashing/ Random/ Tokens/ Context/ Contracts/ Exceptions/ Support/
```

---

# Dependency Rules

May depend on: Core, Support (+ Validation only if needed).

Must not depend on: Database, Fields, API, Admin, Builder, Wordpress.

---

# Constraints

Do not implement: WP roles/nonces as primary, login, sessions, OAuth, JWT, REST/UI security.

---

# Quality

```bash
php composer.phar test:security
php composer.phar phpstan
php composer.phar phpcs
```

---

## Cursor cycle

Follow `.cursor/rules/phase-workflow.mdc` — docs → ADRs → architecture → code → tests → quality → docs → commit only when asked.

**Kabhi direct code generation se start mat karna.**
