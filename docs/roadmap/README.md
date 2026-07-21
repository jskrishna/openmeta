# Roadmap

---

# Purpose

The OpenMeta roadmap defines the long-term implementation strategy of the framework.

Unlike feature lists, this roadmap organizes development into structured architectural phases. Each phase builds upon the previous one, ensuring predictable progress, maintainability, and high software quality.

**Versioned release train:** [release-milestones.md](./release-milestones.md) (`v0.1.0-alpha` → `v1.0.0`).  
**Post-REST order:** [ADR-0024](../adr/ADR-0024-post-rest-phase-order.md).

OpenMeta is a **PHP framework** with a **WordPress-first adapter** — Admin, Builder, GraphQL, and CLI mount on the foundation rather than owning it.

---

# Goals

The roadmap aims to:

- Define a clear implementation sequence
- Minimize architectural rework
- Reduce technical debt
- Enable incremental releases
- Support contributor collaboration
- Maintain alignment with project architecture
- Ensure every subsystem is fully documented before implementation

---

# Roadmap Philosophy

OpenMeta follows an Architecture First and Documentation First development methodology.

Every phase follows this workflow:

```text
Research → Architecture → Documentation → Review → Implementation → Testing → Release
```

No implementation should begin before the corresponding documentation is complete.

---

# Development Phases (canonical)

| Phase | Name | Doc |
| ----- | ---- | --- |
| 00 | Project Planning | [phase-00-planning.md](./phase-00-planning.md) |
| 01 | Framework Bootstrap | [phase-01-bootstrap.md](./phase-01-bootstrap.md) |
| 01.5 | Cursor rules | [phase-01.5-cursor-rules.md](./phase-01.5-cursor-rules.md) |
| 02 | Core | [phase-02-core.md](./phase-02-core.md) |
| 03 | Support | [phase-03-support.md](./phase-03-support.md) |
| 04 | Validation | [phase-04-validation.md](./phase-04-validation.md) |
| 05 | Security | [phase-05-security.md](./phase-05-security.md) |
| 06 | Database | [phase-06-database.md](./phase-06-database.md) |
| 07 | Field Engine | [phase-07-fields.md](./phase-07-fields.md) |
| 08 | REST (framework HTTP) | [phase-08-api.md](./phase-08-api.md) |
| **09** | **WordPress Adapter** | [phase-11-wordpress-integration.md](./phase-11-wordpress-integration.md) |
| **10** | **Admin UI** | [phase-09-admin.md](./phase-09-admin.md) |
| **11** | **Visual Builder** | [phase-10-visual-builder.md](./phase-10-visual-builder.md) |
| **12** | **GraphQL** | [phase-12-graphql.md](./phase-12-graphql.md) |
| **13** | **CLI** | [phase-13-cli.md](./phase-13-cli.md) |
| **14** | **Testing & QA** | [phase-14-testing.md](./phase-14-testing.md) |
| **15** | **v1.0 Release** | [phase-15-releases.md](./phase-15-releases.md) |

> Some filenames keep older numbers; **trust the Phase column above** and [ADR-0024](../adr/ADR-0024-post-rest-phase-order.md).

---

# Overall Development Flow

```text
Planning → Bootstrap → Core → Support → Validation → Security → Database
  → Field Engine → REST
  → WordPress Adapter → Admin UI → Visual Builder
  → GraphQL → CLI → Testing & QA → v1.0 Stable
```

---

# Related

- [ROADMAP.md](../../ROADMAP.md) (root quick reference)
- [release-milestones.md](./release-milestones.md)
- [packages/BLUEPRINTS.md](../../packages/BLUEPRINTS.md)
- [packages/TESTING.md](../../packages/TESTING.md)
