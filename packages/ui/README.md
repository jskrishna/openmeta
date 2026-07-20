# `@openmeta/ui`

> Pre-alpha contract — implement against this document, not ad-hoc assumptions.

---

## Purpose

Ship the shared React/UI component library and design primitives consumed by `admin` and `builder` (and future UI surfaces).

---

## Responsibilities

- Reusable components (forms, tables, modals, notifications, etc.)
- Design tokens / theming hooks
- Accessibility-first primitives
- Shared client utilities used only for presentation

Must not contain domain business rules, persistence, or WordPress capability checks beyond presentational props.

---

## Public APIs

- Published component exports
- Theme/token APIs
- Form control primitives
- Documented prop contracts for each public component

---

## Dependencies

- React / TypeScript (when introduced)
- `packages/support` (shared pure helpers only, if needed)

Must not depend on `database`, `api`, or `fields` implementation details.

---

## Extension Points

- Theme token overrides
- Component composition slots
- Icon / density / locale presentation hooks

---

## Folder Structure

```text
packages/ui/
├── src/
│   ├── components/
│   ├── tokens/
│   ├── hooks/
│   └── utils/
├── tests/
└── README.md
```
