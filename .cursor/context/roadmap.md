# OpenMeta — roadmap context (Phase 01.5)

> Authoritative long form: `ROADMAP.md`, `docs/roadmap/release-milestones.md`, `docs/roadmap/phase-15-releases.md`, [ADR-0024](../../docs/adr/ADR-0024-post-rest-phase-order.md).

## Coding order (implementation)

```text
Core → Support → Validation → Security → Database → Fields
  → Rest → Wordpress (adapter) → UI → Admin → Builder
    → GraphQL → CLI → Testing gate → v1.0
```

One package / phase slice at a time. Against that package’s `SPEC.md` only.

## Release train (Phase 15 = Stable)

```text
v0.1.0-alpha   Core ✅
v0.2.0-alpha   Support ✅
v0.3.0-alpha   Validation ✅
v0.4.0-alpha   Security ✅
v0.5.0-alpha   Database ✅
v0.6.0-alpha   Field Engine ✅
v0.7.0-alpha   REST ✅
v0.8.0-alpha   WordPress Adapter  ← next
v0.9.0-alpha   Admin UI
v0.10.0-beta   Visual Builder
v0.11.0-beta   GraphQL
v0.12.0-beta   CLI
v1.0.0         Stable
```

## Agent phase prompts (`.ai/prompts/`)

| Prompt | Focus | Phase # |
| ------ | ----- | ------- |
| `phase-02-core-bootstrap.md` | Core | 02 |
| `phase-03-support.md` | Support | 03 |
| `phase-04-validation.md` | Validation | 04 |
| `phase-05-security.md` | Security | 05 |
| `phase-06-database.md` | Database | 06 |
| `phase-07-fields.md` | Fields | 07 |
| `phase-08-rest-api.md` | Rest | 08 |
| `phase-09-wordpress.md` | WordPress Adapter | 09 |
| `phase-10-admin.md` | Admin (+ UI) | 10 |
| `phase-11-builder.md` | Builder | 11 |
| `phase-12-testing.md` | Testing gate (also Phase 14) | 14 |

GraphQL / CLI prompts: add when those packages start (`phase-12-graphql`, `phase-13-cli`).

## Status snapshot

- Foundation **v0.1–v0.7** (Core → REST) ✅  
- Phase 01.5 Cursor rules ✅  
- **Next:** Phase 09 WordPress Adapter (`v0.8`)  
- **v1.0.0 Stable** ⏳  

## Exit criteria (any train)

1. SPEC Must not respected  
2. Testing layers green (Phase 14 / `packages/TESTING.md`)  
3. `composer ci` green  
4. CHANGELOG entry  
5. Milestone closed (when releasing)
