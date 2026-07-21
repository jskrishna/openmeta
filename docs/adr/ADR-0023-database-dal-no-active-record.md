# ADR-0023: Database DAL — No Active Record

---

# Status

Accepted

---

# Context

OpenMeta needs a persistence layer that can sit on WordPress tables and custom tables, and later support multiple drivers (wpdb, PDO, SQLite) without forcing domain packages to change.

Active Record ORMs (Eloquent-style models that own persistence) couple domain objects to storage and make multi-driver / WP coexistence harder.

---

# Decision

OpenMeta **does not use Active Record**.

`@openmeta/database` is a **Database Abstraction Layer (DAL)** with this layering:

```text
Application
      ↓
Repository
      ↓
Query Builder
      ↓
Connection
      ↓
Driver
      ↓
Database Engine
```

Domain / application code talks to **repositories**. Repositories use the **query builder**. The query builder talks to a **connection**. Connections are produced by **drivers**. Engines (memory, PDO, future wpdb) stay behind the driver boundary.

---

# Consequences

Positive

- Storage-agnostic Fields / API / Admin / Builder
- WordPress tables + custom tables can share the same repository contracts
- New drivers without rewriting higher packages
- Clear testability via MemoryConnection

Negative

- More explicit repository / DTO wiring than Active Record convenience

Trade-offs

- Slightly more boilerplate; much better long-term boundaries

---

# Rejected alternatives

- Eloquent / Doctrine Active Record clones  
- Direct `$wpdb` or PDO usage from Fields/API  
- Mixing ORM models with WP post objects  

---

# Related

- ADR-0006 Storage Strategy  
- `packages/database/SPEC.md`
