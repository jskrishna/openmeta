# `@openmeta/builder`

> Low-code **visual configuration engine** — canvas, schema, history, templates, conditions. Architecture only (no frontend UI).

**Status:** ✅ Phase 11 · **v0.10.0-beta**  
**Blueprint:** [SPEC.md](./SPEC.md)

Mounted in `@openmeta/admin` screens. Orchestrates `@openmeta/fields`, `@openmeta/validation`, `@openmeta/security` — never reimplements field types or persistence.

```bash
composer test:builder
composer ci
```

## Public API

```text
Builder (façade)
  → canvas / registry / schema / history / sessionState()
BuilderApplication (orchestrator)
```

## Spine

```text
Application → Canvas (Workspace) → Registry → Layouts → DragDrop
  → Selection → Inspector → Properties → Schema → Serialization
  → History → Clipboard → Templates → Library → Conditions → Preview → Events
```

## Docs

See [docs/README.md](./docs/README.md).
