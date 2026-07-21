# Phase 16 — Testing, QA & Performance

> Dedicated hardening pass before **v1.0**, plus the ongoing five-layer gate on every package.

Contract: [packages/TESTING.md](../../packages/TESTING.md) · Earlier doc: [phase-12-testing.md](./phase-12-testing.md) · Decision: [ADR-0026](../adr/ADR-0026-complete-framework-ecosystem.md)

> **Note:** Was “Phase 14” under [ADR-0024](../adr/ADR-0024-post-rest-phase-order.md) / [ADR-0025](../adr/ADR-0025-extended-roadmap-to-v1.md). **Phase 16** per [ADR-0026](../adr/ADR-0026-complete-framework-ecosystem.md).

---

## Layers (every package)

```text
Unit → Integration → WordPress → Performance → Security
```

---

## Phase 16 objectives (pre-v1.0)

- Matrix compliance across all ship packages
- GraphQL + CLI + Extension SDK + Code Generator + Builder covered
- **Performance** — documented budgets and regression baselines (boot, REST/GraphQL query, admin render, save pipeline, CLI cold start, codegen dry-run)
- Security deny / escape / nonce fail-closed green
- CI required checks stable

---

## Exit criteria

- [ ] `composer test:phase12` (or successor suite) green
- [ ] `composer ci` green
- [ ] Benchmark suite (or documented budgets) green for critical paths
- [ ] No open P0/P1 test gaps on public contracts
- [ ] Ready for Phase 17 (Developer Documentation Generator) and Phase 18 (Stable)
