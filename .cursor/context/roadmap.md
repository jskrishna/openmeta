# OpenMeta — roadmap context (Phase 01.5)

> Authoritative long form: `ROADMAP.md`, `docs/roadmap/release-milestones.md`, `docs/roadmap/phase-18-releases.md`, [ADR-0024](../../docs/adr/ADR-0024-post-rest-phase-order.md), [ADR-0026](../../docs/adr/ADR-0026-complete-framework-ecosystem.md).

## Coding order (implementation)

```text
Core → … → Fields → Rest → Wordpress → UI → Admin → Builder
  → GraphQL → CLI → Extension SDK → Code Generator
    → Testing & Performance → Developer Doc Generator → v1.0
```

One package / phase slice at a time. Against that package’s `SPEC.md` only.

## Release train (Phase 18 = Stable)

```text
v0.1–v0.7   Foundation ✅
v0.8        WordPress Adapter ✅
v0.9        Admin UI ✅
v0.10-beta  Visual Builder ✅
v0.11-beta  GraphQL Package  ← next
v0.12-beta  CLI & Developer Tools
v0.13-beta  Extension SDK
v0.14-beta  Code Generator
v0.15-beta  Developer Documentation Generator
v1.0.0      Stable (Phase 18)
```

## Status snapshot

- Foundation **v0.1–v0.7** ✅ · Phases **09–11** ✅  
- **Next:** Phase 12 GraphQL Package (`v0.11`)  
- **v1.0.0 Stable** (Phase 18) ⏳  

## Exit criteria (any train)

1. SPEC Must not respected  
2. Testing layers green (Phase 16 / `packages/TESTING.md`)  
3. `composer ci` green  
4. CHANGELOG entry  
5. Milestone closed (when releasing)
