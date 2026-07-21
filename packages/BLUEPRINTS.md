# Package blueprints — construction set

> **No package implementation coding until this set is accepted.**  
> SPECs are the construction blueprints. That is the step most open-source projects skip — and where architecture drift starts.

## Why

```text
“Build <package>”
      ↓
packages/<package>/SPEC.md
      ↓
kya banana · kya nahi · dependencies · public API · testing
```

AI-assisted development stays predictable. Reviews stay easy. Each package can be developed and tested independently.

## Status — 11 / 11 spine blueprints

| # | Package | Spine | SPEC |
| - | ------- | ----- | ---- |
| 1 | Core ✅ | Application → Container → Kernel → Providers → Bootstrap | [SPEC](./core/SPEC.md) |
| 2 | Support ✅ | Arr → Str → Collections → Paths → Filesystem → Environment → UUID → Reflection → Helpers → Traits | [SPEC](./support/SPEC.md) |
| 3 | Validation ✅ | **Core service** (not Fields/API-owned): Registry → Validator → Results → Messages — reused framework-wide | [SPEC](./validation/SPEC.md) |
| 4 | Security ✅ | Permissions → Capabilities → Gate/Authorizer → Nonce/CSRF → Sanitize/Escape → Hash/Random/Tokens | [SPEC](./security/SPEC.md) |
| 5 | Database ✅ | **DAL** (not ORM): Connections → Query/Schema/Migrations → Repositories → Relationships/Transactions | [SPEC](./database/SPEC.md) |
| 6 | Fields ✅ | Registry → Factory → Manager → Definitions → Types → Groups/Conditions → Validation → Serialization/Hydration → Storage/Rendering contracts → Lifecycle/Events (+ REST/GQL exposure) | [SPEC](./fields/SPEC.md) |
| 7 | REST ✅ | Router → Middleware → Controllers → Resources → Auth contracts → GateAuthorizer | [SPEC](./rest/SPEC.md) |
| 7b | API ✅ | Application REST surface (field routes) on framework REST / legacy stack | [SPEC](./api/SPEC.md) |
| 8 | UI ✅ | Tokens → Primitives → Components → Hooks → Theme | [SPEC](./ui/SPEC.md) |
| 9 | Admin ✅ | Dashboard → Menus → Screens → Forms → Tables → Settings | [SPEC](./admin/SPEC.md) |
| 10 | Builder ✅ | Visual Builder → Canvas → Components → Drag & Drop → Templates → Conditions | [SPEC](./builder/SPEC.md) |
| 11 | Wordpress ✅ | Plugin Bootstrap → Hooks → Filters → Admin Pages → Capabilities → Gutenberg → REST mount | [SPEC](./wordpress/SPEC.md) |

> **Production train after REST** ([ADR-0024](../docs/adr/ADR-0024-post-rest-phase-order.md)):  
> WordPress Adapter → Admin → Builder → GraphQL → CLI → Testing → v1.0.  
> Spine packages may already exist; Phase 09+ work hardens them in that order.

## Rule

1. Complete / refine SPECs first  
2. Then code in [coding order](./core/docs/build-order.md)  
3. One package at a time against its SPEC only  
4. After each package: **[Phase 10 — Testing gate](./TESTING.md)** (Unit → Integration → WP Compatibility → Performance)  
5. Ship by **[Release Plan](../docs/roadmap/release-milestones.md)** (`v0.1` → `v1.0`)  
   Post-REST order: WordPress → Admin → Builder → GraphQL → CLI → Testing → v1.0 ([ADR-0024](../docs/adr/ADR-0024-post-rest-phase-order.md))

Template: [SPEC.TEMPLATE.md](./SPEC.TEMPLATE.md)
