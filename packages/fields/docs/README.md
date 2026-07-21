# `@openmeta/fields` docs

Package-local notes for the Field Engine. Canonical architecture lives in:

- [SPEC.md](../SPEC.md)
- [README.md](../README.md)
- [docs/fields/](../../../docs/fields/)

## Spine

```text
Registry → Factory → Definition → Types
    → Conditions / Groups
    → Validation (bridge) → Serialization → Storage (contracts + DB adapter)
    → Hydration → Rendering (contracts) → Events / Lifecycle
```

## Public API

Prefer:

- `FieldEngine` (façade)
- `FieldRegistry`
- `FieldFactory`
- `FieldManager`

Hide internals behind contracts in `Contracts/`.
