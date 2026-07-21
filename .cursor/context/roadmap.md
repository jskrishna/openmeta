# OpenMeta — roadmap context (Phase 01.5)

> Authoritative long form: `ROADMAP.md`, `docs/roadmap/release-milestones.md`, `docs/roadmap/phase-13-releases.md`.

## Coding order (implementation)

```text
Core → Support → Validation → Security → Database → Fields
  → API → UI → Admin → Builder → Wordpress
```

One package at a time. Against that package’s `SPEC.md` only.

## Release train (Phase 13)

```text
v0.1.0-alpha   Core
v0.2.0-alpha   Support
v0.3.0-alpha   Validation
v0.4.0-alpha   Security
v0.5.0-alpha   Database
v0.6.0-alpha   Field Engine
v0.7.0-alpha   REST API
v0.8.0-alpha   Admin (+ UI)
v0.9.0-beta    Builder (+ WordPress)
v1.0.0         Stable  ← next
```

## Agent phase prompts (`.ai/prompts/`)

| Prompt | Focus |
| ------ | ----- |
| `phase-02-core-bootstrap.md` | Core |
| `phase-03-support.md` | Support |
| `phase-04-validation.md` | Validation |
| `phase-05-security.md` | Security |
| `phase-06-database.md` | Database |
| `phase-07-fields.md` | Fields |
| `phase-08-rest-api.md` | API |
| `phase-09-wordpress.md` | WordPress glue |
| `phase-10-admin.md` | UI + Admin |
| `phase-11-builder.md` | Builder |
| `phase-12-testing.md` | Five-layer testing gate |

## Status snapshot

- Spines **v0.1–v0.9** ✅  
- Phase 12 testing gate ✅  
- Phase 01.5 Cursor rules ✅ (this folder)  
- **v1.0.0 Stable** ⏳  

## Exit criteria (any train)

1. SPEC Must not respected  
2. Phase 12 layers green  
3. `composer ci` green  
4. CHANGELOG entry  
5. Milestone closed (when releasing)
