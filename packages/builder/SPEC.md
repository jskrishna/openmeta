# SPEC — `@openmeta/builder`

> **Implementation contract.** Implement against this document. [README](./README.md) is the short overview.

**Status:** ✅ Phase 11 / `v0.10.0-beta`

**Host:** Mounted inside `@openmeta/admin` (Slots). Does not replace the admin shell.

---

## Purpose

Low-code **visual configuration engine** for OpenMeta schemas — canvas, component registry, layouts, drag & drop infrastructure, schema serialization, history, templates, and conditions — **without** redefining field types, rendering UI, or bypassing validation.

---

## Module map

```text
BuilderApplication
    ↓
Canvas (Workspace) + Selection
    ↓
Registry + Layouts + DragDrop
    ↓
Inspector + Properties (Field Definitions)
    ↓
Schema + Serialization + History + Clipboard
    ↓
Templates + Library + Conditions + Preview + Events
```

---

## Responsibilities

### Builder Application

- Own builder session orchestration (not business logic)
- Coordinate canvas mutations, schema save/discard, preview generation
- Enforce Permissions + Nonce via `@openmeta/security`
- Dispatch builder events via Core `EventDispatcher`

### Canvas

- Ordered nodes + nested children metadata
- Workspace: zoom, pan, grid, snap (architecture only)
- Selection layer binding

### Component Registry

- Register components with categories, tags, versioning
- Lazy metadata resolution
- Map field types from `@openmeta/fields` registry

### Layout Engine

- Rows, columns, containers, sections
- Nested layouts + responsive metadata

### Drag & Drop

- Drag sources, drop targets, reorder, nested drop validation
- No JavaScript — infrastructure contracts only

### Inspector / Properties

- Describe editable settings (labels, validation, conditions, visibility, style metadata)
- **Reuse Field Definitions** — no duplicate field controls

### Schema

- Portable, framework-independent JSON/array schema
- Versioned (`1.0.0` current)
- Migration from legacy `0.9.0-beta` node lists

### Serialization

- Export / import envelopes
- Validation via `@openmeta/validation`
- Version migration

### History

- Undo / redo / snapshots / transactions

### Templates & Library

- Template registry with categories, import/export
- Block library: components, layouts, patterns, favorites

### Conditions

- **Reuse `@openmeta/fields` ConditionEvaluator / ConditionGroup**
- No parallel condition DSL

### Preview

- Preview contracts (`PreviewResult`) — no HTML rendering

---

## Public Contracts

| API | Component |
| --- | --------- |
| `Builder` façade | canvas, registry, schema, history, sessionState |
| `BuilderApplication` | orchestrator |
| `Canvas` + `Workspace` | editing surface |
| `ComponentRegistry` | component discovery |
| `SchemaManager` | build / load / export / import |
| `HistoryManager` | undo / redo |
| `PreviewEngine` | preview descriptors |
| Events | ComponentAdded, SchemaSaved, … |

---

## Folder Structure

```text
packages/builder/
├── src/
│   ├── Application/
│   ├── Canvas/
│   ├── Clipboard/
│   ├── Components/        (reserved — descriptors live in Registry)
│   ├── Conditions/
│   ├── Contracts/
│   ├── DragDrop/
│   ├── Events/
│   ├── Exceptions/
│   ├── History/
│   ├── Inspector/
│   ├── Layouts/
│   ├── Library/
│   ├── Preview/
│   ├── Properties/
│   ├── Registry/
│   ├── Schema/
│   ├── Serialization/
│   ├── Selection/
│   ├── Support/
│   ├── Templates/
│   ├── App/               (VisualBuilder BC alias)
│   ├── Builder.php
│   └── BuilderServiceProvider.php
├── docs/
├── tests/
├── README.md
└── SPEC.md
```

---

## Dependency Rules

| Direction | Rule |
| --------- | ---- |
| Required | `core`, `ui`, `fields`, `validation`, `admin`, `security` |
| Optional | `support`, `database` (pagination N/A here) |
| Forbidden | Reimplementing field types, validation engine, DB schema, HTML/JS UI |
| Consumers | Admin host, WordPress adapter (outer glue) |

---

## Must not

- ❌ Render HTML/CSS/JS/Gutenberg UI in this package
- ❌ Own WP admin menus (admin + wordpress glue)
- ❌ Redefine field types or conditions engine
- ❌ Persist via `$wpdb` directly
- ❌ Use conditions as authorization

---

## Lifecycle

```text
BuilderServiceProvider::register → bind Application, Canvas, registries, SchemaManager, History
    ↓
BuilderServiceProvider::boot → admin Slot (JSON session descriptor)
    ↓
Editor session: canvas edits → history → validate → schema save → SchemaSaved event
```

---

## Testing Strategy

| Layer | Covers |
| ----- | ------ |
| **Unit** | Registry, Schema, History, Serialization, Templates, Conditions, Events |
| **Integration** | Save pipeline + nonce + permissions |
| **WordPress** | Admin slot registration smoke |
| **Performance** | Canvas scale budget |
| **Security** | Save denied without caps |

See [packages/TESTING.md](../../TESTING.md).

---

## Future Scope

- Frontend canvas host (React/Vue package)
- Collaborative editing
- AI-assisted schema suggestions (feature-flagged)
- Never: field-type engine, GraphQL server, raw migrations inside Builder
