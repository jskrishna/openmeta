# ADR-0024: Post-REST phase order (framework-first)

---

# Status

Accepted

---

# Context

After Phase 08 (`@openmeta/rest`), the original train ordered Admin and Builder before a full WordPress mount, and treated GraphQL/CLI as afterthoughts. That risks shipping UI that assumes WP glue prematurely, and framing OpenMeta as “only a plugin” rather than a PHP framework with a WordPress-first adapter.

---

# Decision

Canonical phases after the foundation (Core → … → REST):

```text
Phase 09 — WordPress Adapter
Phase 10 — Admin UI
Phase 11 — Visual Builder
Phase 12 — GraphQL
Phase 13 — CLI
Phase 14 — Testing & QA
Phase 15 — v1.0 Release
```

Rationale:

- Mount the WordPress adapter **before** production Admin/Builder so screens and canvas use real bridges (hooks, caps, REST mount) instead of inventing parallel stacks.
- Keep GraphQL and CLI as first-class framework surfaces before freezing SemVer at v1.0.
- Preserve **WordPress-first** (ADR-0003): the adapter is the primary host; the core remains CMS-agnostic.

---

# Consequences

Positive

- Cleaner layering: framework packages stay host-independent; Wordpress is glue.
- Admin/Builder implement against Rest + Security + Fields + WP bridges.
- Room for GraphQL/CLI without rushing v1.0.

Negative / trade-offs

- Existing early Admin/Builder/Wordpress spines may need alignment passes under this order.
- Release train versions after `v0.7` are remapped (see `docs/roadmap/release-milestones.md`).

---

# Alternatives considered

### Admin → Builder → WordPress (original)

Rejected for production architecture: UI before host adapter increases rework.

### GraphQL/CLI only after v1.0

Rejected: they are part of the “complete PHP framework” story and should soak before the stable freeze.
