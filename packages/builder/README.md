# `@openmeta/builder`

> Pre-alpha contract — implement against this document, not ad-hoc assumptions.

---

## Purpose

Provide the visual field/schema builder experience (drag-and-drop editing, templates, live validation feedback) used inside admin.

---

## Responsibilities

- Builder UI application and interactions
- Field group editing flows
- Templates and presets
- Live validation feedback wired to validation contracts
- Persisting builder changes through documented field/admin APIs

Must not redefine field types or bypass `fields` / `validation` contracts.

---

## Public APIs

- Builder bootstrap / mount API for admin screens
- Template/preset registration API
- Documented events for builder save/load cycles
- Extension slots for custom builder panels

---

## Dependencies

- `packages/core`
- `packages/ui`
- `packages/fields`
- `packages/validation`
- `packages/admin` (host screens)
- `packages/security`

---

## Extension Points

- Custom builder panels / inspectors
- Template providers
- Drag-and-drop item types
- Save pipeline middleware

---

## Folder Structure

```text
packages/builder/
├── src/
│   ├── App/
│   ├── Panels/
│   ├── Templates/
│   └── State/
├── resources/
├── tests/
└── README.md
```
