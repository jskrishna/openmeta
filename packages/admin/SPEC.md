# SPEC — `@openmeta/admin`

> **Implementation contract.** Implement against this document. [README](./README.md) is the short overview.

**Status:** ✅ Complete — Phase 10 / `v0.9.0-alpha`

**Note:** Depends on `@openmeta/ui` for primitives. WordPress menu mounting lives in `@openmeta/wordpress` — not here.

---

## Purpose

Reusable **Admin UI framework** for OpenMeta modules: application boot, pages, navigation, layouts, components, forms, tables, dashboard widgets, notices, modals, toolbar, themes, and UI events — **without business logic**, WordPress APIs, or field storage.

---

## Module map

```text
AdminApplication
    ↓
Pages / Navigation / Screens
    ↓
Layouts / Components / Themes / Assets
    ↓
Forms / Tables / Dashboard / Widgets
    ↓
Notices / Modals / Toolbar / Tabs / Panels / Cards
    ↓
Events (Core dispatcher)
```

---

## AdminApplication

### Responsibilities

- Boot UI registries (pages, navigation, layouts, components, forms, tables)
- Expose public managers via `Admin` façade
- Coordinate capability-gated rendering

### Must not

- Call WordPress APIs
- Own field-type engine or persistence

---

## Pages / Navigation / Screens

### Responsibilities

- `PageManager` — register pages with layout regions (title, toolbar, content, sidebar, footer)
- `NavigationManager` — menus, groups, breadcrumbs atop `MenuRegistry`
- `ScreenRegistry` — legacy compatibility for `renderScreen()`

### Must not

- Register WP admin menus directly

---

## Layouts

### Responsibilities

- `LayoutManager` — composable layouts: full-width, sidebar, split, dashboard-grid, wizard
- `ScreenContext` — region payloads for layouts

---

## Forms / Tables

### Responsibilities

- `FormBuilder` — sections, groups, validation via `@openmeta/validation`, optional `@openmeta/fields` renderer
- `AdminForm` — nonce + submit pipeline
- `TableBuilder` — columns, search, sort, bulk/row actions, `@openmeta/database` paginator

### Must not

- Duplicate field-type implementations

---

## Dashboard / Widgets / Components

### Responsibilities

- `Dashboard` + `DashboardWidget` registration
- `ComponentRegistry` — named descriptors consumed by `@openmeta/ui`
- Cards, panels, tabs, toolbar, notices, modals (presentation shells)

---

## Themes / Assets

### Responsibilities

- `ThemeManager` — wraps `@openmeta/ui` theme + tokens
- `AssetRegistry` — declarative script/style handles for WordPress Asset Manager bridge

### Must not

- Call `wp_enqueue_*` directly

---

## Events

Dispatch via Core: `PageLoaded`, `ComponentRendered`, `FormSubmitted`, `TableLoaded`, `ModalOpened`

---

## Public Contracts (package index)

| API | Component |
| --- | --------- |
| `AdminApplication` | Application boot |
| `PageManager` | Pages |
| `NavigationManager` | Navigation |
| `LayoutManager` | Layouts |
| `ComponentRegistry` | Components |
| `FormBuilder` / `AdminForm` | Forms |
| `TableBuilder` / `AdminTable` | Tables |
| `NoticeManager` / `ModalManager` | Chrome |
| `AssetRegistry` | Assets |

---

## Folder Structure

```text
packages/admin/
├── src/
│   ├── Application/
│   ├── Assets/
│   ├── Cards/
│   ├── Components/
│   ├── Contracts/
│   ├── Dashboard/
│   ├── Events/
│   ├── Exceptions/
│   ├── Forms/
│   ├── Layouts/
│   ├── Menus/
│   ├── Modals/
│   ├── Navigation/
│   ├── Notices/
│   ├── Pages/
│   ├── Panels/
│   ├── Screens/
│   ├── Support/
│   ├── Tables/
│   ├── Tabs/
│   ├── Themes/
│   ├── Toolbar/
│   ├── Widgets/
│   ├── Admin.php
│   └── AdminServiceProvider.php
├── tests/
├── docs/
├── README.md
└── SPEC.md
```

---

## Dependency Rules

| Direction | Rule |
| --------- | ---- |
| Required | `core`, `ui`, `security`, `validation`, `support` |
| Optional | `database`, `fields`, `rest` (pagination / field render hooks) |
| Forbidden | `wordpress`, `builder`, GraphQL, CLI, business domain logic |
| Consumers | OpenMeta modules; `@openmeta/wordpress` menu bridge |

---

## Testing Strategy

| Layer | Covers |
| ----- | ------ |
| **Unit** | Layouts, pages, forms, tables, navigation, events |
| **Integration** | Screen render + capability gate |
| **WordPress** | N/A in package (bridge tested in wordpress) |
| **Performance** | Dashboard render budget |
| **Security** | Capability deny paths |

See [packages/TESTING.md](../TESTING.md).

---

## Future Scope

- Rich component library expansion
- Onboarding checklist widgets
- Never: Builder canvas, Gutenberg UI, field engine, REST server
