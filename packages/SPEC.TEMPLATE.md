# SPEC.TEMPLATE.md

## Why SPEC exists

Kal ko jab agent / contributor ko bole:

> Build the Database package

usko 100 pages docs nahi deni — bas:

```text
packages/database/SPEC.md
```

Us document se **exactly** pata hona chahiye:

| Question | SPEC section |
| -------- | ------------ |
| Kya banana hai? | Purpose, Responsibilities, component spine |
| Kya **nahi** banana? | Must not / Non-goals inside each component |
| Dependencies kya hain? | Dependency Rules |
| Public API kya hai? | Public Contracts |
| Testing kaise hogi? | Testing Strategy (Phase 10: Unit → Integration → WP Compatibility → Performance) |

README = short overview. **SPEC = implementation contract.**

---

## Required sections

Copy this structure. Do not invent alternate top-level headings.

```markdown
# SPEC — `@openmeta/<package>`

> **Implementation contract.** Implement against this document. [README](./README.md) is the short overview.

**Status:** …

---

## Purpose

## Component map
(spine: each major piece → Responsibilities)

## <Component> …
### Responsibilities
### Public contracts
### Must not

## Public Contracts (package index)

## Internal Components

## Folder Structure

## Dependency Rules

## Lifecycle

## Extension Points

## Performance

## Security

## Testing Strategy

> Phase 10 gate — fill all four layers (use **N/A** only with a short reason):
>
> | Layer | What to cover |
> | ----- | ------------- |
> | Unit | Isolated classes / pure logic |
> | Integration | Cross-component flows inside this package |
> | WordPress Compatibility | WP versions / hooks / multisite as applicable |
> | Performance | Budgets / hot-path constraints |

Full matrix: [TESTING.md](./TESTING.md).

## Future Scope
```

| Section | Meaning |
| ------- | ------- |
| **Purpose** | Why the package exists |
| **Component map** | Ordered spine (what to build, in order) |
| **Responsibilities / Must not** | Exact build vs no-build boundary |
| **Public Contracts** | Stable APIs consumers may rely on |
| **Dependency Rules** | Required / optional / forbidden |
| **Testing Strategy** | **Phase 10 gate:** Unit → Integration → WordPress Compatibility → Performance ([TESTING.md](./TESTING.md)). Mark N/A only when justified. |
| **Future Scope** | Explicitly deferred — do not build now |

Spine examples:

- **Support:** Collections → Helpers → Str → Arr → Filesystem → Paths → Environment → UUID → Reflection → Traits  
- **Validation:** Rule Engine → Validator → Error Bag → Messages  
- **Security:** Permissions → Capabilities → Nonce → Sanitization → Escaping  
- **Database:** Connection → Schema → Migration → Repository → Query → Storage → Relations  
- **Fields:** Registry → Field Types → Validation → Storage → Rendering → REST → GraphQL  
- **API:** REST → Controllers → Resources → Authentication → Authorization  
- **Admin:** Dashboard → Menus → Screens → Forms → Tables → Settings  
- **Builder:** Visual Builder → Canvas → Components → Drag & Drop → Templates → Conditions  
