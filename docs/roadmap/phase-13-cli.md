# Phase 13 — CLI & Developer Tools

> Developer CLI for OpenMeta — bootstrap, inspect, migrate, and ops commands over the Core container.

Decision context: [ADR-0024](../adr/ADR-0024-post-rest-phase-order.md) · Train: [release-milestones.md](./release-milestones.md) (`v0.12.0-beta`)

---

## Goals

- Commands resolve services via Core DI (no global singletons)
- Database migrations / status helpers where SPEC allows
- Field / config inspection for developers
- Works without WordPress; optional WP-aware commands behind the adapter

---

## Must not

- Embed Admin UI or Builder canvas logic
- Own field-type or validation engines
- Hard-require WordPress for core commands

---

## Exit criteria (draft)

- [ ] CLI package SPEC + README
- [ ] At least: `about` / `list` / one migration or health command
- [ ] Unit + integration tests
- [ ] `composer ci` green
