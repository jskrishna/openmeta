# Phase 12 — GraphQL

> Framework GraphQL surface — mounts on Field Engine contracts and Rest/API authz patterns. **Not** a WordPress-only schema fork.

Decision context: [ADR-0024](../adr/ADR-0024-post-rest-phase-order.md) · Train: [release-milestones.md](./release-milestones.md) (`v0.11.0-beta`)

---

## Goals

- GraphQL type maps / resolvers driven by `@openmeta/fields` exposure contracts
- Reuse Security Gate + Validation (no parallel authz/validation engines)
- Host-agnostic server contracts; WordPress/WPGraphQL mount via `@openmeta/wordpress` / adapter bridges

---

## Must not

- Reimplement the Field Engine inside GraphQL resolvers
- Bypass Rest/Security authorization
- Require Admin or Builder packages

---

## Exit criteria (draft)

- [ ] Schema/type-map contracts documented
- [ ] Query/mutation happy path for field values
- [ ] AuthN/AuthZ fail-closed tests
- [ ] Phase 14 testing layers green for the GraphQL package
- [ ] `composer ci` green
