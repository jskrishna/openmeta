# ADR-0028: Final release tail — Documentation 17, Release Engineering 18, Stable 19

---

# Status

Accepted (refines the Phase 17–19 tail of [ADR-0027](ADR-0027-dx-first-roadmap.md))

---

# Context

[ADR-0027](ADR-0027-dx-first-roadmap.md) set the DX-first order and, for the tail, had Phase 17 (Documentation Generator), Phase 18 (Marketplace & Package Manager, optional), and Phase 19 (Stable v1.0).

With Testing/QA/Performance (Phase 16) delivered, the product is feature-complete. The remaining work is **shipping discipline**, and mature open-source frameworks (Laravel, Symfony, .NET, Spring) follow a consistent order: **polish documentation → automate the release pipeline → then declare a stable 1.0**. A **Marketplace** is a distribution *product*, not a prerequisite for a reliable 1.0, so it should move to **post-1.0**.

---

# Decision

Finalise the tail (Phases 12–16 unchanged from ADR-0027):

```text
Phase 17 — Documentation Platform & API Documentation   v0.15.0-beta
Phase 18 — Release Engineering & Package Distribution    v0.16.0-beta
Phase 19 — Stable v1.0 Launch                            v1.0.0
```

**Phase 17 — Documentation Platform:** developer docs, generated **API docs**, architecture docs, examples, and tutorials (supersedes the narrower "Documentation Generator").

**Phase 18 — Release Engineering & Package Distribution** (replaces "Marketplace" at this slot): Composer package publishing, GitHub Releases, Semantic Versioning automation, release automation, backward-compatibility checks, package signing, and distribution.

**Phase 19 — Stable v1.0 Launch:** `v1.0.0`, migration guide, **Extension SDK freeze**, public roadmap, and a Long-Term Support (LTS) policy.

**Marketplace & Package Manager** is **deferred to post-1.0** (a future phase), not a 1.0 gate.

Phase 16 (Testing, QA & Performance) remains a **continuous gate** with no dedicated semver train; its infrastructure shipped under `quality/` + CI.

---

# Consequences

Positive

- Documentation lands before release automation, which lands before the freeze — each de-risks the next.
- v1.0 is not blocked on building a marketplace platform.
- Third-party developers get docs + a repeatable release pipeline + an SDK freeze, i.e. a smooth, trustworthy adoption path.

Negative / trade-offs

- Three ADRs (0026, 0027, 0028) now describe the tail; **0028 is authoritative** for Phases 17–19.
- Marketplace slips to post-1.0 (acceptable — it is additive and non-blocking).

---

# Alternatives considered

### Keep Marketplace as Phase 18 (ADR-0027)

Rejected: a marketplace is a large product surface that should not gate a reliable 1.0; release engineering must come first.

### One combined "Release" phase

Rejected: docs, release automation, and the stable launch have distinct exit criteria and are safer shipped in sequence.

---

# References

- [ADR-0019](ADR-0019-versioning.md) — Semantic Versioning
- [ADR-0027](ADR-0027-dx-first-roadmap.md) — DX-first roadmap (tail refined here)
- [ROADMAP.md](../../ROADMAP.md) · [release-milestones.md](../roadmap/release-milestones.md)
