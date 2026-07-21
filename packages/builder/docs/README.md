# Builder package docs

Phase 11 — Visual Builder (`v0.10.0-beta`).

## Architecture

The builder is a **framework-only** low-code configuration engine. It produces portable schema documents and session descriptors. **No HTML, CSS, or JavaScript** lives in this package — frontend hosts (Admin chrome, future React/Vue packages) consume:

- `Builder::sessionState()` — screen id, nonce, schema, selection, library catalog
- `PreviewEngine::generate()` — field preview descriptors
- `InspectorPanel::describe()` — property metadata

## Module map

| Module | Role |
| ------ | ---- |
| `Application/` | Boot, save/discard, component mutations, events |
| `Canvas/` | Nodes, workspace (zoom/pan/grid/snap) |
| `Registry/` | Component discovery, categories, lazy metadata |
| `Layouts/` | Rows, columns, containers, sections, nesting |
| `DragDrop/` | Sources, targets, validation, reorder |
| `Selection/` | Single / multi-select layer |
| `Inspector/` | Settings metadata (labels, validation, conditions) |
| `Properties/` | Field Definition mapping |
| `Schema/` | Portable schema builder + manager |
| `Serialization/` | Export, import, validate, migrate |
| `History/` | Undo, redo, snapshots, transactions |
| `Clipboard/` | Copy / cut / paste |
| `Templates/` | Presets + import/export |
| `Library/` | Components, layouts, patterns, favorites |
| `Conditions/` | Field Engine condition reuse |
| `Preview/` | Preview contracts |
| `Events/` | Core event dispatcher hooks |

## Extension points

- Register `ComponentDescriptor` instances
- Register templates via `TemplateRegistry`
- Register layouts via `LayoutEngine`
- Listen for builder events (`ComponentAdded`, `SchemaSaved`, …)

## Dependency rules

May use: `core`, `support`, `validation`, `security`, `database`, `fields`, `ui`, `admin`  
Must not: reimplement field engine, render UI, write raw DB rows
