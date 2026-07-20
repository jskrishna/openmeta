# `@openmeta/validation`

> Pre-alpha contract — implement against this document, not ad-hoc assumptions.

---

## Purpose

Define validation rules and structured error contracts shared by fields, APIs, and UI feedback surfaces.

---

## Responsibilities

- Built-in validation rules
- Custom rule registration
- Structured validation error envelopes
- Server-side validation execution (and client-evaluable rule metadata where practical)

Must not persist data or render UI components.

---

## Public APIs

- Validator / rule interfaces
- Rule registry API
- Validation result and error DTO shapes
- Helpers to run rule sets against input payloads

---

## Dependencies

- `packages/core`
- `packages/support` (optional pure helpers)

Consumed by `fields`, `api`, `builder`, and `admin`. Must not depend on those packages.

---

## Extension Points

- Custom validation rules
- Error message formatters / translators
- Rule set presets for field types

---

## Folder Structure

```text
packages/validation/
├── src/
│   ├── Rules/
│   ├── Registry/
│   ├── Results/
│   └── Contracts/
├── tests/
└── README.md
```
