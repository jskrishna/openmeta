# `@openmeta/support`

> Pre-alpha contract — implement against this document, not ad-hoc assumptions.

---

## Purpose

Provide small, framework-agnostic helpers and utilities reused across packages without becoming a dumping ground for domain logic.

---

## Responsibilities

- Pure helpers (arrays, strings, paths, collections)
- Small shared value objects / DTOs that are not domain-specific
- Cross-cutting utilities that do not belong in `core`

Must stay lean. Feature logic belongs in domain packages.

---

## Public APIs

- Documented helper functions / classes
- Shared scalar/value utilities
- Collection / Arr / Str style helpers (names TBD in Phase 01)

---

## Dependencies

- PHP 8.3+ only (prefer zero WordPress coupling)

Must not depend on any other OpenMeta package. Other packages may depend on `support`.

---

## Extension Points

- Generally none — prefer adding focused helpers via PRs rather than plugin hooks
- If a hook is required, document it explicitly before shipping

---

## Folder Structure

```text
packages/support/
├── src/
│   ├── Arr/
│   ├── Str/
│   ├── Path/
│   └── Types/
├── tests/
└── README.md
```
