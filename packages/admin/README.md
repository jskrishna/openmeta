# `@openmeta/admin`

> Pre-alpha contract — implement against this document, not ad-hoc assumptions.

---

## Purpose

Own WordPress admin screens and the OpenMeta admin application shell that editors and developers use to manage models, fields, and settings.

---

## Responsibilities

- Admin menu registration and page routing
- Dashboard, settings, and management shells
- Wiring admin screens to `ui` components and domain services
- Admin-only assets and capability-gated pages

Must not contain domain persistence, field-type logic, or public HTTP API controllers.

---

## Public APIs

- Admin page/menu registration helpers
- Screen controller contracts
- Hooks for injecting admin menu items and panels
- Documented JS bridges for admin screens (when introduced)

---

## Dependencies

- `packages/core`
- `packages/ui`
- `packages/fields` (read/display contracts)
- `packages/security` (capabilities / nonces)
- WordPress Admin APIs

Must not depend on `builder` internals (builder may embed into admin via extension points).

---

## Extension Points

- Admin menu / submenu registration filters
- Screen panel slots
- Settings section registration
- Capability maps for admin routes

---

## Folder Structure

```text
packages/admin/
├── src/
│   ├── Menus/
│   ├── Screens/
│   ├── Controllers/
│   └── Assets/
├── resources/
├── tests/
└── README.md
```
