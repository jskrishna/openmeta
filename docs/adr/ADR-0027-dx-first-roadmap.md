# ADR-0027: DX-first roadmap — SDK 12, GraphQL 13, CLI 14, Marketplace 18, v1.0 at Phase 19

---

# Status

Accepted (supersedes the phase numbering of [ADR-0026](ADR-0026-complete-framework-ecosystem.md))

---

# Context

[ADR-0026](ADR-0026-complete-framework-ecosystem.md) sequenced the post-Builder train as GraphQL (12) → CLI (13) → Extension SDK (14) → Code Generator (15) → Testing (16) → Doc Generator (17) → Stable (18).

During implementation the order was revised for two reasons:

1. **The Extension SDK is foundational for everything downstream.** GraphQL, the CLI, code generators, and the doc generator all benefit from a stable extension/manifest/lifecycle contract. Building the SDK *first* lets later packages register types, commands, and generators through one extension surface instead of ad-hoc hooks. The SDK was therefore implemented as **Phase 12** and GraphQL as **Phase 13** (both shipped, `v0.11`/`v0.12`).

2. **Developer Experience should precede more runtime features.** After GraphQL, the highest-leverage work is tooling — a CLI, scaffolding, and generated docs — mirroring Laravel Artisan, Symfony Console, and Filament. A distribution surface (**Marketplace & Package Manager**) is added as an optional phase before the stable freeze.

---

# Decision

Adopt this authoritative phase order and release train (supersedes ADR-0026's Phases 12–18 numbering):

```text
Phase 12 — Extension SDK                     v0.11.0-beta   ✅ shipped
Phase 13 — GraphQL Package                   v0.12.0-beta   ✅ shipped
Phase 14 — CLI & Developer Tools             v0.13.0-beta   ← next
Phase 15 — Code Generator (Scaffolding)      v0.14.0-beta
Phase 16 — Testing, QA & Performance         continuous gate (no semver train)
Phase 17 — Documentation Generator           v0.15.0-beta
Phase 18 — Marketplace & Package Manager      v0.16.0-beta   (optional)
Phase 19 — Stable v1.0 Release               v1.0.0
```

Notes:

- **Phase 16 (Testing, QA & Performance)** remains a **continuous gate** on every train plus a dedicated pre-v1.0 hardening pass — no dedicated semver train.
- **Phase 18 (Marketplace)** is **optional**; if deferred, Stable moves directly after the Documentation Generator without renumbering the earlier trains.
- The Extension SDK package lives at `packages/extensions` (namespace `OpenMeta\Extensions`); "SDK" is its purpose, `extensions` its ecosystem-scalable name.

This ADR changes **only phase numbering and sequence**. Package boundaries, dependency rules, and "Must not" constraints from prior ADRs are unchanged.

---

# Consequences

Positive

- The extension contract is frozen before the tools that build on it (CLI, codegen, docgen).
- A cohesive DX arc — CLI → scaffolding → generated docs — lands before v1.0, easing adoption and extension authoring.
- Marketplace is explicitly optional, so v1.0 is not blocked on a distribution platform.

Negative / trade-offs

- v1.0 moves from Phase 18 → **Phase 19**; historical docs referencing Phase 18 Stable need redirects.
- Two ADRs (0026, 0027) now describe the tail; 0027 is authoritative.

---

# Alternatives considered

### Keep ADR-0026 order (GraphQL 12, CLI 13, SDK 14)

Rejected: building tooling before the extension contract forces rework when the SDK later reshapes registration.

### Fold Marketplace into v1.0

Rejected: distribution is a large, optional surface that should not gate the stable API freeze.

### Renumber from scratch

Rejected: the shipped SDK (12) and GraphQL (13) are already released under this numbering; only the tail (14+) is defined here.

---

# References

- [ADR-0024](ADR-0024-post-rest-phase-order.md) — post-REST foundation order
- [ADR-0025](ADR-0025-extended-roadmap-to-v1.md) — extended roadmap (superseded tail)
- [ADR-0026](ADR-0026-complete-framework-ecosystem.md) — superseded by this ADR for Phases 12–19 numbering
- [ROADMAP.md](../../ROADMAP.md) · [release-milestones.md](../roadmap/release-milestones.md)
