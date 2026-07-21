# Roadmap

---

# Purpose

The OpenMeta roadmap defines the long-term implementation strategy of the framework.

Unlike feature lists, this roadmap organizes development into structured architectural phases. Each phase builds upon the previous one, ensuring predictable progress, maintainability, and high software quality.

**Versioned release train:** [release-milestones.md](./release-milestones.md) (`v0.1.0-alpha` → `v1.0.0`).  
**Post-REST order:** [ADR-0024](../adr/ADR-0024-post-rest-phase-order.md) · **Ecosystem tail:** [ADR-0026](../adr/ADR-0026-complete-framework-ecosystem.md).

OpenMeta is a **complete PHP framework ecosystem** with a **WordPress-first adapter** — extend with plugins (SDK), automate with CLI, integrate via GraphQL, scaffold with codegen, and publish generated developer docs.

---

# Development Phases (canonical)

| Phase | Name | Doc |
| ----- | ---- | --- |
| 00 | Project Planning | [phase-00-planning.md](./phase-00-planning.md) |
| 01 | Framework Bootstrap | [phase-01-bootstrap.md](./phase-01-bootstrap.md) |
| 01.5 | Cursor rules | [phase-01.5-cursor-rules.md](./phase-01.5-cursor-rules.md) |
| 02–08 | Core … REST | see [ROADMAP.md](../../ROADMAP.md) |
| **09** | **WordPress Adapter** ✅ | [phase-11-wordpress-integration.md](./phase-11-wordpress-integration.md) |
| **10** | **Admin UI** ✅ | [phase-09-admin.md](./phase-09-admin.md) |
| **11** | **Visual Builder** ✅ | [phase-10-visual-builder.md](./phase-10-visual-builder.md) |
| **12** | **GraphQL Package** ← next | [phase-12-graphql.md](./phase-12-graphql.md) |
| **13** | **CLI & Developer Tools** | [phase-13-cli.md](./phase-13-cli.md) |
| **14** | **Extension SDK** | [phase-15-sdk-extensions.md](./phase-15-sdk-extensions.md) |
| **15** | **Code Generator** | [phase-15-code-generator.md](./phase-15-code-generator.md) |
| **16** | **Testing, QA & Performance** | [phase-14-testing.md](./phase-14-testing.md) |
| **17** | **Developer Documentation Generator** | [phase-16-documentation-generator.md](./phase-16-documentation-generator.md) |
| **18** | **Stable v1.0 Release** | [phase-18-releases.md](./phase-18-releases.md) |

> Some filenames keep older numbers; **trust the Phase column** and [ADR-0026](../adr/ADR-0026-complete-framework-ecosystem.md).

---

# Overall Development Flow

```text
… → WordPress Adapter → Admin UI → Visual Builder
  → GraphQL → CLI → Extension SDK → Code Generator
  → Testing, QA & Performance → Developer Doc Generator → v1.0 Stable
```

---

# Related

- [ROADMAP.md](../../ROADMAP.md)
- [release-milestones.md](./release-milestones.md)
- [packages/BLUEPRINTS.md](../../packages/BLUEPRINTS.md)
- [packages/TESTING.md](../../packages/TESTING.md)
