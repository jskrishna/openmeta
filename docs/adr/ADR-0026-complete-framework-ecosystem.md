# ADR-0026: Complete framework ecosystem roadmap (v1.0 at Phase 18)

---

# Status

Superseded by [ADR-0027](ADR-0027-dx-first-roadmap.md) for phase numbering (SDK→12, GraphQL→13, CLI→14, Marketplace→18, v1.0→Phase 19). The ecosystem scope below remains valid; only the phase order changed.

---

# Context

[ADR-0025](ADR-0025-extended-roadmap-to-v1.md) extended the train with SDK, a documentation generator, and Stable at Phase 17. After Phase 11 (Visual Builder), the product goal is clearer: OpenMeta is a **complete extensible framework ecosystem** — not only a WordPress custom-fields stack.

Developers need distinct, first-class phases for:

- **GraphQL** integration (Phase 12)
- **CLI** automation (Phase 13)
- **Extension SDK** for third-party plugins (Phase 14)
- **Code generators** to scaffold packages, fields, and extensions (Phase 15)
- **Testing, QA & performance** hardening before freeze (Phase 16)
- **Developer documentation generator** — reference from SPEC + code (Phase 17)
- **Stable v1.0** only after all of the above (Phase 18)

Merging codegen and docgen, or placing testing before SDK, would blur exit criteria and ship Stable without scaffolding or extension tooling.

---

# Decision

Supersede the **tail** of [ADR-0025](ADR-0025-extended-roadmap-to-v1.md) (Phases 14–17 numbering) with:

```text
Phase 12 — GraphQL Package
Phase 13 — CLI & Developer Tools
Phase 14 — Extension SDK
Phase 15 — Code Generator
Phase 16 — Testing, QA & Performance
Phase 17 — Developer Documentation Generator
Phase 18 — Stable v1.0 Release
```

Release train (after `v0.10.0-beta` Visual Builder):

```text
v0.11.0-beta  GraphQL Package
v0.12.0-beta  CLI & Developer Tools
v0.13.0-beta  Extension SDK
v0.14.0-beta  Code Generator
v0.15.0-beta  Developer Documentation Generator
v1.0.0        Stable (Phase 18)
```

Phase 16 (Testing, QA & Performance) remains a **continuous gate** on every train plus a dedicated pre-v1.0 hardening pass — no separate semver train.

Phases 09–13 intent unchanged. Phase 11 (Visual Builder) complete at `v0.10.0-beta`.

---

# Consequences

Positive

- Clear separation: **extend** (SDK) vs **scaffold** (codegen) vs **document** (doc generator).
- GraphQL and CLI soak before extension contracts freeze.
- v1.0 includes generators and generated developer reference as supported product surfaces.

Negative / trade-offs

- One additional beta train (`v0.14` codegen) vs ADR-0025.
- v1.0 moves from Phase 17 → **Phase 18**; historical docs referencing Phase 17 Stable need redirects.
- New packages (`graphql`, `cli`, `sdk`, codegen tool) each require SPEC before implementation.

---

# Alternatives considered

### Keep ADR-0025 as-is (SDK + docgen only, no codegen phase)

Rejected: scaffolding is a distinct DX pillar (Laravel `make:*`, Symfony Maker) and should not be folded into docgen or SDK.

### Testing only after v1.0

Rejected: performance baselines and matrix compliance must gate Stable.

### Single “Developer Tools” mega-phase (CLI + SDK + codegen + docgen)

Rejected: violates one-reason-to-change per phase; harder to parallelize and review.

---

# References

- [ADR-0024](ADR-0024-post-rest-phase-order.md) — post-REST foundation order
- [ADR-0025](ADR-0025-extended-roadmap-to-v1.md) — superseded for Phases 14–17 numbering only
