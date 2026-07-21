# ADR-0025: Extended roadmap — SDK, documentation generator, v1.0

---

# Status

Accepted

---

# Context

[ADR-0024](ADR-0024-post-rest-phase-order.md) established the post-REST train through GraphQL, CLI, Testing & QA, and v1.0. That order remains correct, but stopping at “plugin-complete” understates the product goal: OpenMeta should be a **complete extensible application framework** with developer experience comparable to Laravel, Symfony, or Filament — while remaining WordPress-first.

Shipping v1.0 immediately after CLI and testing would freeze public contracts before:

- A first-class **SDK & extension system** for third-party modules
- **Automated documentation** derived from SPEC, PHPDoc, and package manifests
- A dedicated **benchmarking** pass beyond the continuous five-layer gate

---

# Decision

Extend the canonical post-REST phase list ([ADR-0024](ADR-0024-post-rest-phase-order.md)) with two trains before Stable:

```text
Phase 11 — Visual Builder
Phase 12 — GraphQL API
Phase 13 — CLI & Developer Tools
Phase 14 — Testing, Benchmarking & QA
Phase 15 — SDK & Extension System
Phase 16 — Documentation Generator
Phase 17 — Stable v1.0 Release
```

Release train (after `v0.12.0-beta` CLI):

```text
v0.13.0-beta  SDK & Extension System
v0.14.0-beta  Documentation Generator
v1.0.0        Stable (Phase 17)
```

Rationale:

- **SDK & extensions** formalize how modules register providers, hooks, and semver-compatible public APIs — the “framework” story, not “custom fields plugin.”
- **Documentation generator** keeps Architecture First / Documentation First sustainable at scale (SPEC + code → published reference).
- **Benchmarking** in Phase 14 adds measurable performance baselines before the SemVer freeze.
- **v1.0 moves to Phase 17** so Stable includes SDK surfaces and generated docs as part of the supported product.

Phases 09–13 and the continuous testing gate are unchanged in intent; only the tail of the train is extended.

---

# Consequences

Positive

- Clear place for extension marketplace / module authoring before API freeze.
- Docs stay synchronized with implementation via tooling, not manual drift.
- v1.0 represents a full framework DX, not a minimal WP plugin.

Negative / trade-offs

- v1.0 timeline extends by two beta trains.
- New packages (or expanded `core` / `support` surfaces) may be required for SDK and docgen — each needs SPEC before implementation.
- ADR-0024 phase-15 “v1.0 Release” is superseded for numbering; historical docs may still say “Phase 15 = Release.”

---

# Alternatives considered

### Ship v1.0 after CLI (ADR-0024 as-is)

Rejected: freezes contracts before SDK and doc tooling exist; encourages ad-hoc extension patterns.

### SDK and docgen only post-v1.0

Rejected: third-party authors need stable extension contracts at v1.0; generated reference should ship with Stable.

### Merge SDK + docgen into one phase

Rejected: different skills, packages, and exit criteria; parallel doc work can start once SDK contracts are sketched.

> **Superseded (tail only):** Phases 14–18 and version mapping updated in [ADR-0026](ADR-0026-complete-framework-ecosystem.md) — adds **Code Generator** (Phase 15), moves Testing to Phase 16, Developer Doc Generator to Phase 17, Stable to **Phase 18**.
