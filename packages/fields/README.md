# `@openmeta/fields`

> Pre-alpha contract — implement against this document, not ad-hoc assumptions.

---

## Purpose

Own the field registry, built-in field types, and the create → render → validate → save → load → display lifecycle for OpenMeta fields.

---

## Responsibilities

- Field registry and discovery
- Built-in field type implementations
- Field lifecycle orchestration
- Location-rule aware field group resolution (with core/events)
- Extension contracts for custom field types

Must not own visual builder UI, raw SQL schema migrations, or REST route registration.

---

## Public APIs

- Field registry API
- Base field contracts / abstract field type
- Field group APIs
- Lifecycle hooks (render, save, load, display)
- Custom field type registration API

---

## Dependencies

- `packages/core`
- `packages/validation`
- `packages/database` (persistence adapters)
- `packages/security` (capability-aware field access where required)

---

## Extension Points

- Custom field type registration
- Field lifecycle filters/actions
- Field settings schema extensions
- Storage mapping overrides per field type

---

## Folder Structure

```text
packages/fields/
├── src/
│   ├── Registry/
│   ├── Types/
│   ├── Groups/
│   ├── Lifecycle/
│   └── Contracts/
├── tests/
└── README.md
```
