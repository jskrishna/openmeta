# SPEC — `@openmeta/ui`

> **Implementation contract.** Implement against this document. [README](./README.md) is the short overview.

**Status:** ✅ Complete — Phase 9 / `v0.8.0-alpha` (PHP components; React surface later)

---

## Purpose

Shared React/UI component library and design primitives for `admin`, `builder`, and future surfaces.

---

## Component map

```text
Tokens
    Responsibilities
        ↓
Primitives
    Responsibilities
        ↓
Components
    Responsibilities
        ↓
Hooks
    Responsibilities
        ↓
Theme
    Responsibilities
```

Supporting: **utils**, optional thin PHP bridge (documented if added).

---

## Tokens

### Responsibilities

- Design tokens (color, space, type, radius, elevation)
- Stable naming for CSS variables / JS token maps

### Public contracts

- Token export / CSS variable contract

### Must not

- Encode domain meanings (e.g. “field invalid” business rules)

---

## Primitives

### Responsibilities

- Accessibility-first low-level building blocks (focus, button base, input base)
- Keyboard and ARIA baselines

### Public contracts

- Primitive component exports

### Must not

- Fetch APIs or check WordPress capabilities

---

## Components

### Responsibilities

- Composed UI: forms, tables, modals, notifications, etc.
- Documented prop contracts (TypeScript)

### Public contracts

- Published component export surface

### Must not

- Contain persistence or field-type logic

---

## Hooks

### Responsibilities

- Presentation-only hooks (e.g. disclosure, focus trap helpers)
- No server authz

### Public contracts

- Documented hooks API

### Must not

- Hide network side effects without explicit naming

---

## Theme

### Responsibilities

- Apply Tokens to runtime theme (light/dark later)
- Allow overrides via extension points

### Public contracts

- Theme provider / override API

### Must not

- Ship secrets or environment credentials in theme bundles

---

## Public Contracts (package index)

| API | Component |
| --- | --------- |
| Tokens | Tokens |
| Primitives | Primitives |
| Components | Components |
| Hooks | Hooks |
| Theme provider | Theme |

---

## Internal Components

| Component | Location |
| --------- | -------- |
| Tokens | `src/tokens/` |
| Primitives | `src/primitives/` or under components |
| Components | `src/components/` |
| Hooks | `src/hooks/` |
| Theme | `src/theme/` |
| utils | `src/utils/` |

---

## Folder Structure

```text
packages/ui/
├── src/
│   ├── tokens/
│   ├── primitives/
│   ├── components/
│   ├── hooks/
│   ├── theme/
│   └── utils/
├── tests/
├── README.md
└── SPEC.md
```

---

## Dependency Rules

| Direction | Rule |
| --------- | ---- |
| Required | `core` (monorepo alignment); React/TS when introduced |
| Optional | `support` |
| Forbidden | `database`, `api`, `fields` implementation details |
| Consumers | `admin`, `builder` |

---

## Lifecycle

```text
admin/builder mount → import @openmeta/ui → Theme → Components
```

No PHP provider required by default.

---

## Extension Points

- Token / Theme overrides
- Composition slots
- Icon / density / locale presentation hooks

---

## Performance

- Tree-shakeable exports
- Code-split heavy widgets
- Prefer CSS variables for theming

---

## Security

- Escape text by default
- Explicit sanitized HTML APIs only
- No secrets in client bundles

---

## Testing Strategy

| Layer | Covers |
| ----- | ------ |
| **Unit** | Components + Hooks; a11y primitives |
| **Integration** | Component composition / provider wiring |
| **WordPress compatibility** | N/A early (WP enqueue later if needed) |
| **Performance** | Bundle size budget |

See [packages/TESTING.md](../../TESTING.md) (Phase 10 gate).

---

## Future Scope

- Storybook
- Dark/light tokens
- Headless variants
